<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employeemedcert extends MY_Mssqlutils  
{
    const DB_TABLE = "EmployeeMedCert";
    const DB_TABLE_PK = 'ID';
    
    public $ID;
    public $LeaveID;
    public $WithMedCert;
    public $AuditUser;
    public $AuditDate;
}
