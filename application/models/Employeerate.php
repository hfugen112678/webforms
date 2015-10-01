<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employeerate extends MY_Mssqlutils
{
    const DB_TABLE = '[Employee.Rate]';
    const DB_TABLE_PK = 'EmployeeNumber';
    
    public $EmployeeNumber;
    public $PerMonth;
    public $PerHalfMonth;
    public $PerDay;
    public $PerHour;
    public $PerMinute;
    public $Representation;
    public $Allowance;
    public $TaxShield;
    public $Transpo;
    public $L2Incentive;
}

