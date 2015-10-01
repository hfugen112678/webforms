<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustment extends MY_Mssqlutils
{
    const DB_TABLE = "Adjustment";
    const DB_TABLE_PK = "ID";
    
    public $ID;
    public $EmployeeNumber;
    public $CampaignID;
    public $AdjustmentAmt;
    public $Type;
    public $PeriodStart;
    public $PeriodEnd;
    public $Remarks;
    public $AuditUser;
    public $AuditDate;

}
