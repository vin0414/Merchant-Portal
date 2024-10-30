<?php namespace App\Models;
use CodeIgniter\Model;

class onlinePaymentModel extends Model
{
    protected $table = 'tblpayment';
    protected $primaryKey = 'payID';
    protected $allowedFields = ['BookingNumber','Total_Payment','Date','PaymentMethod','Status','customerID','Time', 'gcash_screenshot'];
}