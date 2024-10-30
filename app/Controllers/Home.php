<?php

namespace App\Controllers;
use App\Libraries\Hash;
require_once 'assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;
require_once('vendor/autoload.php');
class Home extends BaseController
{
    private $db;
    
    public function __construct()
    {
        $this->db = db_connect();
    }
    
    public function index()
    {
        return view('welcome_message');
    }
    
    public function Dashboard()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        //get the merchant info
        $id = session()->get('merchantID');
        $merchantModel = new \App\Models\merchantInfoModel();
        $merchant = $merchantModel->find($id);
        //get the expenses according to the date of merchant
        $builder = $this->db->table('tblcustomer_payment_merchant a');
        $builder->select('a.TrxnDate,SUM(a.TotalAmount)total');
        $builder->join('(Select BookingNumber,agentID from tblrecords_merchant GROUP BY BookingNumber)b','b.BookingNumber=a.BookingNumber','LEFT');
        $builder->WHERE('b.agentID',$id);
        $builder->WHERE('a.TrxnDate >=',$merchant['EffectiveDate']);
        $builder->WHERE('a.TrxnDate <=',$merchant['EndContract']);
        $builder->WHERE('a.Remarks','PAID');
        $builder->groupby('a.TrxnDate');
        $query = $builder->get()->getResult();
        //port
        $builder = $this->db->table('tblport');
        $builder->select('*');
        $port = $builder->get()->getResult();
        
