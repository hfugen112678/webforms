<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavetype  extends MY_Mssqlutils 
{
    const DB_TABLE = 'LeaveType';
    const DB_TABLE_PK = 'Oid';
    
    public $Oid;
    public $Leaveid;
    public $LeaveType;
    public $LeaveCode;
    public $Remarks;
    public $Kiosk;
    public $workforce;
    public $consolidator;
    
    // --------------------------------------------------------------------
    
    /**
     * Get list of leave types.
     *      
     * @return mixed
     */
    public function get_leave_types()
    {
        $leavetypes = array();
        
        $this->workforce = 1;
        $leaves = $this->get();
        
        if (isset($leaves) && !empty($leaves))
        {
            for ($i = 0; $i < count($leaves); $i++)
            {
                $leavetypes[trim($leaves[$i]->Remarks)] = trim($leaves[$i]->Remarks);
            }
        }
        
        return $leavetypes;
    }

}
