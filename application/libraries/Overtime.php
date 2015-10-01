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
                                'Schedule',
                                'Time In', 
                                'Time Out',
                                'OT Hours', 
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
        $start = convert_date($startdate, 'm/d/Y');
        $end = convert_date($enddate, 'm/d/Y');
        
        $otlist = $this->_ci->employeelogs->get_over_time($employeenumber, $start, $end);
        
        $params = array('header' => $this->_tableheaders, 'id' => 'otlist', 'class' => array('table table-striped table-bordered table-hover dt-table'));
        $this->_ci->load->library('MY_Table', $params, 'ot_list');
        
        if (isset($otlist) && !empty($otlist))
        {
            for ($i = 0; $i < count($otlist); $i++)
            {
                if ($otlist[$i]->overtime >= 1)
                {
                    $overtime[] = array($this->_ot_modal($otlist[$i]->userlogid, "operations/viewfiledovertime?logid=" . $otlist[$i]->userlogid),
                        $otlist[$i]->schedule,
                        convert_date($otlist[$i]->TimeIn, "M d, Y h:i A"),
                        convert_date($otlist[$i]->Timeout, "M d, Y h:i A"),
                        $otlist[$i]->overtime,
                        $otlist[$i]->approvedot);
                }
            }
            
            if (isset($overtime) && !empty($overtime))
            {
                return $this->_ci->ot_list->generate($overtime);
            }
            else 
            {
                return $this->_ci->ot_list->generate();
            }
        }
        else 
        {
            return $this->_ci->ot_list->generate();  
        }
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
                return $this->_ci->subordinate_list->generate(); 
            }
        }
        else
        {
            return $this->_ci->subordinate_list->generate(); 
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the sum of approved overtimes, i.e. regular overtime, RDOT etc.
     * 
     * @param object $userlog
     * @return int
     */
    public function sum_overtime(Employeelogs $userlog)
    {
        return $userlog->ApprovedOT + $userlog->ApprovedOTRD + $userlog->ApprovedOTSH + $userlog->ApprovedOTSHR + $userlog->ApprovedOTLH + $userlog->ApprovedOTLHR;
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
