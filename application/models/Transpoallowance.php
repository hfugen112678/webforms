<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transpoallowance extends MY_Mssqlutils
{
    const DB_TABLE = "TranspoAllowance";
    const DB_TABLE_PK = 'ID';
    
    public $ID;
    public $EmployeeNumber;
    public $CampaignID;
    public $Datefrom;
    public $Dateto;
    public $Remarks;
    public $AuditUser;
    public $AuditDate;
    public $Status;
    public $Datefiled;

    /**
     * Filter filed transpo allowance request
     * 
     * @param int $reportto
     * @param datetime $datefrom
     * @param datetime $dateto
     * @return mixed
     */
    public function filed_transpo($reportto, $datefrom, $dateto)
    {        
        $this->db->select('a.ID, a.EmployeeNumber, a.DateFiled, a.Datefrom, a.Dateto, a.Status');
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('Employees AS b', 'a.EmployeeNumber = b.EmployeeNumber');
        $this->db->where('b.ReportTo', $reportto);
        $this->db->where('a.Datefiled >= ', $this->db->escape($datefrom));
        $this->db->where('a.Datefiled <= ', $this->db->escape($dateto));
        
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    public function hr_approval($datefrom, $dateto)
    {
        $this->db->select('*');
        $this->db->from($this::DB_TABLE);
        $this->db->where('Datefiled >= ', $this->db->escape($datefrom));
        $this->db->where('Datefiled <= ', $this->db->escape($dateto));
        $this->db->where('Status', 1);
        
        $query = $this->db->get();                
        $ret_val = $this->_populaterows($query);
        
        return $ret_val;
        
    }
}
