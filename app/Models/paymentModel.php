<?php namespace App\Models;
use CodeIgniter\Model;

class paymentModel extends Model
{
    protected $table = 'tblcustomer_payment_merchant';
    protected $primaryKey = 'cpID';
    protected $allowedFields = ['ID','vesselID','TrxnDate','TypeOfPayment','ReferenceCode','BookingNumber','TotalAmount','AmountPaid','Change_Amount','Remarks'];
}