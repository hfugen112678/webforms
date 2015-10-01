<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Overtime
{
    /**
     * Reference to CI 
     * @var object 
     */
    private $_ci;
    
    /**
     * Headers for the search results
     * 
     * @var array 
     */
    private $_tableheaders = array(
                                '#',
                                'Time In', 
                                'Time Out',
                                'OT Hours',         
                                'Type', 
                                'Approved OT'
                                );
    
    /**
     * Table headers for filed ot
     * 
     * @var array 
     */
    private $_filedot = array("#", 
                                "First name", 
                                "Last name", 
                                "Time In", 
                                "Time Out", 
                                "Date Filed", 
                                "Status"
                                );
    
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        $this->_ci = get_instance();
        $this->_ci->load->model('employeelogs');
        $this->_ci->load->helper('my_datetime_helper');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Filter overtime using a specified date range.
     * 
     * @param string $employeenumber
     * @param date $startdate
     * @param date $enddate
     * @return string
     */
    public function display_overtime($employeenumber, $startdate, $enddate)
    {
        $startdate .= ' 00:00:00';
        $enddate .= ' 23:59:59';
        
        $otlist = $this->_ci->employeelogs->get_over_time($employeenumber, $startdate, $enddate);
        $empty[] = array('No result(s) found.', '', '', '', '', '');
        
        $params = array('header' => $this->_tableheaders, 'id' => 'otlist', 'class' => array('table table-striped table-bordered table-hover dt-table'));
        $this->_ci->load->library('MY_Table', $params, 'ot_list');
        
        if (isset($otlist) && !empty($otlist))
        {
            for ($i = 0; $i < count($otlist); $i++)
            {
                $rs = $this->_state_overtime($otlist[$i]);
                
                if (isset($rs) && !empty($rs))
                {
                    $overtime[] = $rs;
                }
            }
            
            if (isset($overtime) && !empty($overtime))
            {
                return $this->_ci->ot_list->generate($overtime);
            }
            else 
            {
                return $this->_ci->ot_list->generate($empty);
            }
        }
        else 
        {
            return $this->_ci->ot_list->generate($empty);  
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Construct table row of search results.
     * 
     * @param object $recordset
     * @return array
     */
    private function _state_overtime($recordset)
    {
        $row_ot = array();
        $ot = $this->check_ot_type($recordset); 
               
        if (isset($ot) && !empty($ot) && $ot['ot_hours'] != 0)
        {
            $row_ot = array($this->_ot_modal($recordset->UserlogID, "operations/viewfiledovertime?logid=" . $recordset->UserlogID),
                      convert_date($recordset->TimeIn, "M d, Y h:i A"),
                      convert_date($recordset->Timeout, "M d, Y h:i A"),
                      $ot['ot_hours'],
                      $ot['type'],
                      $ot['approved']);
        }
        
        return $row_ot;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check Overtime type and return approved number
     * of hours.
     * 
     * @param object $recordset
     * @return array
     */
    public function check_ot_type($recordset)
    {
        $ot = array();
        foreach ($this->_ci->config->item('ot_types') as $key => $val)
        {
            if ($recordset->{$key} != 0)
            {
                $ot['ot_hours'] = format_overtime($recordset->{$key});
                $ot['type'] = $val;
                
                if ($key == 'Overtime')
                {
                    $ot['approved'] = ($recordset->ApprovedOT != 0 ? $recordset->ApprovedOT : 0);
                }
                else
                {
                    $ot['approved'] = ($recordset->{'Approved' . $key} != 0 ? $recordset->{'Approved' . $key} : 0);
                }
            }
        }
        return $ot;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the list of employees that have filed overtime within a specified 
     * date range.
     * 
     * @param string $employeenumber
     * @param string $datestart
     * @param string $dateend
     * @return mixed
     */
    public function filed_overtime($employeenumber, $startdate, $enddate)
    {
        $subordinates = $this->_ci->employeelogs->filed_overtime($employeenumber, $startdate, $enddate);
        $tabledata = array();    
        $empty[] = array('No result(s) found.', '', '', '', '', '', '');
        
        $params = array('header' => $this->_filedot, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'subordinate_list');
                if (isset($subordinates) && !empty($subordinates))
        {
            for ($i = 0; $i < count($subordinates); $i++)
            {
                $tabledata[] = array($this->_ot_modal($subordinates[$i]->UserlogID, "filedot/viewfiledovertime?logid=" . $subordinates[$i]->UserlogID), 
                    $subordinates[$i]->FirstName, 
                    $subordinates[$i]->LastName, 
                    convert_date($subordinates[$i]->TimeIn, "M d, Y h:i A"),
                    convert_date($subordinates[$i]->Timeout, "M d, Y h:i A"),
                    convert_date($subordinates[$i]->AuditDate, "M d, Y"), 
                    $this->_ot_status($subordinates[$i]));
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->subordinate_list->generate($tabledata);
            }
            else
            {
                return $this->_ci->subordinate_list->generate($empty); 
            }
        }
        else
        {
            return $this->_ci->subordinate_list->generate($empty); 
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Construct button link for filing/ editing Overtime
     * 
     * @param int $userlogid
     * @return string
     */
    private function _ot_modal($userlogid, $dataremote)
    {        
        // $dataremote = "operations/viewfiledovertime?logid=" . $userlogid;               
        return anchor($dataremote, $userlogid, array(
            'data-toggle' => 'modal',
            'data-target' => '#updateOT'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set the display status of the overtime.
     * 
     * @param array $recordset
     * @return string
     */
    private function _ot_status($recordset)
    {
        $result = $this->check_ot_type($recordset);
        if (isset($result['approved']) && $result['approved'] != 0)
        {
            return 'Y';
        }
        else
        {
            return 'N';
        }
    }
}
