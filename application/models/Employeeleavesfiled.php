<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employeeleavesfiled extends MY_Mssqlutils 
{
    const DB_TABLE = '[Employee.Leaves.Filed]';
    const DB_TABLE_PK = 'LeaveID';
    
    public $LeaveID;
    public $EmployeeNumber;
    public $DateFiled;
    public $LeaveFrom;
    public $LeaveTo;
    public $LeaveCount;
    public $Type;
    public $Reason;
    public $Deleted;
    public $WithPay;
    public $AuditUser;
    public $AuditDate;
 
    /**
     * Get the list of leaves filed by an employee using the date
     * range as search parameters.
     * 
     * @param string $employeenumber
     * @param date $datefrom
     * @param date $dateto
     * @return mix
     */
    public function get_leavesfiled_bydate($employeenumber, $datefrom, $dateto)
    {
        $this->db->from($this::DB_TABLE);        
        $this->db->where('CONVERT(VARCHAR(10), LeaveFrom, 21) >=\'' . $datefrom . '\'');
        $this->db->where('CONVERT(VARCHAR(10), LeaveTo, 21) <=\'' . $dateto . '\'');
        $this->db->where('EmployeeNumber =' . $employeenumber);
        $query = $this->db->get();              
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get pending leaves for approval.
     * 
     * @param type $reportto
     * @return mix
     */
    public function leaves_for_approval($reportto, $start, $end)
    {
        $this->db->from($this::DB_TABLE);
        $this->db->join('Employees', 'Employees.EmployeeNumber = ' .  $this::DB_TABLE . '.EmployeeNumber');
        $this->db->where('Employees.ReportTo =' . $reportto);   
        $this->db->where($this::DB_TABLE . '.Deleted = 1');
        $this->db->where($this::DB_TABLE . '.WithPay = 0');
        $this->db->where($this::DB_TABLE . '.EmployeeNumber = ' . $this::DB_TABLE . '.AuditUser');
        $this->db->where('CONVERT(VARCHAR(10), DateFiled, 21) >= \'' . $start . '\'');
        $this->db->where('CONVERT(VARCHAR(10), DateFiled, 21) <= \'' . $end . '\'');
        $query = $this->db->get();              
        
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get list of approved leaves per Team leader.
     * 
     * @param string $reporto
     * @param string $datefrom
     * @param string $dateto
     * @return mixed
     */
    public function approved_leaves($reporto, $datefrom, $dateto)
    {
        $datefrom .= ' 00:00:00';
        $dateto  .= ' 23:59:59';
        
        $this->db->select("a.LeaveID, a.EmployeeNumber, (b.FirstName + ' ' + b.LastName) AS Name, a.LeaveFrom, a.LeaveTo, a.Type, a.WithPay");
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('a.Deleted <> 1');
        $this->db->where('a.LeaveFrom BETWEEN ' . $this->db->escape($datefrom) . ' AND ' . $this->db->escape($dateto));
        
        if (isset($reporto) && !empty($reporto))
        {
            $this->db->where('b.ReportTo = ' . $reporto);
        }
        
        $this->db->where("b.IsActive = 'A'");
        $this->db->order_by("a.LeaveID DESC");
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retrieve list of sick leaves that may or may not require medical 
     * certificates.
     *  
     * @param type $datefrom
     * @param type $dateto
     * @return mixed
     */
    public function filed_sickleaves($datefrom = NULL, $dateto = NULL)
    {
        $startdate = ""; 
        $enddate = "";
        
        if (is_null($datefrom))
        {
            $startdate = date('Y-m-d') . ' 00:00:00';
        }
        else
        {
            $startdate = $datefrom . ' 00:00:00';
        }
        
        if (is_null($dateto))
        {
            $enddate = date('Y-m-d') . ' 23:59:59';
        }
        else
        {
            $enddate = $dateto . ' 23:59:59';
        }
        
        $this->db->from($this::DB_TABLE);
        $this->db->where('Type = \'SL\'');        
        # $this->db->where('Deleted <> 1');
        $this->db->where('WithPay = 0');
        $this->db->where('DateFiled BETWEEN \'' . $startdate . '\' AND \'' . $enddate . '\'');
        $this->db->order_by("LeaveID DESC");
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }

    // --------------------------------------------------------------------
    
    /**
     * List of unapproved sick leaves tied with medical certificates.
     * 
     * @param string $start
     * @param string $end
     * @return array
     */
    public function medcerts($start, $end)
    {
        $this->db->select($this::DB_TABLE . ".LeaveID, DateFiled, (FirstName + ' ' + LastName) AS Name, LeaveFrom, LeaveTo, LeaveCount, WithMedCert, Deleted, WithPay, " . $this::DB_TABLE . ".AuditUser");
        $this->db->from($this::DB_TABLE);
        $this->db->join('Employees',  $this::DB_TABLE . '.EmployeeNumber = Employees.EmployeeNumber');
        $this->db->join('EmployeeMedCert', $this::DB_TABLE . '.LeaveID = EmployeeMedCert.LeaveID', 'left');
        $this->db->where('Type = \'SL\'');
        $this->db->where('WithPay = 0');
        $this->db->where('CONVERT(VARCHAR(10), DateFiled, 21) BETWEEN \'' . $start . '\' AND \'' . $end . '\'');
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
}
