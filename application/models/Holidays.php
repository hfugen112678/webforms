<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holidays extends MY_Mssqlutils 
{
    const DB_TABLE = 'Holidays';
    const DB_TABLE_PK = 'ID';
    
    public $ID;
    public $Date;
    public $Credit;
    public $Name;
    public $Type;
    public $AuditUser;
    public $AuditDate;
    
    // --------------------------------------------------------------------
    
    /**
     * Get the list of holidays on a given month (YYYY-MM).
     * 
     * @param string $date
     * @return array
     */
    public function current_holiday($date = "")
    {
        // If no year - month is given we'll default the search 
        // parameter to the current month and year.
        if (!isset($date))
        {
            $date = date('Y-m');
        }
        
        $this->db->select('*');
        $this->db->from($this::DB_TABLE);
        $this->db->where("CONVERT(VARCHAR(7), Date, 21) = '" . $date . "'");
        $this->db->where("Name <> ''");
        $this->db->order_by("ID ASC");
        $query = $this->db->get();
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check if given date is a holiday.
     * 
     * @param date $date
     * @return boolean
     */
    public function checkholiday($date)
    {
        $this->db->select('Name');
        $this->db->from($this::DB_TABLE);
        $this->db->where("CONVERT(VARCHAR(10), Date, 21) = '" . $date . "'");
        $this->db->where("Name <> ''");
        $query = $this->db->get();        
        $ret_val = $this->_populaterows($query);
        
        if (isset($ret_val) && !empty($ret_val))
        {
            if (isset($ret_val[0]->Name) && trim($ret_val[0]->Name) != "")
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }        
    }
}
