<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Employeelogs extends MY_Mssqlutils
{
    const DB_TABLE = 'Employeelogs';
    const DB_TABLE_PK = 'UserlogID';
    
    public $UserlogID;
    public $EmployeeNumber;
    public $TimeIn;
    public $Timeout;
    public $Regular;
    public $Overtime;
    public $StatusOT;
    public $OTReason;
    public $ApprovedOT;
    public $OTRD;
    public $ApprovedOTRD;
    public $SH;
    public $OTSH;
    public $ApprovedOTSH;
    public $OTSHR;
    public $ApprovedOTSHR;
    public $LH;
    public $OTLH;
    public $ApprovedOTLH;
    public $OTLHR;
    public $ApprovedOTLHR;
    public $NightDifferential;
    public $NDRD;
    public $NDSH;
    public $NDSHR;
    public $NDLH;
    public $NDLHR;
    public $NDOTReg;
    public $NDOTRD;
    public $NDOTSH;
    public $NDOTSHR;
    public $NDOTLH;
    public $NDOTLHR;
    public $Undertime;
    public $Late;
    public $ScheduleID;
    public $EarlyLogin;
    public $AttendanceType;
    public $AuditUser;
    public $AuditDate;
    public $LeaveFiledID;
    public $HL;
    public $ApprovedTranspo;

    // --------------------------------------------------------------------
    
    /**
     * Search for overtime using start and end date.
     * 
     * @param string $employeenumber
     * @param date $startdate
     * @param date $enddate
     * @return mixed
     */
    public function get_over_time($employeenumber, $startdate, $enddate)
    {   
        $this->db->select('*');
        $this->db->from($this::DB_TABLE);
        $this->db->where("EmployeeNumber = '" . $employeenumber . "'");
        #$this->db->where("Overtime <> 0");
        $this->db->where("TimeIn >=", $this->db->escape($startdate));
        $this->db->where("TimeIn <=", $this->db->escape($enddate)); 
        
        $query = $this->db->get();              
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Search for overtime of subordinates using team lead's employeenumber, 
     *  startdate and end date.
     * 
     * @param int $reporto 
     * @param date $startdate
     * @param date $enddate
     * @return mixed
     */    
    public function filed_overtime($reporto, $startdate, $enddate)
    {        
        $columns = 'a.UserlogID, b.FirstName, b.LastName, a.TimeIn, '
                 . 'a.Timeout, a.AuditDate, a.Overtime, a.ApprovedOT, ' 
                 . 'a.OTRD, a.ApprovedOTRD, a.OTSH, a.ApprovedOTSH, '
                 . 'a.OTSHR, a.ApprovedOTSHR, a.OTLH, a.ApprovedOTLH, '
                 . 'a.OTLHR, a.ApprovedOTLHR';
        
        $this->db->select($columns);
        $this->db->from('Employeelogs AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('b.ReportTo', $reporto);
        $this->db->where('b.IsActive', $this->db->escape('A'));
        $this->db->where('a.AuditDate >= ', $this->db->escape($startdate . " 00:00:00"));
        $this->db->where('a.AuditDate <= ', $this->db->escape($enddate . " 23:59:59"));        
        $this->db->where('a.AuditUser = a.EmployeeNumber');
        // Additional Filter
        $this->db->where('((Overtime/60) <> 0 or (OTRD/60) <> 0 or (OTSH/60) <> 0 or (OTSHR/60) <> 0 or (OTLH/60) <> 0 or (OTLHR/60) <> 0)');
        $this->db->where('OTReason IS NOT NULL');
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get list of unfiled overtime.
     *      
     * @param date $startdate
     * @param date $enddate
     * @return mixed
     */ 
    public function unfiled_overtime($startdate, $enddate)
    {
        $this->db->select('*');
        $this->db->from($this::DB_TABLE);
        $this->db->where("EmployeeNumber = '" . $this->EmployeeNumber . "'");        
        $this->db->where("TimeIn >=", $this->db->escape($startdate));
        $this->db->where("TimeIn <=", $this->db->escape($enddate));
        $this->db->where("approvedot = 0");
        $this->db->where("approvedotrd = 0");
        $this->db->where("approvedotsh = 0");
        $this->db->where("approvedotshr = 0");
        $this->db->where("approvedotlh = 0");
        $this->db->where("approvedotlhr = 0");
        
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get employee attendance
     * 
     * @param string $employeenumber
     * @param date $start
     * @param date $end
     * @return array
     */
    public function getattendance($employeenumber, $start, $end)
    {
        $function = "GetSchedulerAttendance '" . $employeenumber . "', '" . $start . "', '" . $end . "'";
        # echo $function;
        return $this->call_function($function);
    }	
    
}
