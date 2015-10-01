<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representation extends MY_Mssqlutils 
{
    const DB_TABLE = "Representation";
    const DB_TABLE_PK = 'ID';
    
    public $ID;
    public $EmployeeNumber;
    public $RepresentationAmt;
    public $Type;
    public $PeriodStart;
    public $PeriodEnd;
    public $Remarks;
    public $AuditUser;
    public $AuditDate;

}
