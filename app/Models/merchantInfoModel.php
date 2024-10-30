<?php namespace App\Models;
use CodeIgniter\Model;

class merchantInfoModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'tblagent';
    protected $primaryKey = 'agentID';
    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['Agent_Name','Number','EmailAddress','Address','Area','EffectiveDate','EndContract','ModePayment','Remarks'];
    
    
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