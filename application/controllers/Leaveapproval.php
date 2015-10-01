<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveapproval extends MY_Controller 
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
        $this->load->library(array('leave'));
        
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
        
        if ($this->input->post('btnfilter'))
        {
            $data['startdate'] = $this->input->post('startdate');
            $data['enddate'] = $this->input->post('enddate');
            
            // Use this for live
            $data['forapproval'] = $this->leave->leaves_for_approval(trim($this->session->userdata('employeenumber')),$data['startdate'],$data['enddate']);		
			# var_dump($data['forapproval']);
            // Use this for test
            // $data['leaveforapproval'] = $this->leave->leaves_for_approval('051039',$data['startdate'],$data['enddate']);            
        }
        else 
        {
            // Use this for live
            $data['forapproval'] = $this->leave->leaves_for_approval(trim($this->session->userdata('employeenumber')));

            // Use this for test
            // $data['leaveforapproval'] = $this->leave->leaves_for_approval('051039');
        }
        
        $this->page_title('Leaves Approval - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable',
            'bootbox.min',
            'leaveapproval'));
        
        $this->display_master('index', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View leave for approval
     */
    public function view()
    {
        $this->load->model(array('employees',
			'employeeleavesfiled', 
			'employeeleaves',
            'leavetype', 
            'employeemedcert'));
        
        $this->load->helper(array('my_datetime_helper', 'my_leavestatus_helper'));
        
        $this->employeeleavesfiled->load($this->uri->segment(3));        
        $data['leave'] = $this->employeeleavesfiled;
		$this->employeeleaves->load($this->employeeleavesfiled->EmployeeNumber);
        $data['leavecredits'] = $this->employeeleaves;
        $this->employees->load($this->employeeleavesfiled->EmployeeNumber);
        $data['employee'] = $this->employees;
        $data['leavetypes'] = $this->leavetype->get_leave_types();
        $data['leavedescription'] = $this->config->item('leavedescription');
        $data['leavepaylabels'] = $this->config->item('leavepaylabels');
        
        $this->load->view('leaveapproval/view', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Approve/ decline leave.
     */
    public function update()
    {
        $this->load->model(array('employeeleavesfiled','employeeleaves',
                'leavetype'));
        $this->load->library('leave');
        $this->load->helper(array('my_datetime_helper'));
        
        $this->leavetype->Remarks = $this->db->escape($this->input->post('leavetype'));
        $type = $this->leavetype->get();
        
        $this->employeeleavesfiled->EmployeeNumber = $this->input->post('employeenumber');
        $this->employeeleavesfiled->LeaveFrom = convert_date($this->input->post('leavefrom'), 'Y-m-d H:i:s');
        $this->employeeleavesfiled->LeaveTo = convert_date($this->input->post('leaveto'), 'Y-m-d H:i:s');      
        $this->employeeleavesfiled->LeaveCount = $this->input->post('count');
        $this->employeeleavesfiled->Type = $type[0]->LeaveType;        
        $this->employeeleavesfiled->Reason = $this->input->post('leavereason');
        $this->employeeleavesfiled->Deleted = $this->input->post('status');
        $this->employeeleavesfiled->WithPay = $this->input->post('withpay');        
        $this->employeeleavesfiled->AuditUser = '123456';
        $this->employeeleavesfiled->AuditDate = date('Y-m-d H:i:s');
        $this->employeeleavesfiled->update($this->input->post('leaveid'));
        
        // Update leave credits
        // Leave is approved      
        if ($this->input->post('status') == 0)
        {
            // Leave is with pay.
            if ($this->input->post('withpay') == 1)
            {
				$this->employees->load($this->input->post('employeenumber'));
				
                // Employees hired after May 31, 2013 have no sick leave credits. 
                // They only earn vacation leave credits which can be used in place 
                // of the sick leave.
                if (strtotime($this->employees->DateHired) > strtotime('May 31, 2013') && $this->input->post('leavetype') == 'Sick Leave')
                {
                    $leavetype = 'Vacation Leave';
                }
                else
                {
                    $leavetype = $this->input->post('leavetype');
                }				
				
                $this->leave->updatecredits($this->input->post('employeenumber'), 
                        $leavetype, 
                        $this->input->post('leavefrom'), 
                        $this->input->post('leaveto'));
						
				// Update employee logs.
				$this->leave->leavedetails($this->input->post('leaveid'));
            }

        }
        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check leave credits
     */
    public function credits()
    {
        $this->load->model('employeeleaves');
        if ($this->input->get('emp'))
        {
            $this->employeeleaves->load($this->input->get('emp'));
            $credits[] = array('sick' => $this->employeeleaves->Sick, 
                'vacation' => $this->employeeleaves->Vacation,
                'emergency' => $this->employeeleaves->Emergency,
                'maternity' => $this->employeeleaves->Maternity,
                'paternity' => $this->employeeleaves->Paternity,
                'auditdate' => $this->employeeleaves->AuditDate,);
            
            echo json_encode($credits); 
        }
    }
	
    // --------------------------------------------------------------------
    
    /**
     * Check date hired
     */
    public function datehired() 
    {
        $this->load->model('employees');
        if ($this->input->get('emp'))
        {
            $this->employees->load($this->input->get('emp'));
            $employee[] = array('datehired' => strtotime($this->employees->DateHired));
            
            $this->output->set_content_type('application/json', 'utf-8')
                         ->set_output(json_encode($employee));
        }
    }	
}
