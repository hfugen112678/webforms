<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employeedisputes extends MY_Mssqlutils
{
    const DB_TABLE = "EmployeeDisputes";
    const DB_TABLE_PK = "ID";
    
    public $ID;
    public $EmployeeNumber;
    public $CampaignID;
    public $AdjustmentAmt;
    public $Type;
    public $DateOccurred;
    public $PeriodStart;
    public $PeriodEnd;
    public $Remarks;
    public $AuditUser;
    public $AuditDate;
    public $Status;
    public $SecurityNotes;
    public $SecurityID;
	public $TaxableAmt;

    /**
     * Get list of disputes for TL approval using the ReportTo
     * column from the Employees table, and AuditDate from
     * the EmployeeDisputes table.
     * 
     * @param int $reporto
     * @param string $datefrom
     * @param string $dateto
     * @return mixed
     */
    public function for_approval($reporto, $datefrom, $dateto)
    {
        $datefrom .= ' 00:00:00';
        $dateto  .= ' 23:59:59';
        
        $this->db->select("a.ID,a.EmployeeNumber,(b.FirstName + ' ' + b.LastName) as 'Name', a.Type,a.AuditDate, a.DateOccurred, a.PeriodStart, a.PeriodEnd");
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('b.ReportTo = ' . $reporto);
        $this->db->where('a.Status = 0');
        $this->db->where('a.AuditDate BETWEEN ' . $this->db->escape($datefrom) . ' AND ' . $this->db->escape($dateto));
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the list of adjustments for finance approval.
     * 
     * @param string $datefrom
     * @param string $dateto
     * @return mixed
     */
    public function finance_approval($datefrom, $dateto)
    {
        $datefrom .= ' 00:00:00';
        $dateto  .= ' 23:59:59';
        
        $this->db->select("a.ID,a.EmployeeNumber,(b.FirstName + ' ' + b.LastName) as 'Name', a.Type, a.DateOccurred,a.AuditDate, a.PeriodStart, a.PeriodEnd, a.AdjustmentAmt");
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('a.Status = 1');
        $this->db->where('a.AuditDate BETWEEN ' . $this->db->escape($datefrom) . ' AND ' . $this->db->escape($dateto));
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the list of approved disputes using dates and Teamleader's 
     * employee number as search filters.
     * 
     * @param string $employeenumber
     * @param string $datefrom
     * @param string $dateto     
     * @return mixed
     */
    public function approved_disputes($employeenumber, $datefrom, $dateto)
    {
        $datefrom .= ' 00:00:00';
        $dateto  .= ' 23:59:59';
        
        $this->db->select("a.ID, a.EmployeeNumber, (b.FirstName + ' ' + b.LastName) AS Name, a.Type, a.Status");
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('b.ReportTo = ' . $employeenumber);
        $this->db->where('a.AuditDate BETWEEN ' . $this->db->escape($datefrom) . ' AND ' . $this->db->escape($dateto));
        $this->db->where('Status <> 3');
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get list of No Log in/ No log out that have not yet been approved by
     * the agents' team leaders.
     * 
     * @return mixed
     */
    public function security_verification()
    {
        $this->db->select("a.ID, a.EmployeeNumber, a.Type, (b.FirstName + ' ' + b.LastName) AS Name");
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('a.Type IN (0,1)');
        $this->db->where('a.Status = 0');
        
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
}
