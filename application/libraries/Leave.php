<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave
{
    /**
     * Reference to CI 
     * @var object 
     */
    private $_ci;
    
    /**
     * Table headers
     * 
     * @var array 
     */
    private $_forapproval = array("#",
                                "First Name", 
                                "Last Name", 
                                "Date Filed",
                                "Leave From",
                                "Leave To",
                                "Count",
                                "Type"
                                );
    
    /**
     * Table headers for leaves per employee
     * 
     * @var array 
     */
    private $_leaveperemployee = array("Vacation",
                                "Emergency",
                                "Sick", 
                                "Maternity", 
                                "Paternity");    
    
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        $this->_ci = get_instance();
        $this->_ci->load->model(array('employeeleavesfiled', 
            'leavetype', 
            'schedules', 
            'holidays',
            'employeeleaves',
            'employeelogs'));
        
        $this->_ci->load->helper(array('my_datetime_helper', 'my_variable_helper'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * File employee leave
     * 
     * @return string
     */
    public function file($employeenumber, $start, $end, $type, $reason)
    {
        // Get the number of days of the leave so we can check the employee's 
        // schedule for each date.
        $days = days_difference($start, $end);
        $deny_leave = array();
        $result = "";
        
        // We'll loop through the days to check the schedule.
        for ($i = 0; $i < $days; $i++)
        {
            if ($this->_hasschedule($employeenumber, date_subtract($i,date_create($end))) != 'Ok')
            {
                $deny_leave[date_subtract($i,date_create($end))] = $this->_hasschedule($employeenumber, date_subtract($i,date_create($end)));
            }
        }
        
        if (isset($deny_leave) && !empty($deny_leave))
        {
            // echo 'Unable to file leave(s) due to the following reason(s): ' . '<br />';
            foreach ($deny_leave as $key => $value)
            {
                $result .= $key . ' - ' . $value . '<br />';
            }
        }
        else
        {
            $this->_inserttotable($employeenumber, $start, $end, $type, $reason, $days);
            #$result = 'Filed';
        }
        
        return $result;        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update leave
     * 
     * @param array $leavedetails
     * @return string
     */
    public function updateleave($leavedetails)
    {
        // Get the number of days of the leave so we can check the employee's 
        // schedule for each date.
        $days = days_difference($leavedetails->post('leavefrom'), $leavedetails->post('leaveto'));
        $deny_leave = array();
        $result = "";
        $schedcheck = "";
        
        // We'll loop through the days to check the schedule.
        for ($i = 0; $i < $days; $i++)
        {
            if (date_subtract($i,date_create($leavedetails->post('leaveto'))) != $leavedetails->post('leavefrom'))
            {
                $schedcheck = $this->_hasschedule($leavedetails->post('employeenumber'), date_subtract($i,date_create($leavedetails->post('leaveto'))));
                if ($schedcheck != 'Ok')
                {
                    $deny_leave[date_subtract($i,date_create($leavedetails->post('leaveto')))] = $schedcheck;
                }
            }
        }
        
        if (isset($deny_leave) && !empty($deny_leave))
        {
            // echo 'Unable to file leave(s) due to the following reason(s): ' . '<br />';
            foreach ($deny_leave as $key => $value)
            {
                $result .= $key . ' - ' . $value . '<br />';
            }
        }
        else 
        {
            $this->_ci->leavetype->Remarks = $this->_ci->db->escape($leavedetails->post('leavetypes'));
            $type = $this->_ci->leavetype->get();

            $this->_ci->employeeleavesfiled->AuditDate = date("Y-m-d H:i:s");
            $this->_ci->employeeleavesfiled->Type = $type[0]->LeaveType;
            $this->_ci->employeeleavesfiled->LeaveFrom = $leavedetails->post('leavefrom');
            $this->_ci->employeeleavesfiled->LeaveTo = $leavedetails->post('leaveto');
            $this->_ci->employeeleavesfiled->LeaveCount = $leavedetails->post('leavecount');
            $this->_ci->employeeleavesfiled->Reason = $leavedetails->post('leavereason');
            $this->_ci->employeeleavesfiled->Deleted = 1;
            $this->_ci->employeeleavesfiled->update($leavedetails->post('leaveid'));
        }
        return $result;
    }


    // --------------------------------------------------------------------
    
    /**
     * Display leaves for approval.
     * 
     * @param string $reportto
     * @return mixed
     */
    public function leaves_for_approval($reportto, $start = NULL, $end = NULL)
    {
        // If date parameters are empty set it to the current date.
        $sdate = iif(isset($start), $start, date('Y-m-d'));
        $edate = iif(isset($end), $end, date('Y-m-d'));
                
        $params = array('header' => $this->_forapproval, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'for_approval');
        
        $pendingleaves = $this->_ci->employeeleavesfiled->leaves_for_approval($reportto, $sdate, $edate); 
        
        $empty[] = array('No result(s) found.', '', '', '', '', '', '', '');
        
        if (isset($pendingleaves) && !empty($pendingleaves))
        {
            for ($i = 0; $i < count($pendingleaves); $i++)
            {
                $tabledata[] = array($this->_approval_modal($pendingleaves[$i]->LeaveID),
                    $pendingleaves[$i]->FirstName,
                    $pendingleaves[$i]->LastName,
                    convert_date($pendingleaves[$i]->DateFiled, "M d, Y"),
                    convert_date($pendingleaves[$i]->LeaveFrom, "M d, Y"),
                    convert_date($pendingleaves[$i]->LeaveTo, "M d, Y"),
                    $pendingleaves[$i]->LeaveCount,
                    $pendingleaves[$i]->Type);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->for_approval->generate($tabledata);
            }
            else
            {
                return $this->_ci->for_approval->generate($empty);
            }
        }
        else
        {
            return $this->_ci->for_approval->generate($empty);
        }        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update employee leave credits. But we'll need to loop through the 
     * dates to check if one (or all) the dates fall on a holiday. If the 
     * date fall on a holiday we'll need to exclude it from the leave 
     * credits deduction.
     * 
     * @param string $employeenumber
     * @param string $leavetype
     * @param date $start
     * @param date $end
     * @return void
     */
    public function updatecredits($employeenumber, $leavetype, $start, $end)
    {
        $days = days_difference($start, $end);
        $holidays = 0;
        
        // We'll loop through the days.
        for ($i = 0; $i < $days; $i++)
        {
            if ($this->_ci->holidays->checkholiday(convert_date(date_subtract($i,date_create($end)),'Y-m-d')))
            {
                $holidays++;
            }
        }
        
        $this->_ci->employeeleaves->load($employeenumber);
        $leavecoltype = substr($leavetype, 0, strpos($leavetype, ' ')); 
        $remainingcredits = $this->_ci->employeeleaves->{$leavecoltype} - ($days - $holidays);
        $this->_ci->employeeleaves->update_leave_count($employeenumber, $leavecoltype, $remainingcredits, $this->_ci->session->userdata('employeenumber'));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retrieve leave details 
     * 
     * @param int $leavefileid
     * @return void
     */
    public function leavedetails($leavefileid)
    {
        $this->_ci->employeeleavesfiled->load($leavefileid);
        
        // Get the number of days of the filed leave.
        $days = days_difference($this->_ci->employeeleavesfiled->LeaveFrom, $this->_ci->employeeleavesfiled->LeaveTo);
        
        // We'll loop through the days.
        for ($i = 0; $i < $days; $i++)
        {
            $scheddate = date_subtract($i,date_create($this->_ci->employeeleavesfiled->LeaveTo));
            $this->_inserttoemplogs($scheddate, $this->_ci->employeeleavesfiled);
        }
        
        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display leave credits by an employee.
     * 
     * @param type $employeenumber
     * @return mixed
     */
    public function leave_by_employee($employeenumber)
    {
        $params = array('header' => $this->_leaveperemployee, 
            'class' => array('table table-striped table-bordered'));
        
        $this->_ci->load->library('MY_table', $params, 'empleaves');
        
        $this->_ci->employeeleaves->load($employeenumber);
        $leavecredits = $this->_ci->employeeleaves;
        
        $empty[] = array('No results found.', '', '', '', '');
        
        if (isset($leavecredits) && !empty($leavecredits))
        {
            $tabledata[] = array($this->_inputcredits($leavecredits->Vacation, 'vacation'), 
                $this->_inputcredits($leavecredits->Emergency, 'emergency'),
                $this->_inputcredits($leavecredits->Sick, 'sick'), 
                $this->_inputcredits($leavecredits->Maternity, 'maternity'), 
                $this->_inputcredits($leavecredits->Paternity, 'paternity'),);
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->empleaves->generate($tabledata);
            }
            else
            {
                return $this->_ci->empleaves->generate($empty);
            }
        }
        else
        {
            return $this->_ci->empleaves->generate($empty);
        }
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Insert leave details to Employeelogs
     * 
     * @param date $leavedate
     * @param Employeeleavesfiled $empleave
     * @return void
     */
    private function _inserttoemplogs($leavedate, Employeeleavesfiled $empleave)
    {
        $schedule = new Schedules();
        $emplogs = new Employeelogs();
        
        $schedule->Date = '\'' . $leavedate . ' 00:00:00\'';
        $schedule->EmployeeNumber = $empleave->EmployeeNumber;
        $sched = $schedule->get();
        
        if (isset($sched) && !empty($sched))
        {
            $emplogs->EmployeeNumber = '\'' . $empleave->EmployeeNumber . '\'';
            $emplogs->ScheduleID = $sched[0]->ScheduleID; 
            $emplogs->TimeIn = '\'' . $sched[0]->SchedIn . '\'';
            $emplogs->Timeout = '\'' . $sched[0]->SchedOut . '\'';
            $emplogs->EarlyLogin = 1;
            $emplogs->AttendanceType = '\'' . $empleave->Type . '\'';
            $emplogs->LeaveFiledID = $empleave->LeaveID;
            $emplogs->insert();
        }
    }    
    
    // --------------------------------------------------------------------

    /**
     * Checks wether an employee has a listed schedule on 
     * the given date.
     * 
     * @return string
     */
    private function _hasschedule($employeenumber, $date)
    {
        $this->_ci->schedules->EmployeeNumber = $employeenumber;
        $this->_ci->schedules->Date = '\'' . $date . ' 00:00:00\'';
        $schedule = $this->_ci->schedules->get();
        $result = "";
        
        if (isset($schedule) && !empty($schedule))
        {
            if ($schedule[0]->IsRestday == 'Y')
            {
                $result = 'Restday';
            }
            elseif ($schedule[0]->IsHolidayRestday == 'Y')
            {
                $result = 'Holiday Restday';
            }
            elseif ($schedule[0]->IsSuspended == 'Y')
            {
                $result = 'Suspended';
            }
            elseif ($schedule[0]->IsDeleted == 'Y')
            {
                $result = 'Deleted';
            }
            else
            {
                if (!$this->_isdateavailable($employeenumber, $schedule[0]->Date))
                {
                    $result = 'An existing leave has already been filed for this date.';
                }
                else
                {
                    $result = 'Ok';
                }
            }
        }
        else
        {
            $result = 'No schedule';
        }
        
        return $result;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check if employee already filed a leave on the given date. 
     * This is to prevent multiple leave filing for the same date.
     * 
     * @param date $date
     * @return boolean
     */
    private function _isdateavailable($employeenumber, $date)    
    {
        $date_available = TRUE;
        $this->_ci->employeeleavesfiled->EmployeeNumber = $employeenumber;
        $this->_ci->employeeleavesfiled->LeaveFrom = '\'' . $date . '\'' ;
        $filed = $this->_ci->employeeleavesfiled->get();
        
        if (isset($filed) && !empty($filed))
        {
            $date_available = FALSE;
        }
        
        return $date_available;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Insert record to [Employee.Leaves.Filed]
     */
    private function _inserttotable($employeenumber, $start, $end, $leavetype, $reason, $days)
    {        
        $this->_ci->leavetype->Remarks = '\'' . $leavetype . '\'';
        $type = $this->_ci->leavetype->get();
        
        $this->_ci->employeeleavesfiled->EmployeeNumber = $employeenumber;
        $this->_ci->employeeleavesfiled->DateFiled = date('Y-m-d H:i:s');
        $this->_ci->employeeleavesfiled->LeaveFrom = $start;
        $this->_ci->employeeleavesfiled->LeaveTo = $end;
        $this->_ci->employeeleavesfiled->Type = $type[0]->LeaveType;
        $this->_ci->employeeleavesfiled->Reason = $reason;
        $this->_ci->employeeleavesfiled->LeaveCount = $days;
        // Set it to 1 so this will not automatically appear on the WFM
        $this->_ci->employeeleavesfiled->Deleted = 1;
        // Set it to without pay by default.
        $this->_ci->employeeleavesfiled->WithPay = '0';
        $this->_ci->employeeleavesfiled->AuditUser = $employeenumber;
        $this->_ci->employeeleavesfiled->AuditDate = date("Y-m-d H:i:s");

        $this->_ci->employeeleavesfiled->unescape_insert();
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Construct button link for viewing leave details
     * 
     * @param int $leaveid
     * @return string
     */
    private function _approval_modal($leaveid)
    {
        return form_button(array(
            'name' => 'btn-ot-modal',
            'id' => $leaveid,
            'value' => $leaveid,
            'content' => $leaveid,
            'class' => 'btn btn-link',
            'data-toggle' => 'modal',
            'data-target' => '#viewLeave',
            'data-remote' => "leaveapproval/view/" . $leaveid
        )); 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Create textbox for leave credits.
     * 
     * @param string $leavecredit
     * @param string $type
     * @return mixed
     */
    private function _inputcredits($leavecredit, $type)
    {
        return form_input(array(
            'id' => $type,
            'name' => $type,
            'value' => $leavecredit, 
            'class' => 'form-control',
            'required' => 'required'
        ));
    }    
}
