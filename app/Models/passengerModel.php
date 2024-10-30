<?php namespace App\Models;
use CodeIgniter\Model;

class passengerModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'tblrecords_merchant';
    protected $primaryKey = 'recordID';
    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['portID','ID','agentID','Passenger_Type','ID_number','Discount','Amount','vID','mID','plate_number',
    'Fullname','Contact','BirthDate','EmailAddress','Address','Gender','Insured','Accommodation','SeatNumber',
    'TrxnDate','BookingNumber','Remarks','Status','customerID'];
       
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}