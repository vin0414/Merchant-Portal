<?php namespace App\Models;
use CodeIgniter\Model;

class balanceModel extends Model
{
    protected $table = 'tblbalance';
    protected $primaryKey = 'balanceID';
    protected $allowedFields = ['customerID','Current_Balance','Amount','New_Balance','Date'];
}