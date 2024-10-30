<?php

namespace App\Controllers;
require_once 'assets/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
require_once('vendor/autoload.php');

class TransactionController extends BaseController
{
    private $db;
    
    public function __construct()
    {
        $this->db = db_connect();
    }
    
    public function eticket($bookingNumber)
    {
        $total=0.00;$plate_number="";
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select('a.TrxnDate,a.BookingNumber,b.TimeDeparture,FORMAT(c.TotalAmount,2)total,
        e.Agent_Name,b.Route,a.Accommodation,e.EmailAddress,e.Number,f.Departure,f.Arrival');
        $builder->join('tblschedule b','b.ID=a.ID','LEFT');
        $builder->join('tblcustomer_payment_merchant c','c.BookingNumber=a.BookingNumber','LEFT');
        $builder->join('tblagent e','e.agentID=a.agentID', 'LEFT');
        $builder->join('tblroute f','f.Code=b.Route','LEFT');
        $builder->WHERE('a.BookingNumber',$bookingNumber);
        $builder->groupby('a.BookingNumber');
        $data = $builder->get();
        
        $template = '';
        
        if($row = $data->getRow())
        {        
            $total = $row->total;
            $template .= "
                <body>
                <div>
                <table width='100%'>
                <tbody>
                    <tr><td><center>
                    <img src='data:image/png;base64,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' width='50'></center></td>
                    <td><h3><center>Itinerary Receipt</center></h3></td>
                    </tr>
                </tbody>
                </table>
                <hr style='border: 5px solid #EC5800;'/>
                <table width='100%'>
                <tr>
                    <td><label><b>Merchant/Travel Agency<b></label>: ".$row->Agent_Name."<br/><label><b>Email</b></label>: ".$row->EmailAddress."<br/><label><b>Tel/Cell No</b></label>: ".$row->Number."</td>
                    <td colspan='2'><center><label>Reservation Slip No</label><br/><label style='font-size:25px;'>".$row->BookingNumber."</label></center></td>
                </tr>
                </table>
                <hr style='border: 2px dotted #EC5800;'/>
                <label><b>Travel Details</b></label><br/><br/>
                <table width='100%'>
                <tr>
                    <td><label><b>Route</b></label>: ".$row->Route."</td>
                    <td><label><b>Departure</b></label>: ".$row->Departure."</td>
                    <td><label><b>Arrival</b></label>: ".$row->Arrival."</td>
                </tr>
                <tr>
                    <td><label><b>Departure Date</b></label>: ".$row->TrxnDate."</td>
                    <td><label><b>Departure Time</b></label>: ".date('h:i:s a',strtotime($row->TimeDeparture))."</td>
                </tr>
                <tr>
                    <td colspan='2'><label><b>Accommodation</b></label>: ".$row->Accommodation."</td>
                </tr>
                </table>
                <hr style='border: 2px dotted #EC5800;'/>";
        }
        $template.="<label><b>Passenger Details</b></label><br/><br/>
                <table width='100%' style='border:2px solid #EC5800;'>
                <thead>
                    <th style='text-align:left;'>Passenger Type</th>
                    <th style='text-align:left;'>Complete Name</th>
                    <th style='text-align:left;'>Seat No</th>
                </thead>
                <tbody>";
        //passenger details
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('Fullname,Passenger_Type,SeatNumber,plate_number');
        $builder->WHERE('BookingNumber',$bookingNumber);
        $builder->groupby('recordID');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            $plate_number = $row->plate_number;
                $template.="<tr>
                        <td>".$row->Passenger_Type."</td>
                        <td>".$row->Fullname."</td>
                        <td>".$row->SeatNumber."</td>
                    </tr>";
        }
        $template.="</tbody>
                </table><hr style='border: 2px dotted #EC5800;'/>";
        $template.="<label><b>Vehicle Details</b></label><br/><br/>
        <table width='100%' style='border:2px solid #EC5800;'>
                <thead>
                    <th style='text-align:left;'>Vehicle Type</th>
                    <th style='text-align:left;'>Model</th>
                    <th style='text-align:left;'>Plate No</th>
                </thead>
                <tbody>";
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select('a.plate_number,IFNULL(b.Name,"N/A")Name,IFNULL(c.Name,"N/A") as model');
        $builder->join('tblvehicles b','b.vID=a.vID','LEFT');
        $builder->join('tblvehicle_model c','c.mID=a.mID','LEFT');
        $builder->WHERE('a.BookingNumber',$bookingNumber)->WHERE('a.Passenger_Type','DRIVER');
        $builder->groupby('a.recordID');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            $template.="<tr>
                    <td>".$row->Name."</td>
                    <td>".$row->model."</td>
                    <td>".$row->plate_number."</td>
                </tr>";
        }
        $template.="</tbody></table>";
        $template.="<label><b>Payment Breakdown</b></label><br/><br/>
                    <table width='100%' style='border:2px solid #EC5800;'>
                    <thead>
                        <th style='text-align:left;'>Description</th>
                        <th style='text-align:right;'>Amount</th>
                    </thead>
                    <tbody>";
        $total_amount = 0;
        $code = $this->request->getGet('code');
        $user = $this->request->getGet('user');
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('IFNULL(SUM(Amount),0)total');
        $builder->WHERE('BookingNumber',$bookingNumber);
        
        
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $template.='
            <tr>
                <td>Base Fare</td><td style="text-align:right;">' . number_format($row->total,2) . '</td>
            </tr>
            ';
            $total_amount += $row->total;
        }
        //cargo
        
        
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(Rate),0)rate');
        $builder->WHERE('BookingNumber',$bookingNumber);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            $template.=' 
            <tr>
                <td>Total Cargo Rate</td><td style="text-align:right;">' . number_format($row->rate,2) . '</td>
            </tr>
            ';
            $total_amount += 0;
        }
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(Discount),0)discount');
        $builder->WHERE('BookingNumber',$bookingNumber);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            $template.='
            <tr>
                <td>Discount</td><td style="text-align:right;">' . number_format($row->discount,2) . '</td>
            </tr>
            ';
            
            $total_amount += $row->discount;
        }
        $builder = $this->db->table('tblcargo_merchant');
        $builder->select('IFNULL(SUM(NetAmount),0)price');
        $builder->WHERE('BookingNumber',$bookingNumber);
        $cargos = $builder->get();
        if($row = $cargos->getRow())
        {
            $template.='    
            <tr>
                <td>Sub-Total Cargo Amount</td><td style="text-align:right;">' . number_format($row->price,2) . '</td>
            </tr>
            ';
            $total_amount += $row->price;
        }
        //get the admin fee
        $admin_fee;
        $builder = $this->db->table('tblconvenience_merchant');
        $builder->select('SUM(Amount)admin_fee');
        $builder->WHERE('BookingNumber',$bookingNumber);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $template.='     
            <tr>
                <td>Admin Fee</td><td style="text-align:right;">' . number_format($row->admin_fee,2) . '</td>
            </tr>
            ';
            $total_amount += $row->admin_fee;
        }
        
        $template.="</tbody></table><br/>";
        $template.="<table width='100%'>
                        <tr>
                            <td><h2>Total</h2></td>
                            <td style='text-align:right;font-size:25px;'>PhP ". number_format($total_amount,2) ."</td>
                        </tr>
                        </table>";
        $template.="</div>
                    <div>
                        <center><font size='10px'>Kindly present this trip ticket to the Port Cashier/Teller before departure for confirmation of your reservation</font></center>
                    </div>
                </body>";

        
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select('a.*,b.TimeDeparture,b.Route,c.Name, FORMAT(a.Amount, 2) AS BaseFare , FORMAT(d.TotalAmount,2) as overall, FORMAT(IFNULL(g.NetAmount, 0),2) AS NetAmount, e.Name vessel ,FORMAT(f.Amount, 2) AS ConvFee');
        $builder->join('tblschedule b','b.ID=a.ID','LEFT');
        $builder->join('tblvehicles c','c.vID=a.vID','LEFT');
        $builder->join('tblcustomer_payment_merchant d','d.BookingNumber=a.BookingNumber', 'LEFT');
        $builder->join('tblvessels e','e.vesselID=b.vesselID', 'LEFT');
        $builder->join('tblconvenience_merchant f','f.BookingNumber=a.BookingNumber','LEFT');
        $builder->join('tblcargo_merchant g','g.BookingNumber=a.BookingNumber','LEFT');
        $builder->WHERE('a.BookingNumber',$bookingNumber);
        $builder->WHERE('a.Status', 1 );
        $builder->groupby('a.TrxnDate,a.recordID');
        $data2 = $builder->get();
        
        foreach($data2->getResult() as $row2) {        
        $code2 = urlencode(base64_encode($row2->BookingNumber.",".$row2->recordID));
        $passenger_type2 = '';
        if($row2->Passenger_Type == 'FULL'){
            $passenger_type2 = 'FULL';
        }
        else if($row2->Passenger_Type == 'DRIVER'){
         $passenger_type2 = 'DRIVER';
        }
        else if($row2->Passenger_Type == 'PWD'){
            $passenger_type2 = 'PWD';
        }
        else if($row2->Passenger_Type == 'SC'){
         $passenger_type2 = 'SC';
        }
        else if($row2->Passenger_Type == 'STU'){
            $passenger_type2 = 'STU';
        }
        else if($row2->Passenger_Type == 'HALF'){
            $passenger_type2 = 'HALF';
        }
        else if($row2->Passenger_Type == 'FOC'){
         $passenger_type2 = 'FOC';
        }
        else{
            $passenger_type2 = 'FULL';
        }
        $template .= "
            
            <body>
                <div>
                    <table width='100%'>
                        <tbody>
                            <tr><td><center>
                            <img src='data:image/png;base64,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' width='100' style='display:block;'></center></td>
                            <td><h3 style='font-size: 28px; font-family: Arial, sans-serif;'><center>ONBOARDING PASS</center></h3></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr style='border: 3px solid #EC5800;'/>
                   <table width='100%'>
                       <td>
                            <label>
                                <p class='title' style='font-family: Arial, sans-serif; font-size:20px; font-weight:bold;'>Archipelago Philippine Ferries Corporation</p>
                            </label>
                        </td>
                        <br/>
                        <td colspan='2'><center><label style='font-size:15px; font-family: Arial, sans-serif;'>Ticket No:</label><br/>
                            <label style='font-size:30px; font-family: Arial, sans-serif; font-size:20px;'>".$row2->BookingNumber."</label></center>
                        </td>
                    </table>
                    
                    <table width='100%' style='border-collapse: collapse;'>
                        <tr>
                            <td style='vertical-align: top; width: 33.33%;'>
                                <hr style='border: 2px dotted #EC5800;'/>
                                <table width='100%'>
                                    <tr>
                                        <td style='font-family: Arial, sans-serif;'><b>Passenger Details</b></td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Arial, sans-serif;'>Fullname :".$row2->Fullname."</td>
                                    </tr>
                                    <tr>
                                         <td style=' font-family: Arial, sans-serif;'>Vehicle Type : ".$row2->Name."</td>
                                    </tr>
                                </table>
                            </td>
                            <td style='vertical-align: top; width: 33.33%;'>
                            <hr style='border: 2px dotted #EC5800;'/>
                                <table width='100%'>
                                    <tr>
                                        <td style='font-family: Arial, sans-serif; color:#FFFFFF'><b>Passenger Details</b></td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Arial, sans-serif;'>Type : ".$passenger_type2."</td>
                                    </tr>
                                    <tr>
                                         <td style=' font-family: Arial, sans-serif;'> Plate Number : ".$row2->plate_number."</td>
                                    </tr>
                                </table>
                            </td>
                            <td style='vertical-align: top; width: 33.33%;'>
                                <hr style='border: 2px dotted #EC5800;'/>
                                <table width='100%'>
                                    <tr>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style='vertical-align: top; width: 40.00%;'>
                                <hr style='border: 2px dotted #EC5800;'/>
                                <table width='100%'>
                                <tr>
                                <td style='font-family: Arial, sans-serif;'><b>Travel Details</b></td>
                                </tr>
                               <tr>
                            <td style='font-family: Arial, sans-serif;'>Route : ".$row2->Route."</td>
                        </tr>
                        <tr>
                            <td style='font-family: Arial, sans-serif;'>Vessel : ".$row2->vessel."</td>
                        </tr>
                        <tr>
                            <td style='font-family: Arial, sans-serif;'>Departure Date : ".$row2->TrxnDate."</td>
                        </tr>
                        <tr>
                            <td style='font-family: Arial, sans-serif;'>Departure Time :". date("g:i A", strtotime($row2->TimeDeparture))."</td>
                        </tr>
                         <tr>
                            <td style='font-family: Arial, sans-serif;'>Accommodation : ".$row2->Accommodation."</td>
                        </tr>
                         <tr>
                            <td style='font-family: Arial, sans-serif;'>Seat Number : ".$row2->SeatNumber."</td>
                        </tr>
                    </table>
                </td>
                <td style='vertical-align: top; width: 30.00%;'>
                    <hr style='border: 2px dotted #EC5800;'/>
                    <table width='100%'>
                        <tr>
                            <td style='font-family: Arial, sans-serif;'><b>Total Fare</b></td>
                        </tr>
                        <tr>
                            <td style='font-family: Arial, sans-serif;'>
                                Passenger Rate : ".$row2->BaseFare."
                                <br> Vehicle Amount : ".$row2->NetAmount."
                                <br> Admin fee : ".$row2->ConvFee." 
                                <br>Total Amount : ".$row2->overall." 
                            </td>
                        </tr>
                    </table>
                </td>
                <td style='vertical-align: top; width: 30.00%;'>
                    <hr style='border: 2px dotted #EC5800;'/>
                    <table width='100%'>
                        <tr>
                            <td style='text-align: right;'>
                                <img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$code2."' id='qrcode' width='180' height='180'/>
                            </td>
                        </tr>
                    </table>
                    <hr style='border: 3px solid #EC5800;'/>
                </td>
            </tr>
            </table>
        </div>
        </body>";
        }


        // instantiate and use the dompdf class
        $options = new Options();
        $options->set([
            'isRemoteEnabled' => true
        ]);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($template);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream($bookingNumber."-reservation-slip".".pdf");
        exit();
    }
    
    public function download($bookingNumber)
    {
        $builder = $this->db->table('tblrecords_merchant a');
        $builder->select('a.TrxnDate,a.plate_number,a.BookingNumber,b.TimeDeparture,IFNULL(c.Name,"N/A")Name, FORMAT(d.TotalAmount,2) total, e.Agent_Name,b.Route');
        $builder->join('tblschedule b','b.ID=a.ID','LEFT');
        $builder->join('tblvehicles c','c.vID=a.vID','LEFT');
        $builder->join('tblcustomer_payment_merchant d','d.BookingNumber=a.BookingNumber', 'LEFT');
        $builder->join('tblagent e','e.agentID=a.agentID', 'LEFT');
        $builder->WHERE('a.BookingNumber',$bookingNumber);
        $builder->groupby('a.BookingNumber');
        $data = $builder->get();
        
        $template = '';
        
        foreach($data->getResult() as $row)
        {        
            $plate_num = "";
            if(empty($row->plate_number)){$plate_num= "N/A";}else{$plate_num= $row->plate_number;}
        $template .= "
            <body>
            <div>
            <table width='100%'>
            <tbody>
                <tr><td><center><img src='data:image/png;base64,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' width='50'></center></td>
                <td><h3><center>TRIP TICKET</center></h3></td>
                </tr>
            </tbody>
            </table>
            <table width='100%'>
                <tr><td colspan='2'><center><label style='font-size:8px;'>TICKET NO</label><br/>".$row->BookingNumber."</center></td></tr>
                <tr><td colspan='2'><center><label style='font-size:8px;'>MERCHANT</label><br/>".$row->Agent_Name."</center></td></tr>
                <tr>
                    <td><center><label style='font-size:8px;'>DATE</label><br/>".$row->TrxnDate."</center></td>
                    <td><center><label style='font-size:8px;'>DEPARTURE</label><br/>".date('h:i:s a',strtotime($row->TimeDeparture))."</center></td>
                </tr>
                <tr>
                    <td><center><label style='font-size:8px;'>VEHICLE TYPE</label><br/>".$row->Name."</center></td>
                    <td><center><label style='font-size:8px;'>PLATE NO</label><br/>".$plate_num."</center></td>
                </tr>
                <tr>
                    <td><center><label style='font-size:8px;'>ROUTE</label><br/>".$row->Route."</center></td>
                    <td><center><label style='font-size:8px;'>TOTAL AMOUNT</label><br/>".$row->total."</center></td>
                </tr>
            </table>
            </div>
            <br/><br/>
            <div>
                <center><font size='10px'>Kindly present this trip ticket to the Port Cashier/Teller before departure for confirmation of your reservation</font></center>
            </div>
            </body>";
            
        }
        
        // instantiate and use the dompdf class
        $options = new Options();
        $options->set([
            'isRemoteEnabled' => true
        ]);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($template);
        $dompdf->setPaper(array(0, 0, 260, 340), 'portrait');
        $dompdf->render();
        $dompdf->stream($bookingNumber."-reservation-slip".".pdf");
        exit();
    }
    
    public function fetchRoute()
    {
        $builder = $this->db->table('tblroute');
        $builder->select('Code');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option><?php echo $row->Code ?></option>
            <?php
        }
    }
    
    public function generateReport()
    {
        $user = $this->request->getGet('user');
        $fdate = $this->request->getGet('fromdate');
        $tdate = $this->request->getGet('todate');
        $route = $this->request->getGet('route');
        //generate the data
        if($route=="All Routes")
        {
            $builder = $this->db->table('tblcustomer_payment_merchant a');
            $builder->select('a.BookingNumber,FORMAT(a.TotalAmount,2)Amount,c.Route,b.TrxnDate,c.TimeDeparture,(b.Discount)*100 as discount,d.Code,b.plate_number,e.Name');
            $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
            $builder->join('tblschedule c','c.ID=a.ID','LEFT');
            $builder->join('tblvessels d','d.vesselID=a.vesselID','LEFT');
            $builder->join('tblvehicles e','e.vID=b.vID','LEFT');
            $builder->WHERE('b.agentID',$user)->WHERE('a.Remarks',"PAID");
            $builder->WHERE('b.TrxnDate >=',$fdate);
            $builder->WHERE('b.TrxnDate <=',$tdate);
            $builder->groupby('a.BookingNumber');
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                ?>
                <tr>
                    <td><?php echo $row->TrxnDate ?></td>
                    <td><?php echo $row->BookingNumber ?></td>
                    <td><?php echo $row->Route ?></td>
                    <td><?php echo $row->Code ?></td>
                    <td><?php echo $row->TimeDeparture ?></td>
                    <td><?php echo $row->Name ?></td>
                    <td><?php echo $row->plate_number ?></td>
                    <td style="text-align:center;"><?php echo $row->discount ?>%</td>
                    <td style="text-align:right;"><?php echo $row->Amount ?></td>
                </tr>
                <?php
            }
            //get the sum of all paid transaction
            $sql = "Select FORMAT(IFNULL(SUM(a.TotalAmount),0),2) Amount from tblcustomer_payment_merchant a LEFT JOIN 
            (Select BookingNumber,agentID from tblrecords_merchant GROUP BY BookingNumber) b ON b.BookingNumber=a.BookingNumber WHERE a.Remarks='PAID' AND 
            b.agentID=:user: AND a.TrxnDate BETWEEN :fdate: AND :tdate:";
            $query = $this->db->query($sql,[
                "user"=>$user,
                "fdate"=>$fdate,
                "tdate"=>$tdate,
            ]);
            if($row = $query->getRow())
            {
                ?>
                <tr style="font-weight:bold;">
                    <td colspan='8'><h5>Total</h5></td>
                    <td style="text-align:right;font-size:18px;"><?php echo $row->Amount ?></td>
                </tr>
                <?php
            }
        }
        else
        {
            $builder = $this->db->table('tblcustomer_payment_merchant a');
            $builder->select('a.BookingNumber,FORMAT(a.TotalAmount,2)Amount,c.Route,b.TrxnDate,c.TimeDeparture,(b.Discount)*100 as discount,d.Code,b.plate_number,e.Name');
            $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
            $builder->join('tblschedule c','c.ID=a.ID','LEFT');
            $builder->join('tblvessels d','d.vesselID=a.vesselID','LEFT');
            $builder->join('tblvehicles e','e.vID=b.vID','LEFT');
            $builder->WHERE('b.agentID',$user)->WHERE('a.Remarks',"PAID");
            $builder->WHERE('b.TrxnDate >=',$fdate);
            $builder->WHERE('b.TrxnDate <=',$tdate);
            $builder->WHERE('c.Route',$route);
            $builder->groupby('a.BookingNumber');
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                ?>
                <tr>
                    <td><?php echo $row->TrxnDate ?></td>
                    <td><?php echo $row->BookingNumber ?></td>
                    <td><?php echo $row->Route ?></td>
                    <td><?php echo $row->Code ?></td>
                    <td><?php echo $row->TimeDeparture ?></td>
                    <td><?php echo $row->Name ?></td>
                    <td><?php echo $row->plate_number ?></td>
                    <td style="text-align:center;"><?php echo $row->discount ?>%</td>
                    <td style="text-align:right;"><?php echo $row->Amount ?></td>
                </tr>
                <?php
            }
            //get the sum of all paid transaction
            $sql = "Select FORMAT(IFNULL(SUM(a.TotalAmount),0),2) Amount from tblcustomer_payment_merchant a LEFT JOIN 
            (Select BookingNumber,agentID from tblrecords_merchant GROUP BY BookingNumber) b ON b.BookingNumber=a.BookingNumber LEFT JOIN
            (Select ID,Route from tblschedule)c ON c.ID=a.ID
            WHERE a.Remarks='PAID' AND c.Route=:route: AND
            b.agentID=:user: AND a.TrxnDate BETWEEN :fdate: AND :tdate:";
            $query = $this->db->query($sql,[
                "user"=>$user,
                "route"=>$route,
                "fdate"=>$fdate,
                "tdate"=>$tdate,
            ]);
            if($row = $query->getRow())
            {
                ?>
                <tr style="font-weight:bold;">
                    <td colspan='8'><h5>Total</h5></td>
                    <td style="text-align:right;font-size:18px;"><?php echo $row->Amount ?></td>
                </tr>
                <?php
            }
        }
    }
    
    public function computeBalance()
    {
        $balanceModel = new \App\Models\balanceModel();
        $user = $this->request->getPost('user');
        $date = date('Y-m-d');
        //get the balance
        $balance=0.00;
        $sql = "Select IFNULL(New_Balance,0)total from tblbalance WHERE customerID=:user: ORDER BY balanceID DESC LIMIT 1";
        $query = $this->db->query($sql,[
            "user"=>$user,
            ]);
        if($row = $query->getRow())
        {
            $balance =  $row->total;
        }
        //get the recent expense 
        $expense = 0.00;
        $builder  = $this->db->table('tblcustomer_payment_merchant a');
        $builder->select('IFNULL(a.TotalAmount,0) as total');
        $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
        $builder->WHERE('b.agentID',$user)->WHERE('a.Remarks','PAID');
        $builder->groupby('a.BookingNumber')->orderby('a.BookingNumber','DESC')->limit(1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $expense = $row->total;
        }
        $new_balance = $balance - $expense;
        $values = [
            "customerID"=>$user,
            "Current_Balance"=>$balance,
            "Amount"=>$expense,
            "New_Balance"=>$new_balance,
            "Date"=>$date,
        ];
        $balanceModel->save($values);
        echo "Success";
    }
    
    public function recentExpense()
    {
        $user = $this->request->getGet('user');
        $sql = "Select FORMAT(a.TotalAmount,2)total from tblcustomer_payment_merchant a LEFT JOIN tblrecords_merchant b ON b.BookingNumber=a.BookingNumber WHERE b.agentID=:user: ORDER BY a.cpID DESC LIMIT 1";
        $query = $this->db->query($sql,[
            "user"=>$user,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function getBalance()
    {
        $user = $this->request->getGet('user');
        $builder = $this->db->table('tblmerchant');
        $builder->select('agentID');
        $builder->WHERE('merchantID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $sql = "Select FORMAT(IFNULL(New_Balance,0),2)total from tblbalance WHERE customerID=:user: ORDER BY balanceID DESC LIMIT 1";
            $query = $this->db->query($sql,[
                "user"=>$row->agentID,
                ]);
            if($row = $query->getRow())
            {
                echo $row->total;
            }
        }
    }
    
    public function listSchedules()
    {
        $builder = $this->db->table('tblschedule a');
        $builder->select('a.*,b.Name');
        $builder->join('tblvessels b','b.vesselID=a.vesselID','LEFT');
        $builder->orderby('a.ID','DESC')->limit(5);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-block">
                <div class="form-check w-100">
                    <label class="form-check-label">
                        <b><?php echo $row->Route ?></b> | <?php echo $row->Name ?>
                    </label>
                    <div class="d-flex mt-2">
                        <div class="ps-4 text-small me-3"><?php echo date('h:i:s a', strtotime($row->TimeDeparture)) ?> - <?php echo date('h:i:s a', strtotime($row->TimeArrival)) ?></div>
                        <div class="badge badge-opacity-warning me-3"><?php echo $row->Remarks ?></div>
                    </div>
                </div>
            </li>
            <?php
        }
    }
    
    public function dailyExpenses()
    {
        $user = $this->request->getGet('user');
        $date = date('Y-m-d');
        $sql = "Select FORMAT(IFNULL(SUM(a.TotalAmount),0),2)total from tblcustomer_payment_merchant a LEFT JOIN 
        (Select agentID,BookingNumber from tblrecords_merchant GROUP BY BookingNumber) b ON b.BookingNumber=a.BookingNumber WHERE a.Remarks='PAID' 
        AND b.agentID=:user: AND a.TrxnDate=:date:";
        $query = $this->db->query($sql,[
            "user"=>$user,
            "date"=>$date,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function monthlyExpenses()
    {
        $user = $this->request->getGet('user');
        $year = date('Y');
        $month = date('m');
        $sql = "Select FORMAT(IFNULL(SUM(a.TotalAmount),0),2)total from tblcustomer_payment_merchant a LEFT JOIN 
        (Select agentID,BookingNumber from tblrecords_merchant GROUP BY BookingNumber) b ON b.BookingNumber=a.BookingNumber WHERE a.Remarks='PAID' 
        AND b.agentID=:user: AND DATE_FORMAT(a.TrxnDate,'%m')=:month: AND DATE_FORMAT(a.TrxnDate,'%Y')=:year:";
        $query = $this->db->query($sql,[
            "user"=>$user,
            "month"=>$month,
            "year"=>$year,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function totalReserved()
    {
        $user = $this->request->getGet('user');
        $sql = "Select COUNT(a.BookingNumber)total from tblcustomer_payment_merchant a LEFT JOIN
        (Select agentID,BookingNumber from tblrecords_merchant GROUP BY BookingNumber)b ON b.BookingNumber=a.BookingNumber
        WHERE b.agentID=:user: AND a.Remarks='PAID'";
        $query = $this->db->query($sql,[
            "user"=>$user,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function totalPending()
    {
        $user = $this->request->getGet('user');
        $sql = "Select COUNT(a.BookingNumber)total from tblcustomer_payment_merchant a LEFT JOIN
        (Select agentID,BookingNumber from tblrecords_merchant GROUP BY BookingNumber)b ON b.BookingNumber=a.BookingNumber
        WHERE b.agentID=:user: AND a.Remarks='Unpaid'";
        $query = $this->db->query($sql,[
            "user"=>$user,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function totalBook()
    {
        $user = $this->request->getGet('user');
        $sql = "Select COUNT(a.BookingNumber)total from tblcustomer_payment_merchant a LEFT JOIN
        (Select agentID,BookingNumber from tblrecords_merchant GROUP BY BookingNumber)b ON b.BookingNumber=a.BookingNumber
        WHERE b.agentID=:user:";
        $query = $this->db->query($sql,[
            "user"=>$user,
            ]);
        if($row = $query->getRow())
        {
            echo $row->total;
        }
    }
    
    public function viewData()
    {
        $code = $this->request->getGet('code');
        $builder = $this->db->table('tblrecords_merchant');
        $builder->select('*');
        $builder->WHERE('BookingNumber',$code);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td><?php echo $row->Passenger_Type ?></td>
                <td><?php echo $row->Fullname ?></td>
                <td><?php echo $row->Accommodation ?></td>
                <td><?php echo $row->SeatNumber ?></td>
                <td><?php echo $row->plate_number ?></td>
            </tr>
            <?php
        }
    }
    
    public function Recent()
    {
        $user  = $this->request->getGet('user');
        $builder = $this->db->table('tblcustomer_payment_merchant a');
        $builder->select('a.TrxnDate,a.BookingNumber,FORMAT(a.TotalAmount,2)Amount,a.Remarks,c.Route');
        $builder->join('tblrecords_merchant b','b.BookingNumber=a.BookingNumber','LEFT');
        $builder->join('tblschedule c','c.ID=b.ID','LEFT');
        $builder->WHERE('b.agentID',$user);
        $builder->groupby('a.BookingNumber');
        $builder->orderby('a.BookingNumber','DESC')->limit(10);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            if($row->Remarks=="PAID")
            {
                ?>
                <tr>
                    <td><?php echo $row->TrxnDate ?></td>
                    <td><?php echo $row->BookingNumber ?></td>
                    <td><?php echo $row->Route ?></td>
                    <td style="text-align:right;"><?php echo $row->Amount ?></td>
                    <td><span class="badge badge-opacity-success me-3">PAID</span></td>
                </tr>
                <?php
            }
            else if($row->Remarks=="Unpaid")
            {
                ?>
                <tr>
                    <td><?php echo $row->TrxnDate ?></td>
                    <td><?php echo $row->BookingNumber ?></td>
                    <td><?php echo $row->Route ?></td>
                    <td style="text-align:right;"><?php echo $row->Amount ?></td>
                    <td><span class="badge badge-opacity-warning me-3">PENDING</span></td>
                </tr>
                <?php
            }
            else
            {
                ?>
                <tr>
                    <td><?php echo $row->TrxnDate ?></td>
                    <td><?php echo $row->BookingNumber ?></td>
                    <td><?php echo $row->Route ?></td>
                    <td style="text-align:right;"><?php echo $row->Amount ?></td>
                    <td><span class="badge badge-opacity-danger me-3">CANCELLED</span></td>
                </tr>
                <?php
            }
        }
    }
}