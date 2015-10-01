<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agentprofile 
{
    /**
     * Reference to CI 
     * 
     * @var object 
     */
    private $_ci;
    
    /**
     * Table headers
     * 
     * @var array 
     */
    private $_agents = array("#", 
                            "First Name", 
                            "Last Name", 
                            "<i class=\"ace-icon fa fa-clock-o bigger-110 hidden-480\"></i> Attendance", 
                            "<i class=\"ace-icon fa fa-bar-chart-o bigger-110 hidden-480\"></i> QA Scores", 
                            "<i class=\"ace-icon fa fa-flag bigger-110 hidden-480\"></i> Violations");    
    
    /**
     * Table headers
     * 
     * @var array 
     */    
    private $_attendance = array('Day', 
                                'Schedule', 
                                'Remarks', 
                                'Time-In', 
                                'Time-Out');
    
    /**
     * Class constructor
     * 
     * @return	void
     */    
    public function __construct()
    {
        $this->_ci = get_instance();        
        $this->_ci->load->model(array('employees', 'employeelogs'));
        
        $this->_ci->load->helper(array('my_datetime_helper',
                                       'my_variable_helper'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Agent list
     * 
     * @param string $employeenumber
     * @param string $positionid
     * @return string
     */
    public function agentlist($employeenumber, $positionid)
    {
        $params = array('header' => $this->_agents, 
            'class' => array('table table-striped table-bordered dt-table'));  
        
        $empty[] = array('No results found.', '', '', '', '', '');
        
        $this->_ci->load->library('MY_table', $params, 'agents');
        
        // List all the employees for the Operations Manager, VP and President
        if ($positionid == 58 || $positionid == 59 || $positionid == 91)
        {
            $agents = $this->_ci->employees->activeemployees();    
        }
        else
        {
            $agents = $this->_ci->employees->report_to($employeenumber);
        }
        
        if (isset($agents) && !empty($agents))
        {
            for ($i = 0; $i < count($agents); $i++)
            {
                $tabledata[] = array($agents[$i]->EmployeeNumber, 
                    $agents[$i]->FirstName, 
                    $agents[$i]->LastName,
                    $this->_agentmodal($agents[$i]->EmployeeNumber, 'attendance', 'info'), 
                    $this->_agentmodal($agents[$i]->EmployeeNumber, 'qascores', 'success'), 
                    $this->_agentmodal($agents[$i]->EmployeeNumber, 'violations', 'danger'), );
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->agents->generate($tabledata);
            }
            else
            {
                return $this->_ci->agents->generate($empty);
            }            
        }
        else
        {
            return $this->_ci->agents->generate($empty);
        }  
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display employee attendance
     * 
     * @param string $employeenumber
     * @param date $start
     * @param date $end
     * @return string
     */
    public function attendance($employeenumber, $start = NULL, $end = NULL)
    {
        $sdate = iif(isset($start), date_format(date_create($start), 'm/d/Y'), date('m/d/Y'));
        $edate = iif(isset($end), date_format(date_create($end), 'm/d/Y'), add_date(14,date_create($start), 'm/d/Y'));
        
        $empty[] = array('No results found.', '', '', '', '');
        
        $params = array('header' => $this->_attendance, 
            'class' => array('table table-striped table-bordered dt-table'), 
            'id' => 'agent-attendance');  
        
        $this->_ci->load->library('MY_table', $params, 'attendance');
        
        $attendance = $this->_ci->employeelogs->getattendance($employeenumber, $sdate, $edate);
        
        if (isset($attendance) && !empty($attendance))
        {
            for ($i = 0; $i < count($attendance); $i++)
            {
                if (!is_null($attendance[$i]->TimeIn))
                {
                    $tabledata[] = array($attendance[$i]->Day, 
                        $attendance[$i]->Schedule, 
                        iif(($attendance[$i]->Late * 60) >= 1, 'Late', ''),
                        iif(!is_null($attendance[$i]->TimeIn), convert_date($attendance[$i]->TimeIn, 'M d, Y H:i:s'), ''),
                        iif(!is_null($attendance[$i]->Timeout), convert_date($attendance[$i]->Timeout, 'M d, Y H:i:s'), ''));
                }
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->attendance->generate($tabledata);
            }
            else
            {
                return $this->_ci->attendance->generate($empty);
            }            
        }
        else
        {
            return $this->_ci->attendance->generate($empty);
        }        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Modal link
     * 
     * @param string $employeenumber
     * @param string $module
     * @return string
     */
    private function _agentmodal($employeenumer, $module, $label)
    {
        return anchor(site_url('/agentlist/' . $module . '/' . trim($employeenumer)), 
               '<span class="label label-sm label-' . $label . ' arrowed-in arrowed">View</span>', 
                array('data-toggle' => 'modal', 'role' => 'button', 'class' => 'agentlist-modal'));
    }
}