        $data = [
            'title'=>'FerryLink Merchant Portal - Dashboard',
            'userInfo'=>$userInfo,
            'query'=>$query,
            'port'=>$port
        ];
        return view('dashboard', $data);
    }
    
    public function Payment($id = null)
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        //get all the records once confirmed
        $passengerModel = new \App\Models\passengerModel();
        $query = $passengerModel->WHERE('BookingNumber',$id)->findAll();
        //get the total amount
        $paymentModel = new \App\Models\paymentModel();
        $payment = $paymentModel->WHERE('BookingNumber',$id)->findAll();
        //cargo model
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select('a.plate_number,b.Name,c.Name as model');
        $builder->join('tblvehicles b','b.vID=a.vID','INNER');
        $builder->join('tblvehicle_model c','c.mID=a.mID','INNER');
        $builder->WHERE('a.BookingNumber',$id);
        $vehicle = $builder->get()->getResult();
        $data = [
            'title'=>'Payment',
            'userInfo'=>$userInfo,
            'passenger'=>$query,
            'payment'=>$payment,
            'code'=>$id,
            'vehicle'=>$vehicle
        ];
        return view('confirmation', $data);
    }
    
    public function Account()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        //get the merchant details
        $id = session()->get('merchantID');
        $merchantModel = new \App\Models\merchantInfoModel();
        $merchant = $merchantModel->find($id);
        $data = [
            'title'=>'Merchant Information',
            'userInfo'=>$userInfo,
            'merchant'=>$merchant
        ];
        return view('account', $data);
    }
    
    public function Book()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $merchantID = session()->get('merchantID');
        $userInfo = $userModel->find($loggedUserID);
        $merchantInfo = new \App\Models\merchantInfoModel();
        $merchant = $merchantInfo->WHERE('agentID',$merchantID)->first();
        $current_date = date('Y-m-d');
        if($current_date <= $merchant['EndContract'])
        {
            $data = [
                'title'=>'Book Now',
                'userInfo'=>$userInfo
            ];
            return view('book', $data);
        }
        else
        {
            session()->setFlashdata('fail','Your contract is expired. Please contact Sales Department');
            return redirect()->to('/dashboard')->withInput();
        }
    }
    
    public function AddPassenger()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        //merchant
        $id = session()->get('merchantID');
        $merchantModel = new \App\Models\merchantInfoModel();
        $merchant = $merchantModel->find($id);
        $data = [
            'title'=>'Add Passenger Details',
            'userInfo'=>$userInfo,
            'merchant'=>$merchant
        ];
        return view('add-passenger', $data);
    }
    
    public function Transaction()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        $merchant = session()->get('merchantID');
        //fetch all 
        $builder  = $this->db->table('tblcustomer_payment_merchant a');
        $builder->select('a.BookingNumber,FORMAT(a.TotalAmount,2)total,COUNT(b.recordID)pax,c.Route,b.TrxnDate,a.Remarks');
        $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
        $builder->join('tblschedule c','c.ID=b.ID','LEFT');
        $builder->WHERE('b.agentID',$merchant);
        $builder->groupby('a.BookingNumber');
        $query = $builder->get()->getResult();
        $data = [
            'title'=>'Transaction History',
            'userInfo'=>$userInfo,
            'passenger'=>$query
        ];
        return view('transaction', $data);
    }
    
    public function currentBalance()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        $merchant = session()->get('merchantID');
        //fetch all
        $builder  = $this->db->table('tblcustomer_payment_merchant a');
        $builder->select('a.BookingNumber,FORMAT(a.TotalAmount,2)total,c.Route,b.TrxnDate');
        $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
        $builder->join('tblschedule c','c.ID=b.ID','LEFT');
        $builder->WHERE('b.agentID',$merchant)->WHERE('a.Remarks','PAID');
        $builder->groupby('a.BookingNumber');
        $query = $builder->get()->getResult();
        $data = [
            'title'=>'Current Balance',
            'userInfo'=>$userInfo,
            'query'=>$query
        ];
        return view('current-balance', $data);
    }
    
    public function statementAccount()
    {
        $userModel = new \App\Models\merchantModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $userModel->find($loggedUserID);
        //merchant
        $id = session()->get('merchantID');
        $merchantModel = new \App\Models\merchantInfoModel();
        $merchant = $merchantModel->find($id);
        $data = [
            'title'=>'Statement of Account',
            'userInfo'=>$userInfo,
            'merchant'=>$merchant
        ];
        return view('statement-account', $data);
    }
    
    public function updateInformation()
    {
        $merchantInfo = new \App\Models\merchantInfoModel();
        $user = $this->request->getPost('merchantID');
        $merchant = $this->request->getPost('merchantName');
        $address = $this->request->getPost('address');
        $contactNo = $this->request->getPost('contactNumber');
        $email = $this->request->getPost('email');
        $validation = $this->validate([
            'merchantID'=>'required',
            'merchantName'=>'required',
            'address'=>'required',
            'contactNumber'=>'required',
            'email'=>'required'
            ]);
        if(!$validation)
        {
            session()->setFlashdata('failed','Invalid! Please fill in the form');
            return redirect()->to('/account-settings')->withInput();
        }
        else
        {
            $values = [
                "Agent_Name"=>$merchant,
                "Number"=>$contactNo,
                "EmailAddress"=>$email,
                "Address"=>$address,
            ];
            $merchantInfo->update($user,$values);
            session()->setFlashdata('successful','Great! Successfully updated');
            return redirect()->to('/account-settings')->withInput();
        }
    }
    
    public function changePassword()
    {
        $merchantModel = new \App\Models\merchantModel();
        $user = $this->request->getPost('merchantID');
        $current_pass = $this->request->getPost('current_password');
        $new_pass = $this->request->getPost('new_password');
        $confirm_pass = $this->request->getPost('confirm_password');
        $user_info = $merchantModel->where('merchantID', $user)->WHERE('Status',1)->first();
        $check_password = Hash::check($current_pass, $user_info['Password']);
        if(!$check_password)
        {
            session()->setFlashdata('fail','Invalid! Incorrect Password');
            return redirect()->to('/account-settings')->withInput();
        }
        else
        {
            if($new_pass==$confirm_pass)
            {
                $values = ['Password'=>Hash::make($new_pass),];
                $merchantModel->update($user,$values);
                session()->setFlashdata('success','Great! Successfully changed');
                return redirect()->to('/account-settings')->withInput();
            }
            else
            {
                session()->setFlashdata('fail','Invalid! Password mismatched');
                return redirect()->to('/account-settings')->withInput();
            }
        }
    }
    
    public function Origin()
    {
        $builder = $this->db->table("tblport");
        $builder-> select('portID,PortName');
        $data = $builder->get();
        foreach ($data->getResult() as $row) 
        {
            echo '<option>';
            echo $row->PortName;
            echo '</option>';
        }
    }
    
    public function Destination()
    {
        $from  = $this->request->getGet('origin');
        $builder = $this->db->table('tblroute');
        $builder->select('Arrival');
        $builder->where('Departure',$from);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            echo "<option>".$row->Arrival."</option>";
        }
    }
    
    public function availableSeats()
    {
        $val = $this->request->getGet('value');
        $date = $this->request->getGet('date');
        $vessel=0;$business;$premium;$econo;$premium_without_ac;$business_without_ac;
        //get the vessel
        $builder = $this->db->table('tblschedule');
        $builder->select('vesselID');
        $builder->WHERE('ID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $vessel = $row->vesselID;
        }
        //get the capacity
        $builder = $this->db->table('tblcapacity');
        $builder->select('SUM(CASE WHEN Seat_Type="Business Class" THEN Capacity Else 0 END)Bus,
        SUM(CASE WHEN Seat_Type="Premium Economy" THEN Capacity Else 0 END)Prem,
        SUM(CASE WHEN Seat_Type="Economy" THEN Capacity Else 0 END)Econo');
        $builder->WHERE('vesselID',$vessel);
        $builder->groupby('vesselID');
        $datas = $builder->get();
        foreach($datas->getResult() as $row)
        {
            $business = $row->Bus;
            $premium = $row->Prem;
            $econo = $row->Econo;
        }
        $output="";
        //get the reserved volume
        $builder = $this->db->table('accommodation_capacity_v2');
        $builder->select('COUNT(SeatNumber)total');
        $builder->WHERE('Seat_Type','Business Class')->WHERE('ID',$val)->WHERE('TrxnDate',$date)->WHERE('Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $output.="<tr><td>Business Class</td><td>".$row->total."/".$business."</td><tr/>"; 
        }
        //get the reserved volume
        $builder = $this->db->table('accommodation_capacity_v2');
        $builder->select('COUNT(SeatNumber)total');
        $builder->WHERE('Seat_Type','Premium Economy')->WHERE('ID',$val)->WHERE('TrxnDate',$date)->WHERE('Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $output.="<tr><td>Premium Economy</td><td>".$row->total."/".$premium."</td><tr/>"; 
        }
        //get the reserved volume
        $builder = $this->db->table('accommodation_capacity_v2');
        $builder->select('COUNT(SeatNumber)total');
        $builder->WHERE('Seat_Type','Economy')->WHERE('ID',$val)->WHERE('TrxnDate',$date)->WHERE('Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $output.="<tr><td>Economy</td><td>".$row->total."/".$econo."</td><tr/>"; 
        }
        echo $output;
    }
    
    public function Departure()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblschedule');
        $builder->select('TimeDeparture,TimeArrival');
        $builder->WHERE('ID',$val);
        $data = $builder->get();
        $info;
        if($row = $data->getRow())
        {
            $info = array("departure"=>date('h:i:s a', strtotime($row->TimeDeparture)),"arrival"=>date('h:i:s a', strtotime($row->TimeArrival)));
        }
        echo json_encode($info);
    }
    
    public function fetchRate()
    {
        $seat = $this->request->getGet('accommodation');
        $id = $this->request->getGet('schedule');
        $port;
        $builder = $this->db->table('tblschedule');
        $builder->select('portID');
        $builder->WHERE('ID',$id);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $port = $row->portID;
        }
        //get the price
        $builder = $this->db->table('tblprice');
        $builder->select('FORMAT(Amount,2)Amount');
        $builder->WHERE('Seat_Type',$seat);
        $builder->WHERE('portID',$port);
        $data = $builder->get();
        if($rows = $data->getRow())
        {
            echo $rows->Amount;
        }
    }
    
    public function Schedules()
    {
        date_default_timezone_set('Asia/Manila');
        $origin  = $this->request->getGet('origin');
        $destination = $this->request->getGet('destination');
        $date = $this->request->getGet('departureDate');
        $time = date("h:i:s");
        $currentdate = date('Y-m-d');
        //get the code
        $route;
        $builder = $this->db->table('tblroute');
        $builder->select('Code');
        $builder->WHERE('Departure',$origin);
        $builder->WHERE('Arrival',$destination);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $route = $row->Code;   
        }
        //get the schedules
        if($currentdate==$date)
        {
            $builder = $this->db->table('tblschedule');
            $builder->select('portID,ID,TimeDeparture,TimeArrival,Remarks');
            $builder->WHERE('Route',$route)->WHERE('FromDate <=',$date)->WHERE('ToDate >=',$date)->WHERE('Status',1)->WHERE('isOnline',1);
            $builder->WHERE('TimeDeparture >',$time);
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                $ts1 = strtotime($row->TimeDeparture);
                $ts2 = strtotime($row->TimeArrival);     
                $seconds_diff = $ts2 - $ts1;                            
                $time = ($seconds_diff/3600); 
                ?>
                <div class="col-12">
                    <div class="card sched">
                        <div class="card-body">
                            <h6 class="badge bg-primary"><?php echo $row->Remarks ?></h6>
                            <div class="row g-3">
                                <div class="col-lg-10">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <center>
                                                <h3><?php echo date('h:i:s a', strtotime($row->TimeDeparture)) ?></h3>
                                                <p><?php echo $origin ?></p>
                                            </center>
                                        </div>
                                        <div class="col-lg-4">
                                            <center>
                                                <span class="mdi mdi-ferry"></span>
                                                <span>---------------------></span>
                                                <span class="mdi mdi-map-marker-radius"></span>
                                                <h6><?php echo $time ?> Hours</h6>
                                                <p>Travel Time</p>
                                            </center>
                                        </div>
                                        <div class="col-lg-4">
                                            <center>
                                                <h3><?php echo date('h:i:s a', strtotime($row->TimeArrival)) ?></h3>
                                                <p><?php echo $destination ?></p>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" id="port" value="<?php echo urlencode(base64_encode($row->portID)) ?>"/>
                                    <input type="hidden" id="date" value="<?php echo $date ?>"/>
                                    <input type="hidden" id="origin" value="<?php echo $origin ?>"/>
                                    <input type="hidden" id="destination" value="<?php echo $destination ?>"/>
                                    <div class="radio-buttons">
                                        <label class="custom-radio">
                                          <input type="radio" name="book" id="schedule" class="choose" value="<?php echo $row->ID ?>">
                                          <span class="radio-btn btn-primary" id="setLabel">
                                              Choose
                                          </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        else
        {
            $builder = $this->db->table('tblschedule');
            $builder->select('portID,ID,TimeDeparture,TimeArrival,Remarks');
            $builder->WHERE('Route',$route)->WHERE('FromDate <=',$date)->WHERE('ToDate >=',$date)->WHERE('Status',1)->WHERE('isOnline',1);
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                $ts1 = strtotime($row->TimeDeparture);
                $ts2 = strtotime($row->TimeArrival);     
                $seconds_diff = $ts2 - $ts1;                            
                $time = ($seconds_diff/3600);
                ?>
                <div class="col-12">
                    <div class="card sched">
                        <div class="card-body">
                            <h6 class="badge bg-primary"><?php echo $row->Remarks ?></h6>
                            <div class="row g-3">
                                <div class="col-lg-10">
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <center>
                                                <h3><?php echo date('h:i:s a', strtotime($row->TimeDeparture)) ?></h3>
                                                <p><?php echo $origin ?></p>
                                            </center>
                                        </div>
                                        <div class="col-lg-4">
                                            <center>
                                                <span class="mdi mdi-ferry"></span>
                                                <span>---------------------></span>
                                                <span class="mdi mdi-map-marker-radius"></span>
                                                <h6><?php echo $time ?> Hours</h6>
                                                <p>Travel Time</p>
                                            </center>
                                        </div>
                                        <div class="col-lg-4">
                                            <center>
                                                <h3><?php echo date('h:i:s a', strtotime($row->TimeArrival)) ?></h3>
                                                <p><?php echo $destination ?></p>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" id="port" value="<?php echo urlencode(base64_encode($row->portID)) ?>"/>
                                    <input type="hidden" id="date" value="<?php echo $date ?>"/>
                                    <input type="hidden" id="origin" value="<?php echo $origin ?>"/>
                                    <input type="hidden" id="destination" value="<?php echo $destination ?>"/>
                                    <div class="radio-buttons">
                                        <label class="custom-radio">
                                          <input type="radio" name="book" id="schedule" class="choose" value="<?php echo $row->ID ?>">
                                          <span class="radio-btn btn-primary" id="setLabel">
                                              Choose
                                          </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    
    public function Authenticate()
    {
        $merchantModel = new \App\Models\merchantModel();
        $validation = $this->validate([
            'email'=>[
                'rules'=>'is_not_unique[tblmerchant.Email]',
                'errors'=>[
                    'is_not_unique'=>'This account is not registered!'
                ]
            ],
            'password'=>[
                'rules'=>'min_length[8]|max_length[12]',
                'errors'=>
                [
                    'min_length'=>'Password must have atleast 8 characters in length',
                    'max_length'=>'Password must have atleast 16 characters in length',
                ]
            ]
        ]);
        if(!$validation)
        {
            return view('/welcome_message',['validation'=>$this->validator]);
        }
        else
        {
            $username = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $date = date('Y-m-d');
            $user_info = $merchantModel->where('Email', $username)->WHERE('Status',1)->first();
            if(empty($user_info['Password']))
            {
                session()->setFlashdata('fail','Invalid Email or Password!');
                return redirect()->to('/')->withInput();
            }
            else
            {
                $check_password = Hash::check($password, $user_info['Password']);
                if(!$check_password || empty($check_password))
                {
                    session()->setFlashdata('fail','Invalid Email or Password!');
                    return redirect()->to('/')->withInput();
                }
                else
                {
                    $user_id = $user_info['merchantID'];
                    session()->set('loggedUser', $user_id);
                    session()->set('merchantName',$user_info['merchantName']);
                    session()->set('merchantID',$user_info['agentID']);
                    session()->set('email',$user_info['Email']);
                    return redirect()->to('/dashboard');
                }
            }
        }
    }
    
    function logout()
    {
        if(session()->has('loggedUser'))
        {
            session()->remove('loggedUser');
            return redirect()->to('/?access=out')->with('fail', 'You are logged out!');
        }
    }
    
    public function getCSPP(){
        $jsonPayload = $this->request->getBody();
        $header = $this->request->getHeaders();
        $data = json_decode($jsonPayload, true);

        // Process the webhook event data ($data) here
        $log = $this->db->table('paymongo_webhook_logs');
        $record = [
            'body'    => $jsonPayload,
            'header' => json_encode($header)
        ];
        $log->insert($record);
        
        $booking_number = $data['data']['attributes']['data']['attributes']['description'];
        $payment_method  = '';
        $payment_intent  = $data['data']['attributes']['data']['attributes']['payment_intent']['id'];
        $status = 1;
        $payments = $data['data']['attributes']['data']['attributes']['payments'];
        $searchValue = 'paid';
        $dummy = '';
        
        $filtered = array_filter($payments, function ($payment) use ($searchValue) {
            $jsonPayload = $this->request->getBody();
            $header = $this->request->getHeaders();
            $data = json_decode($jsonPayload, true);
            $booking_number = $data['data']['attributes']['data']['attributes']['description'];
            $paymentModel = new \App\Models\onlinePaymentModel();
            $pending_payment = $paymentModel->where('BookingNumber', $booking_number)->first();
            
            return $payment['attributes']['status'] === $searchValue && substr_replace($payment['attributes']['amount'], '.', -2, 0) >= $pending_payment['Total_Payment'];
        });
        
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.paymongo.com/v1/payment_intents/' . $payment_intent, [
          'headers' => [
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfbGl2ZV9pTTlpM3Y0Nlp0UnNqSEpVUnhKWUVrMm06',
          ],
        ]);
        
        $payment_verification_status = $response->getStatusCode();
        
        if (!empty($filtered) && $payment_verification_status == 200) {
            $foundPayment = reset($filtered);
            $paymentModel = new \App\Models\onlinePaymentModel();
            $pending_payment = $paymentModel->where('BookingNumber', $booking_number)->first();
            $payment_method = $foundPayment['attributes']['source']['type'];
            
            $paymentModel
                    ->whereIn('BookingNumber', [$booking_number])
                    ->set(['Status' => $status, 'PaymentMethod' => $payment_method])
                    ->update();
                
                //send email with qrcode
                
                //get email credentials
                $builder = $this->db->table('tblpayment a');
                $builder->select('b.Fullname, b.EmailAddress, a.BookingNumber');
                $builder->join('tblcustomer b','b.customerID=a.customerID','LEFT');
                $builder->WHERE('a.BookingNumber',$booking_number);
                $data = $builder->get();
                $row = $data->getRow();
                
                //create e-ticket attachment
                $builder = $this->db->table('tbltransaction a');
                $builder->select('a.*,b.TimeDeparture,b.Route,c.Name, FORMAT(d.Total_Payment,2) total, e.Name vessel');
                $builder->join('tblschedule b','b.ID=a.ID','LEFT');
                $builder->join('tblvehicles c','c.vID=a.vID','LEFT');
                $builder->join('tblpayment d','d.BookingNumber=a.BookingNumber', 'LEFT');
                $builder->join('tblvessels e','e.vesselID=b.vesselID', 'LEFT');
                $builder->WHERE('a.BookingNumber',$booking_number);
                $data = $builder->get();
                $route = $data->getResult()[0]->Route;
                $departure_date = $data->getResult()[0]->TrxnDate;
                $departure_time = $data->getResult()[0]->TimeDeparture;
                $amount = $data->getResult()[0]->total;
                
                $email = \Config\Services::email();
                $email->setTo($row->EmailAddress, $row->Fullname);
                $email->setFrom("reservation.apfc@fastcat.com.ph","FastCat Customer Care");
                $email->setCC("reservation.apfc@fastcat.com.ph","FastCat Customer Care");
                $template = "
                <p>Dear " . $row->Fullname . ",</p>
                <p>We are delighted to inform you that your booking with FastCat has been approved! Please see your booking details below: </p>
                <p>Booking Number: " . $booking_number . "</p>
                <p>Route: " . $route . "</p>
                <p>Departure Date: " . $departure_date . "</p>
                <p>Departure Time: " . $departure_time . "</p>
                <p>Amount: " . $amount. "</p>
                <p>Passengers: </p>
                <ol>";
                
                foreach($data->getResult() as $row)
                {  
                    $template .= "
                        <li>Name: ". $row->Fullname . ", Seat no.: ". $row->SeatNumber . ", Accomodation: " . $row->Seat_Type . ", Vehicle Type: " . $row->Name . ", Plate Number: " . $row->plate_number . "</li>
                    ";
                }
                
                $template .= "
                </ol>
                <p>To access your e-ticket, please see the attached file or follow these simple steps:</p>
                <ol>
                <li>Visit our website at <a href='https://fastcat-book.com/'>https://fastcat-book.com/</a>.</li>
                <li>Log in to your account using your registered email and password.</li>
                <li>Navigate to the \"Manage Booking\" section.</li>
                <li>Locate your booking.</li>
                <li>Click on the \"Download e-ticket\" button to obtain your electronic ticket (e-ticket).</li>
                </ol>
                <p>Remember to present your printed tickets at the FastCat ticketing booth 2 (two) hours before the scheduled departure of your trip. Thank You!</p>
                <p>Should you have any concerns or require further assistance, please feel free to contact our team at reservation.apfc@fastcat.com.ph. We're here to help!</p>
                <p>Thank you for choosing FastCat. We look forward to serving you on your upcoming journey. Have a fantastic travel experience!</p>
                <p>Best regards,</p>
                <p>FastCat Team
                ";
                $subject = "Your Booking with FastCat has been Approved!";
                $email->setSubject($subject);
                $email->setMessage($template);
                
                $template = '';
                
                foreach($data->getResult() as $row)
                {        
                $code = $row->TrxnDate.",".$row->TimeDeparture.",".$row->portID.",".$row->ID.",".$row->BookingNumber.",".$row->Fullname.",".$row->Seat_Type.",".$row->SeatNumber.",".$row->TransactionType;
                $template .= "
                    <head>
                        <style>a
                            p{margin-bottom:5px;}
                            .title{
                                font-size:16px;
                            }
                        </style>
                    </head>
                    <body>
                    <div style='font-size:14px;font-weight:500;text-align:center;padding:30px;' border='0'>
                    <span><img src='data:image/png;base64,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' height='50'></h3></center></td></tr><span>
                    <p class='title'>Archipelago Philippine Ferries Corporation</p>
                    <p>Ticket No : ".$row->BookingNumber."</p>
                    <span><img src='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=".$code."' id='qrcode'/><span></br>
                    <p class='title'>Passenger Details</span></p>
                    <p>Route : ".$row->Route."</p>
                    <p>Vessel : ".$row->vessel."</p>
                    <p>Departure Date : ".$row->TrxnDate."</p>
                    <p>Departure Time : ".$row->TimeDeparture."</p>
                    <p>Fullname : ".$row->Fullname."</p>
                    <p>Accommodation : ".$row->Seat_Type."</p>
                    <p>Seat Number : ".$row->SeatNumber."</p>
                    <p>Amount : ".$row->total."</p>
                    <p>Vehicle Type : ".$row->Name."</p>
                    <p>Plate Number : ".$row->plate_number."</p>
                    </div></body>";
                    
                }
                
                // instantiate and use the dompdf class
                $options = new Options();
                $options->set([
                    'isRemoteEnabled' => true
                ]);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($template);
                $dompdf->setPaper(array(0, 0, 260, 680), 'portrait');
                $dompdf->render();
                $ticket = $dompdf->output();
                
                $email->attach($ticket, 'attachment', $booking_number."-e-ticket".".pdf", 'application/pdf');
                
                $email->send();
                
                return $this->response->setJSON(['status' => 'success']); 
        }
        else{
            return $this->response->setJSON(['status' => 'fail']); 
        }
        
    }
    
        public function getPort()
        {
            $val = $this->request->getGet('port');
            $builder = $this->db->table('tblport');
            $builder->select('PortName');
            $builder->WHERE('portID',$val);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                echo $row->PortName;
            }
        }
        
        public function getSchedule()
        {
            $id = $this->request->getGet('value'); 
            $builder = $this->db->table('tblschedule');
            $builder->select('TimeDeparture');
            $builder->WHERE('ID',$id)->WHERE('Status',1);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                echo date('h:i:s a', strtotime($row->TimeDeparture));
            }
    }
}
