<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves extends MY_Controller
{
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Index
     */
    public function index()
    {
        $this->load->model(array('leavetype', 'employeeleaves', 'holidays'));
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
        
        $this->employeeleaves->load($this->session->userdata('employeenumber'));
        $data['leavecredits'] = $this->employeeleaves;
        // List of holiday(s) for the current month.
        $data['holidays'] = $this->holidays->current_holiday();
        
        $this->page_title('Leaves - TREC Webforms');
        $this->add_css(array('datepicker.min', 
            'dataTables.bootstrap.min', 
            'jquery-ui.custom.min', 
            'fullcalendar.min'));
        
        $this->add_js_footer(array('bootstrap-datepicker.min',
            'input-datepicker',
            'jquery.ui.touch-punch.min',
            'moment.min',
            'fullcalendar.min',
            'bootbox.min', 
            'leaves'));
        $this->display_master('index', $data);        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get leaves by date. Will return json which will be consumed by the
     * fullcalendar.js plugin.
     *      
     */
    public function leavebydate()
    {
        $this->load->model(array('employeeleavesfiled'));
        $this->load->helper(array('my_datetime_helper'));
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        $leaves = $this->employeeleavesfiled->get_leavesfiled_bydate($this->session->userdata('employeenumber'), $start, $end);                
        $result = array();
        for ($i = 0; $i < count($leaves); $i++)
        {
            if ($leaves[$i]->Deleted == 1 && $leaves[$i]->WithPay == 0)
            {
                // Pending or not approved leave.
                $color = '#d6487e';
            }
            else
            {
                $color = '#82af6f';
            }
            
            // Notice the end date has a default time of 09:00:00, this is due 
            // to the nextDayTreshold in fullcalendar.js
            // Refer to this link for the full explaination: 
            // http://fullcalendar.io/docs/event_rendering/nextDayThreshold/
            
            $result[] = array('title' => $leaves[$i]->Reason, 
                'start' => convert_date($leaves[$i]->LeaveFrom,'Y-m-d'),
                'end' => convert_date($leaves[$i]->LeaveTo,'Y-m-d 09:00:00'),
                'leavetype' => trim($leaves[$i]->Type),
                'color' => $color,
                'leaveid' => $leaves[$i]->LeaveID);
        }

        echo json_encode($result); 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View filed leave
     */
    public function view()
    {
        $this->load->model(array('employeeleavesfiled', 
            'leavetype', 
            'employeemedcert'));
        
        $this->load->helper(array('my_datetime_helper', 'my_leavestatus_helper'));
        
        $this->employeeleavesfiled->load($this->input->get('id'));
        
        $data['leave'] = $this->employeeleavesfiled;
        $data['leavetypes'] = $this->leavetype->get_leave_types();
        $data['leavedescription'] = $this->config->item('leavedescription');
        
        if (trim($this->employeeleavesfiled->Type) == 'SL')
        {
            $this->employeemedcert->LeaveID = $this->input->get('id');
            $medcert = $this->employeemedcert->get();
            
            $checked = FALSE;
            $val = "";
            
            if (isset($medcert[0]->WithMedCert))
            {
                $val = $medcert[0]->WithMedCert;
                $checked = ($medcert[0]->WithMedCert == 'Y' ? TRUE : FALSE);                
            }
            
            $data['medcert'] = form_checkbox('medcert', $val, $checked, 'disabled="disabled"');
        }
        
        $this->load->view('leaves/view', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * File leave
     */
    public function file()
    {
        $this->load->model(array('leavetype'));
        $this->load->library(array('leave'));
        
        if ($this->input->post('btnfile'))
        {
           $result = $this->leave->file($this->session->userdata('employeenumber'), 
                     $this->input->post('start'), 
                     $this->input->post('end'), 
                     $this->input->post('leavetypes'), 
                     $this->input->post('leavereason'));
           
           //redirect('/leaves');
           if (isset($result) && trim($result) != "")
           {
               // Unable to file leave(s) due to the following reason(s): ' . '<br />';
               
               $this->page_title('Leaves - TREC Webforms');
               $this->display_master('errorfiling', array('result' => $result));
           }
           else 
           {
               redirect('/leaves');
           }
        }
        else
        {
            $data['leavetypes'] = $this->leavetype->get_leave_types();
            $data['start'] = $this->input->get('start');
            $data['end'] = $this->input->get('end');

            $this->load->view('leaves/file', $data);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update leave
     */
    public function update()
    {
        $this->load->library('leave');
        $result = $this->leave->updateleave($this->input);
        
        if (isset($result) && trim($result) != "")
        {
            // Unable to file leave(s) due to the following reason(s): ' . '<br />';
            $this->page_title('Leaves - TREC Webforms');
            $this->display_master('errorfiling', array('result' => $result));
        }
        else 
        {
            redirect('/leaves');
        }        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check the upcoming holidays on a given month. This will return json 
     * which will be used to update the list of holidays on the leave 
     * calendar for when the user clicks the next and previous buttons.
     * 
     * @return json
     */
    public function monthly_holiday()
    {
        $this->load->model(array('holidays'));
        $currentmonth = substr($this->input->get('currentmonth'),0,7);
        
        // List of holiday(s) for the current month.
        $holidays = $this->holidays->current_holiday($currentmonth);
        $result = array();
        
        for ($i = 0; $i < count($holidays); $i++)
        {
            $result[] = array('Name' => $holidays[$i]->Name,
                'Date' => $holidays[$i]->Date, 
                'Type' => $holidays[$i]->Type);
        }
        
        echo json_encode($result);
    }
}
