<?php

namespace App\Controllers;

class PassengerController extends BaseController
{
    private $db;
    
    public function __construct()
    {
        $this->db = db_connect();
    }
    
    public function Verification()
    {
        $user = $this->request->getGet('user');
        $id = $this->request->getGet('schedule');
        $date = $this->request->getGet('date');
        //get the vID
        $class_type = ['Type 3','Type 4'];
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select("b.Vehicle_Class");
        $builder->join("tblclasses b","b.vID=a.vID","LEFT");
        $builder->WHERE("a.ID",$id)->WHERE("a.agentID",$user)->WHEREIN('b.Vehicle_Class',$class_type);
        $builder->WHERE("a.TrxnDate",$date)->WHERE("a.BookingNumber","");
        $data = $builder->get();
        if($row = $data->getRow())
        {
            if($row->Vehicle_Class=="Type 3")
            {
                //check the number of FOC added
                $builder = $this->db->table('tblrecords_merchant');
                $builder->select('COUNT(recordID)total');
                $builder->WHERE("ID",$id)->WHERE("customerID",$user)->WHERE('Passenger_Type','FOC');
                $builder->WHERE("TrxnDate",$date)->WHERE("BookingNumber","");
                $records = $builder->get();
                if($rows = $records->getRow())
                {
                    echo 1-$rows->total;
                }
            }
            else if($row->Vehicle_Class=="Type 4")
            {
                //check the number of FOC added
                $builder = $this->db->table('tblrecords_merchant');
                $builder->select('COUNT(recordID)total');
                $builder->WHERE("ID",$id)->WHERE("customerID",$user)->WHERE('Passenger_Type','FOC');
                $builder->WHERE("TrxnDate",$date)->WHERE("BookingNumber","");
                $records = $builder->get();
                if($rows = $records->getRow())
                {
                    echo 2-$rows->total;
                }
            }
            else
            {
                echo 0;
            }
        }
    }
    
    public function Save()
    {
        $passenger = $this->request->getPost('passenger');
        $fullname = $this->request->getPost('fullname');
        $address = $this->request->getPost('address');
        $phone = $this->request->getPost('phone');
        $age = $this->request->getPost('age');
        $passengerModel = new \App\Models\passengerModel();
        $values=[
            "Fullname"=>$fullname,
            "Contact"=>$phone,
            "Age"=>$age,
            "Address"=>$address,
        ];
        $passengerModel->update($passenger,$values);
        echo "Great! Successfully updated";
    }
    
