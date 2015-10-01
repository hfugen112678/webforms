<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules extends MY_Mssqlutils
{
    const DB_TABLE = "Schedules";
    const DB_TABLE_PK = 'ScheduleID';
    
    public $ScheduleID;
    public $EmployeeNumber;
    public $Date;
    public $SchedIn;
    public $SchedOut;
    public $Break;
    public $IsRestday;
    public $IsHolidayRestday;
    public $IsSuspended;
    public $IsDeleted;
    public $AuditUser;
    public $AuditDate;
    public $Remarks;
        
    /**
     * Retrieve the dates covered by the transpo allowance
     * request.
     * 
     * @param string $employeenumber
     * @param datetime $datefrom
     * @param datetime $dateto
     * @return array
     */
    public function transpo_coverage($employeenumber, $datefrom, $dateto)
    {
        $this->db->select('b.UserLogID, a.SchedIn, a.SchedOut, b.TimeIn, b.Timeout, b.ApprovedTranspo');
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('EmployeeLogs AS b', 'a.ScheduleID = b.ScheduleID');
        $this->db->where('CONVERT(VARCHAR,a.SchedIn,120) BETWEEN \'' . $datefrom . '\' AND \'' . $dateto . '\'');
        $this->db->where('a.EmployeeNumber = \'' . $employeenumber . '\'');
        $this->db->where('b.LeaveFiledID IS NULL');
        $this->db->order_by('a.ScheduleID DESC');
        
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }
    
    /**
     * Check if an employee has a schedule on a given date.
     * 
     * @param string $employeenumber
     * @param date $date
     * @return array
     */
    public function has_schedule($employeenumber, $date)
    {
        $this->db->select('*');
        $this->db->from($this::DB_TABLE);
        $this->db->where('EmployeeNumber = "' . $employeenumber . '"');
        $this->db->where('CONVERT(varchar(10),Date,21) = "' . $date . '"');
        
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }

}