    public function viewDetails()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('*');
        $builder->WHERE('recordID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>
            <form method="post" class="forms-sample" id="frmDetails">
                <input type="hidden" name="passenger" value="<?php echo $row->recordID ?>"/>
                <div class="form-group">
                    <label>Complete Name</label>
                    <input type="text" class="form-control form-control-lg" value="<?php echo $row->Fullname ?>" name="fullname"/>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control form-control-lg" name="address" style="height:120px;"><?php echo $row->Address ?></textarea>
                </div>
                <div class="form-group">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label>Contact No</label>
                            <input type="phone" name="phone" value="<?php echo $row->Contact ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  class="form-control form-control-lg" maxlength="11"/>   
                        </div>
                        <div class="col-lg-6">
                            <label>Age</label>
                            <input type="number" name="age" value="<?php echo $row->BirthDate ?>" class="form-control form-control-lg"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary update"><span class="mdi mdi-content-save"></span> Save Changes</button>
                </div>
            </form>
            <?php
        }
    }
    
    public function paymentBreakdown()
    {
        $code = $this->request->getGet('code');
        $user = $this->request->getGet('user');
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('IFNULL(SUM(Amount),0)total');
        $builder->WHERE('BookingNumber',$code);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>      
            <tr>
                <td>Base Fare</td><td style="text-align:right;"><?php echo number_format($row->total,2) ?></td>
            </tr>
            <?php
        }
        //cargo
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(Rate),0)rate');
        $builder->WHERE('BookingNumber',$code);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            ?>      
            <tr>
                <td>Total Cargo Rate</td><td style="text-align:right;"><?php echo number_format($row->rate,2) ?></td>
            </tr>
            <?php
        }
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(Discount),0)discount');
        $builder->WHERE('BookingNumber',$code);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            ?>      
            <tr>
                <td>Discount</td><td style="text-align:right;"><?php echo number_format($row->discount,2) ?></td>
            </tr>
            <?php
        }
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(NetAmount),0)price');
        $builder->WHERE('BookingNumber',$code);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            ?>      
            <tr>
                <td>Sub-Total Cargo Amount</td><td style="text-align:right;"><?php echo number_format($row->price,2) ?></td>
            </tr>
            <?php
        }
        //get the admin fee
        $admin_fee=0;
        $builder = $this->db->table('tblconvenience_merchant');
        $builder->select('SUM(Amount)admin_fee');
        $builder->WHERE('BookingNumber',$code);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>      
            <tr>
                <td>Admin Fee</td><td style="text-align:right;"><?php echo number_format($row->admin_fee,2) ?></td>
            </tr>
            <?php
        }
    }
    
    
    public function Confirmation()
    {
        //database
        $savePassenger = new \App\Models\passengerModel();
        $adminFee = new \App\Models\convenienceModel();
        $cargoModel = new \App\Models\cargoModel();
        $paymentModel = new \App\Models\paymentModel();
        //variables
        $user = $this->request->getPost('user');
        $id = $this->request->getPost('schedule');
        $date = $this->request->getPost('date');
        $port = $this->request->getPost('port');
        $agent = $this->request->getPost('agent');
        $code= "";
        //generate booking number
        $builder = $this->db->table('tblcustomer_payment_merchant');
        $builder->select('COUNT(*)+1 as total');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $code = "IA-".str_pad($row->total, 7, '0', STR_PAD_LEFT)."-".$port;
        }
        //compute the total
        $net=0;$total=0;$price=0;
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('IFNULL(SUM(Amount),0)total');
        $builder->WHERE('customerID',$user)->WHERE('BookingNumber','');
        $builder->WHERE('Status',0)->WHERE('ID',$id)->WHERE('TrxnDate',$date);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $total=$row->total;
        }
        //cargo
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(NetAmount),0)price');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber','');
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            $price = $row->price;
        }
        //get the admin fee
        $admin_fee=0;
        $builder = $this->db->table('tblconvenience_merchant');
        $builder->select('SUM(Amount)admin_fee');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber','')->WHERE('Date',$date);
        $data = $builder->get();
        if($fee = $data->getRow())
        {
            $admin_fee = $fee->admin_fee;
        }
        $net = $total + $price + $admin_fee;
        //get the vessel
        $vessel=0;
        $builder = $this->db->table('tblschedule');
        $builder->select('vesselID');
        $builder->WHERE('ID',$id);
        $record = $builder->get();
        if($row = $record->getRow())
        {
            $vessel = $row->vesselID;   
        }
        $value = [
                'ID'=>$id,'vesselID'=>$vessel,'TrxnDate'=>$date,
                'TypeOfPayment'=>'','ReferenceCode'=>'',
                'BookingNumber'=>$code,'TotalAmount'=>$net,
                'PayAmount'=>0.00,'ChangeAmount'=>0.00,
                'Remarks'=>'Unpaid',
                ];
        $paymentModel->save($value);
        //generate seat number
        //update the tblrecords
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('recordID,Accommodation');
        $builder->WHERE('ID',$id)->WHERE('TrxnDate',$date);
        $builder->WHERE('agentID',$agent)->WHERE('customerID',$user);
        $builder->WHERE('BookingNumber','')->WHERE('Status',0);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            $capacity=0;$key="";
            if($row->Accommodation=="Business Class")
            {
                $key = "B";
            }
            else if($row->Accommodation=="Premium Economy")
            {
                $key = "P";
            }
            else if($row->Accommodation=="Economy")
            {
                $key = "E";
            }
            else
            {
                $key = "PWD";
            }
            
            //get the capacity of the vessel
            $builder = $this->db->table('tblcapacity');
            $builder->select('Capacity');
            $builder->WHERE('vesselID',$vessel)->WHERE('Seat_Type',$row->Accommodation);
            $cap = $builder->get();
            if($rows = $cap->getRow())
            {
                $capacity = $rows->Capacity;
            }
            $list = array();$lists = array();
            for($x=1;$x<=$capacity;$x++)
            {
                array_push($list,sprintf("%03d",$x).$key);
            }
            $numbers = array();
            $builder = $this->db->table('accommodation_capacity_v2');
            $builder->select('SeatNumber');
            $builder->WHERE('Seat_Type',$row->Accommodation)->WHERE('ID',$id)->WHERE('TrxnDate',$date);
            $datas = $builder->get();
            foreach($datas->getResult() as $rowx)
            {
                array_push($numbers,$rowx->SeatNumber);
            }
            $lists = array_diff($list,$numbers);
            $Obj = json_decode(json_encode($lists));
            foreach($Obj as $object)
            {
                $values = [
                    'SeatNumber'=>$object,
                    'BookingNumber'=>$code,
                    'Status'=>1,
                ];
                $savePassenger->update($row->recordID,$values);
            }
        }
        //update the admin fee
        $builder = $this->db->table('tblconvenience_merchant');
        $builder->select('convID');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber','')->WHERE('Date',$date);
        $datas = $builder->get();
        foreach($datas->getResult() as $row)
        {
            $values = [
                'BookingNumber'=>$code,
            ];
            $adminFee->update($row->convID,$values);
        }
        //update the cargo
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('cID');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber','');
        $datas = $builder->get();
        foreach($datas->getResult() as $row)
        {
            $values = [
                'BookingNumber'=>$code,
            ];
            $cargoModel->update($row->cID,$values);
        }
        return redirect()->to('payment/'.$code); 
    }
    
    public function Discount()
    {
        $port = $this->request->getGet('port');
        $agent = $this->request->getGet('agent');
        $builder = $this->db->table('tblagentroute');
        $builder->select('Discount');
        $builder->WHERE('agentID',$agent);
        $builder->WHERE('portID',$port);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->Discount;
        }
    }
    
    public function Vehicles()
    {
        $builder = $this->db->table('tblvehicles');
        $builder->select('*');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option value='<?php echo $row->vID ?>'><?php echo $row->Name ?></option>
            <?php
        }
    }
    
    public function vehicleModel()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblvehicle_model');
        $builder->select('mID,Name');
        $builder->WHERE('vID',$val);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option value="<?php echo $row->mID ?>"><?php echo $row->Name ?></option>
            <?php
        }
    }
    
    public function Summary()
    {
        $user = $this->request->getGet('user');
        $id = $this->request->getGet('schedule');
        $date = $this->request->getGet('date');
        $total=0;$cargo = 0;$admin = 0;
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('IFNULL(SUM(Amount),0)total');
        $builder->WHERE('customerID',$user)->WHERE('BookingNumber',"")->WHERE('TrxnDate',$date)->WHERE('Status',0)->WHERE('ID',$id);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $total = $row->total;
        }
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(NetAmount),0)price');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber',"")->WHERE('ID',$id);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            $cargo = $row->price;
        }
        //get the admin fee
        $admin_fee=0;
        $builder = $this->db->table('tblconvenience_merchant');
        $builder->select('SUM(Amount)admin_fee');
        $builder->WHERE('userID',$user)->WHERE('BookingNumber',"")->WHERE('Date',$date);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $admin = $row->admin_fee;
        }
        echo number_format($total+$cargo+$admin,2);
    }
    
    public function Passenger()
    {
        $user = $this->request->getGet('user');
        $id = $this->request->getGet('schedule');
        $date = $this->request->getGet('date');
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('recordID,Fullname,Accommodation,SeatNumber,BirthDate as Age');
        $builder->WHERE('ID',$id)->WHERE('customerID',$user);
        $builder->WHERE('TrxnDate',$date)->WHERE('BookingNumber','');
        $builder->orderby('recordID','DESC');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-block">
                <div class="form-check w-100">
                    <label class="form-check-label">
                        <b style='font-size:18px;'><?php echo $row->Fullname ?></b>/<?php echo $row->Age ?>&nbsp;
                        <button type="button" class="btn btn-sm delete" value="<?php echo $row->recordID ?>"><span class="mdi mdi-delete-forever"></span> DELETE</button>
                    </label>
                    <div class="d-flex mt-2">
                        <div class="ps-4 text-small me-3"><?php echo $row->Accommodation ?></div>
                        <div class="badge badge-opacity-warning me-3"><?php echo $row->SeatNumber ?></div>
                    </div>
                </div>
            </li>
            <?php
        }
    }
    
    public function removePassenger()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table("tblrecords_merchant");
        $builder->WHERE('recordID',$val);
        $builder->delete();
        
        $builder = $this->db->table("tblconvenience_merchant");
        $builder->WHERE('customerID',$val);
        $builder->delete();
        
        $builder = $this->db->table('tblcargo_merchant');
        $builder->WHERE('customerID',$val);
        $builder->delete();
        echo "Success";
    }
    
    public function removeAll()
    {
        $user = $this->request->getPost('user');
        $id = $this->request->getPost('schedule');
        $date = $this->request->getPost('date');
        //tblrecords
        $builder = $this->db->table("tblrecords_merchant");
        $builder->WHERE('customerID',$user);
        $builder->WHERE('ID',$id);
        $builder->WHERE('TrxnDate',$date);
        $builder->WHERE('Status',0);
        $builder->delete();
        //admin fee
        $builder = $this->db->table("tblconvenience_merchant");
        $builder->WHERE('userID',$user);
        $builder->WHERE('Date',$date);
        $builder->WHERE('BookingNumber',"");
        $builder->delete();
        //delete all the cargo records
        $builder = $this->db->table('tblcargo_merchant');
        $builder->WHERE('userID',$user);
        $builder->WHERE('BookingNumber',"");
        $builder->delete();
        echo "Successfully removed all the records";
    }
    
    public function savePassenger()
    {
        $savePassenger = new \App\Models\passengerModel();
        $adminFee = new \App\Models\convenienceModel();
        $cargoModel = new \App\Models\cargoModel();
        //variables
        $schedule = $this->request->getPost('schedule');
        $port = $this->request->getPost('port');
        $user = $this->request->getPost('user');
        $date = $this->request->getPost('date');
        $agent = $this->request->getPost('agent');
        $pass_type = $this->request->getPost('passenger_type');
        $customer = $this->request->getPost('customer_type');
        $discount  = $this->request->getPost('discount');
        $seat = $this->request->getPost('accommodation');
        $passenger = $this->request->getPost('passenger');
        $contact = $this->request->getPost('phone');
        $gender = $this->request->getPost('gender');
        $age = $this->request->getPost('age');
        $address = $this->request->getPost('address');
        $vID = 0;
        if(empty($this->request->getPost('vehicle'))){$vID=0;}else{$vID=$this->request->getPost('vehicle');}
        $mID = 0;
        if(empty($this->request->getPost('model'))){$mID=0;}else{$mID=$this->request->getPost('model');}
        $plate_num = $this->request->getPost('plateNumber');
        $id_num="";$amount=0.00;$email="N/A";
        $code= "";
        
        $validation = $this->validate([
            'passenger'=>'required',
            'phone'=>'required',
            'gender'=>'required',
            'age'=>'required'
            ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form to continue";
        }
        else
        {
            if($pass_type=="DRIVER" && empty($vID))
            {
                echo "Invalid! Please select vehicle type";
            }
            else if($pass_type=="DRIVER" && empty($plate_num))
            {
                echo "Invalid! Please enter your plate number";
            }
            else
            {
                //check if peak season
                $getPeakSeason = $this->db->table('peak_season_dates');
                $getPeakSeason->select('date_from, date_to');
                $getPeakSeason->WHERE('status',1)
                            ->WHERE('date_from <=', $date)
                            ->WHERE('date_to >=',$date);
                $peakSeasons = $getPeakSeason->get();
                $peakSeasons = $peakSeasons->getResult();
                
                $departureDateTimestamp = strtotime($date);
                $isPeakSeason = 0;
                
                foreach ($peakSeasons as $peakSeason) {
                    $fromTimestamp = strtotime($peakSeason->date_from);
                    $toTimestamp = strtotime($peakSeason->date_to);
                
                    if (($departureDateTimestamp >= $fromTimestamp && $departureDateTimestamp <= $toTimestamp)) {
                        $isPeakSeason = 1;
                        break;
                    }
                }
                    
                $builder = $this->db->table('tblprice');
                $builder->select('Amount');
                $builder->WHERE('Seat_Type',$seat);
                $builder->WHERE('portID',$port);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $amount =  $row->Amount;
                }
                //get the economy price
                $economy=0.00;
                $builder = $this->db->table('tblprice');
                $builder->select('Amount');
                $builder->WHERE('Seat_Type','Economy');
                $builder->WHERE('portID',$port);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $economy =  $row->Amount;
                }
                //generate seat number
                $vessel=0;$capacity=0;$key="";
                if($seat=="Business Class"||$seat=="Business Class w/o AC"){$key="B";}
                else if($seat=="Premium Economy"||$seat=="Premium Economy w/o AC"){$key = "P";}
                else if($seat=="Economy"){$key = "E";}
                else{$key = "PWD";}
                //get the vesselID
                $builder = $this->db->table('tblschedule');
                $builder->select('vesselID');
                $builder->WHERE('ID',$schedule);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $vessel = $row->vesselID;
                }
                //get the capacity of the vessel
                $builder = $this->db->table('tblcapacity');
                $builder->select('Capacity');
                $builder->WHERE('vesselID',$vessel)->WHERE('Seat_Type',$seat);
                $cap = $builder->get();
                if($row = $cap->getRow())
                {
                    $capacity = $row->Capacity;
                }
                $list = array();$lists = array();
                for($x=1;$x<=$capacity;$x++)
                {
                    array_push($list,sprintf("%03d",$x).$key);
                }
                $numbers = array();
                $builder = $this->db->table('accommodation_capacity_v2');
                $builder->select('SeatNumber');
                $builder->WHERE('Seat_Type',$seat)->WHERE('ID',$schedule)->WHERE('TrxnDate',$date);
                $datas = $builder->get();
                foreach($datas->getResult() as $row)
                {
                    array_push($numbers,$row->SeatNumber);
                }
                $lists = array_diff($list,$numbers);
                $Obj = json_decode(json_encode($lists));
                foreach($Obj as $object)
                {
                    $resultData[] = $object;
                }
                if(count($resultData) == 0 || (count($datas->getResult()) >= (0.30 * $capacity) && $isPeakSeason == 1)){
                    echo 'The maximum seat capacity has been reached for the selected accommodation.';
                    return;
                }
                $seat_num = "TBD";
                if($pass_type=="DRIVER")
                {
                    //validate if already added
                    $builder = $this->db->table('tblrecords_merchant');
                    $builder->select('recordID');
                    $builder->WHERE('Passenger_Type','DRIVER')
                            ->WHERE('ID',$schedule)
                            ->WHERE('TrxnDate',$date)
                            ->WHERE('BookingNumber','')
                            ->WHERE('customerID',$user);
                    $records = $builder->get();
                    if($list = $records->getRow())
                    {
                        echo "Invalid! One Driver per transaction only";
                    }
                    else
                    {
                        $discounted = $amount-$economy;
                        $values = [
                            'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_type,'ID_number'=>$id_num,'Discount'=>$discount,
                            'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                            'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_num,
                            'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                        ];
                        $savePassenger->save($values);
                        //admin fee
                        $charge = 27.00;
                        $customerID=0;
                        $builder = $this->db->table('tblrecords_merchant');
                        $builder->select("recordID");
                        $builder->WHERE('Fullname',$passenger);
                        $builder->WHERE('Gender',$gender);
                        $builder->WHERE('BirthDate',$age);
                        $builder->WHERE('ID',$schedule);
                        $builder->WHERE('TrxnDate',$date);
                        $data = $builder->get();
                        if($row = $data->getRow())
                        {
                            $customerID = $row->recordID;
                            $values = [
                                'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                            ];
                            $adminFee->save($values);
                        }
                        //get the rate of the vehicle and stored to the tblcargo
                        $cargo_fee=0;
                        $builder = $this->db->table('tblcargo_price');
                        $builder->select('Amount');
                        $builder->WHERE('portID',$port);
                        $builder->WHERE('vID',$vID);
                        $datas = $builder->get();
                        if($rows = $datas->getRow())
                        {
                            $cargo_fee = $rows->Amount;
                            $deducted = ($rows->Amount*$discount);
                            $discount_fare = $cargo_fee - $deducted;
                            $values = [
                                'ID'=>$schedule,'customerID'=>$customerID,'BookingNumber'=>$code,'Rate'=>$rows->Amount,'Discount'=>$deducted,'NetAmount'=>$discount_fare,'userID'=>$user,
                            ];
                            $cargoModel->save($values);
                        }
                        echo "Success";
                    }
                }
                else if($pass_type=="FULL")
                {
                    //passenger category
                    $new_discount=0;$discounted=0;
                    $pass_code="";$seat_code="";
                    switch($customer)
                    {
                        case "Adult":
                            $pass_code = "FULL";
                            $new_discount = 0;
                            $discounted = $amount;
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Minor":
                            $pass_code = "FULL";
                            $new_discount = 0;
                            $discounted = $amount;
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Student":
                            $pass_code = "STU";
                            $new_discount = 0.2;
                            $discounted = $amount - ($amount*$new_discount);
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "PWD":
                            $pass_code = "PWD";
                            $new_discount = 0.2;
                            $discounted = $amount*0.80/1.12;
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Senior Citizen":
                            $pass_code = "SC";
                            $new_discount = 0.2;
                            $discounted = $amount*0.80/1.12;
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Children (4 to 7 years old)":
                            $pass_code = "HALF";
                            $new_discount = 0.20; // 0.20;
                            $discounted = $amount - ($amount*$new_discount);
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "FOC":
                            $pass_code = "FOC";
                            $new_discount = 0;
                            $discounted = 0.00;
                            $seat_code = $seat_num;
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Children 3 years old and below":
                            $pass_code = "FOC";
                            $new_discount = 0;
                            $discounted = 0.00;
                            $seat_code = "000";
                            $values = [
                                'portID'=>$port,'ID'=>$schedule,'agentID'=>$agent,'Passenger_Type'=>$pass_code,'ID_number'=>$id_num,'Discount'=>$new_discount,
                                'Amount'=>$discounted,'vID'=>$vID,'mID'=>$mID,'plate_number'=>$plate_num,'Fullname'=>$passenger,'Contact'=>$contact,'BirthDate'=>$age,
                                'EmailAddress'=>$email,'Address'=>$address,'Gender'=>$gender,'Insured'=>0,'Accommodation'=>$seat,'SeatNumber'=>$seat_code,
                                'TrxnDate'=>$date,'BookingNumber'=>$code,'Remarks'=>"Walk-In",'Status'=>0,'customerID'=>$user,
                            ];
                            $savePassenger->save($values);
                            //admin fee
                            $charge = 2.00;
                            $builder = $this->db->table('tblrecords_merchant');
                            $builder->select("recordID");
                            $builder->WHERE('Fullname',$passenger);
                            $builder->WHERE('Gender',$gender);
                            $builder->WHERE('BirthDate',$age);
                            $builder->WHERE('ID',$schedule);
                            $builder->WHERE('TrxnDate',$date);
                            $data = $builder->get();
                            if($row = $data->getRow())
                            {
                                $values = [
                                    'BookingNumber'=>$code,'customerID'=>$row->recordID,'Amount'=>$charge,'Date'=>$date,'userID'=>$user,
                                ];
                                $adminFee->save($values);
                            }
                            echo "Success";
                            break;
                        case "Driver":
                            echo "Please select other classification of passenger";
                            break;
                        default:
                            echo "Please select other classification of passenger";
                            break;
                    } 
                }
            }
        }
    }
    
    public function getOnlineAccommodations(){
        $trip  = $this->request->getGet('choice');
        $departureDate = $this->request->getGet('fromdate');
        $returnDate = $this->request->getGet('todate');
        $departureScheduleID = $this->request->getGet('departID');
        $returnScheduleID = $this->request->getGet('departID');
        $departureVessel = $this->request->getGet('departureVessel');
        $returnVessel = $this->request->getGet('returnVessel');
        $port = $this->request->getGet('port');
        
        if($departureVessel == ''){
            $getVesselID = $this->db->table('tblschedule');
            $getVesselID->select('vesselID');
            $getVesselID->WHERE('ID', $departureScheduleID);
            $vesselID = $getVesselID->get();
            if($row = $vesselID->getRow())
            {
                $departureVessel = $row->vesselID;
            }
        }
        
        $getPeakSeason = $this->db->table('peak_season_dates');
        $getPeakSeason->select('date_from, date_to');
        $getPeakSeason->WHERE('status',1)
                    ->WHERE('date_from <=', $departureDate)
                    ->WHERE('date_to >=',$departureDate)
                    ->orWHERE('date_from <=',$returnDate)
                    ->WHERE('date_to >=',$returnDate);
        $peakSeasons = $getPeakSeason->get();
        $peakSeasons = $peakSeasons->getResult();
        
        $departureDateTimestamp = strtotime($departureDate);
        $returnDateTimestamp = strtotime($returnDate);
        $isPeakSeason = 0;
        
        foreach ($peakSeasons as $peakSeason) {
            $fromTimestamp = strtotime($peakSeason->date_from);
            $toTimestamp = strtotime($peakSeason->date_to);
        
            if (($departureDateTimestamp >= $fromTimestamp && $departureDateTimestamp <= $toTimestamp) || ($returnDateTimestamp >= $fromTimestamp && $returnDateTimestamp <= $toTimestamp)) {
                $isPeakSeason = 1;
                break;
            }
        }
        
        if($isPeakSeason == 1){
            
            $getDeparturePassengerCount = $this->db->table('accommodation_capacity_v2_OL_and_IA');
            $getDeparturePassengerCount->select('Seat_Type, COUNT(*) count');
            $getDeparturePassengerCount->WHERE('ID', $departureScheduleID)
                    ->WHERE('TrxnDate',$departureDate)
                    ->WHERE('SeatNumber!=','000')
                    ->groupBy('Seat_Type');
            $departurePassengerCount = $getDeparturePassengerCount->get();
            
            $departureBC = 0;
            $departurePE = 0;
            $departureE = 0;
            foreach($departurePassengerCount->getResult() as $row)
            {
                if($row->Seat_Type == 'Business Class' ||$row->Seat_Type == 'Business Class w/o AC'){
                    $departureBC = $row->count;
                }
                if($row->Seat_Type == 'Premium Economy'||$row->Seat_Type == 'Premium Economy w/o AC'){
                    $departurePE = $row->count;
                }
                if($row->Seat_Type == 'Economy'){
                    $departureE = $row->count;
                }
            }
            
            $departureCapacityBC = 0;
            $departureCapacityPE = 0;
            $departureCapacityE = 0;
            
            $getDepartureVesselCapacity = $this->db->table('tblcapacity');
            $getDepartureVesselCapacity->select('Seat_Type,Capacity');
            $getDepartureVesselCapacity->WHERE('vesselID', $departureVessel);
            $departureVesselCapacity = $getDepartureVesselCapacity->get();
            foreach($departureVesselCapacity->getResult() as $row)
            {
                if($row->Seat_Type  == 'Business Class'||$row->Seat_Type == 'Business Class w/o AC'){
                    $departureCapacityBC = $row->Capacity;
                }
                if($row->Seat_Type  == 'Premium Economy'||$row->Seat_Type == 'Premium Economy w/o AC'){
                    $departureCapacityPE = $row->Capacity;
                }
                if($row->Seat_Type  == 'Economy'){
                    $departureCapacityE = $row->Capacity;
                }
            }
            
            
            $returnBC = 0;
            $returnPE = 0;
            $returnE = 0;
            
            $returnCapacityBC = 1;
            $returnCapacityPE = 1;
            $returnCapacityE = 1;
            if($trip == 'roundtrip'){
                $getReturnPassengerCount = $this->db->table('accommodation_capacity_v2_OL_and_IA');
                $getReturnPassengerCount->select('Seat_Type, COUNT(*) count');
                $getReturnPassengerCount->WHERE('ID', $returnScheduleID)
                        ->WHERE('TrxnDate', $returnDate)
                        ->WHERE('SeatNumber!=','000')
                        ->groupBy('Seat_Type');
                $returnPassengerCount = $getReturnPassengerCount->get();
                foreach($returnPassengerCount->getResult() as $row)
                {
                    if($row->Seat_Type == 'Business Class'||$row->Seat_Type == 'Business Class w/o AC'){
                        $returnBC = $row->count;
                    }
                    if($row->Seat_Type == 'Premium Economy'||$row->Seat_Type == 'Premium Economy w/o AC'){
                        $returnPE = $row->count;
                    }
                    if($row->Seat_Type == 'Economy'){
                        $returnE = $row->count;
                    }
                }
                
                $getReturnVesselCapacity = $this->db->table('tblcapacity');
                $getReturnVesselCapacity->select('Seat_Type,Capacity');
                $getReturnVesselCapacity->WHERE('vesselID', $returnVessel);
                $returnVesselCapacity = $getReturnVesselCapacity->get();
                foreach($returnVesselCapacity->getResult() as $row)
                {
                    if($row->Seat_Type  == 'Business Class'||$row->Seat_Type == 'Business Class w/o AC'){
                        $returnCapacityBC = $row->Capacity;
                    }
                    if($row->Seat_Type  == 'Premium Economy'||$row->Seat_Type == 'Premium Economy w/o AC'){
                        $returnCapacityPE = $row->Capacity;
                    }
                    if($row->Seat_Type  == 'Economy'){
                        $returnCapacityE = $row->Capacity;
                    }
                }
            }
            if($departureVessel==8)
            {
                echo '<option value="">Choose</option>';
                echo '<option '; if(($departureBC >= ($departureCapacityBC * 0.30)) || ($returnBC >= ($returnCapacityBC * 0.30))){ echo 'disabled'; } echo '>Business Class w/o AC'; if(($departureBC >= ($departureCapacityBC * 0.30)) || ($returnBC >= ($returnCapacityBC * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
                echo '<option '; if(($departurePE >= ($departureCapacityPE * 0.30)) || ($returnPE >= ($returnCapacityPE * 0.30))){ echo 'disabled'; } echo '>Premium Economy w/o AC'; if(($departurePE >= ($departureCapacityPE * 0.30)) || ($returnPE >= ($returnCapacityPE * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
                echo '<option '; if(($departureE >= ($departureCapacityE * 0.30)) || ($returnE >= ($returnCapacityE * 0.30))){ echo 'disabled'; } echo '>Economy'; if(($departureE >= ($departureCapacityE * 0.30)) || ($returnE >= ($returnCapacityE * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
            }
            else
            {
                echo '<option value="">Choose</option>';
                echo '<option '; if(($departureBC >= ($departureCapacityBC * 0.30)) || ($returnBC >= ($returnCapacityBC * 0.30))){ echo 'disabled'; } echo '>Business Class'; if(($departureBC >= ($departureCapacityBC * 0.30)) || ($returnBC >= ($returnCapacityBC * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
                echo '<option '; if(($departurePE >= ($departureCapacityPE * 0.30)) || ($returnPE >= ($returnCapacityPE * 0.30))){ echo 'disabled'; } echo '>Premium Economy'; if(($departurePE >= ($departureCapacityPE * 0.30)) || ($returnPE >= ($returnCapacityPE * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
                echo '<option '; if(($departureE >= ($departureCapacityE * 0.30)) || ($returnE >= ($returnCapacityE * 0.30))){ echo 'disabled'; } echo '>Economy'; if(($departureE >= ($departureCapacityE * 0.30)) || ($returnE >= ($returnCapacityE * 0.30))){ echo ' - seats fully booked'; } echo '</option>';
            }
        }  
        else{
            echo '<option value="">Choose</option>';
            if(((($departureScheduleID == 327 || $departureScheduleID == 329 || $departureScheduleID == 331 || $departureScheduleID == 333 || $departureScheduleID == 330 || 
            $departureScheduleID == 328 || $departureScheduleID == 332 || $departureScheduleID == 334 || $departureScheduleID == 281 || /*$departureScheduleID == 282
            ||*/ $departureScheduleID == 284) && $departureDate >= '2024-05-20' && $departureDate <= '2024-05-31') || 
            (($returnScheduleID == 327 || $returnScheduleID == 329 || $returnScheduleID == 331 || $returnScheduleID == 333 || $returnScheduleID == 330 ||
            $returnScheduleID == 328 || $returnScheduleID == 332 || $returnScheduleID == 334 || $returnScheduleID == 281 || /*$returnScheduleID == 282 
            ||*/ $returnScheduleID == 284) && $returnDate >= '2024-05-20' && $returnDate <= '2024-05-31') ||
            
            (($departureScheduleID == 331 || $departureScheduleID == 332 || $departureScheduleID == 333 || $departureScheduleID == 334 || $departureScheduleID == 327 || $departureScheduleID == 329 || $departureScheduleID == 330) && 
            $departureDate >= '2024-06-06' && $departureDate <= '2024-12-31') ||
            (($returnScheduleID == 331 || $returnScheduleID == 332 || $departureScheduleID == 333 || $departureScheduleID == 334 || $departureScheduleID == 327 || $departureScheduleID == 329 || $departureScheduleID == 330 ) && 
            $returnDate >= '2024-06-06' && $returnDate <= '2024-12-31')))
            {}
            else{
                
                if($departureVessel==8)
                {
                    echo '<option>Business Class w/o AC</option>';
                }
                else
                {
                    echo '<option>Business Class</option>';
                }
            }
            
            if(((($departureScheduleID == 330 || $departureScheduleID == 328 || $departureScheduleID == 332 || $departureScheduleID == 334 || $departureScheduleID == 281 || /*$departureScheduleID == 282 
            ||*/ $departureScheduleID == 284) 
            && $departureDate >= '2024-05-20' && $departureDate <= '2024-05-31') || 
            (($returnScheduleID == 330 || $returnScheduleID == 328 || $returnScheduleID == 332 || $returnScheduleID == 334 || $returnScheduleID == 281 || /*$returnScheduleID == 282 
            ||*/ $returnScheduleID == 284) 
            && $returnDate >= '2024-05-20' && $returnDate <= '2024-05-31') ||
            
            // else if((($departureScheduleID == 304 || $departureScheduleID == 306) && $departureDate >= '2024-06-05' && $departureDate <= '2024-06-05') ||
            // (($returnScheduleID == 304 || $returnScheduleID == 306)  && $returnDate >= '2024-06-05' && $returnDate <= '2024-06-05'))
            // {}
            
            
            (($departureScheduleID == 331 || $departureScheduleID == 332 || $departureScheduleID == 333 || $departureScheduleID == 334 || $departureScheduleID == 327 || $departureScheduleID == 329 || $departureScheduleID == 330) && 
            $departureDate >= '2024-06-06' && $departureDate <= '2024-12-31') ||
            (($returnScheduleID == 331 || $returnScheduleID == 332 || $departureScheduleID == 333 || $departureScheduleID == 334 || $departureScheduleID == 327 || $departureScheduleID == 329 || $departureScheduleID == 330 ) && 
            $returnDate >= '2024-06-06' && $returnDate <= '2024-12-31')))
            {}
            
            else{
                if($departureVessel==8)
                {
                    echo '<option>Premium Economy w/o AC</option>';
                }
                else
                {
                    echo '<option>Premium Economy</option>';
                }
            }
            echo '<option>Economy</option>';
        }
    }
}