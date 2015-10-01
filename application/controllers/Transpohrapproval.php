<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transpohrapproval extends MY_Controller 
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
        $this->load->library(array('transport')); 
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper')); 
        
        if ($this->input->post('startdate') && $this->input->post('enddate'))
        {
            $data['transpo'] = $this->transport->hr_view($this->input->post('startdate'), $this->input->post('enddate'));
            $data['startdate'] = $this->input->post('startdate');
            $data['enddate'] = $this->input->post('enddate');
        }
        else
        {
            $data['transpo'] = $this->transport->hr_view();
        }
        
        $this->page_title('Transportation - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable', 
            'transpoapproval'));
        $this->display_master('index', $data); 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View filed transpo request.
     */    
    public function viewfiledtranspo()
    {
        $this->load->model(array('employees','transpoallowance'));
        $this->load->library(array('transport')); 
        $this->load->helper(array('my_datetime_helper'));
        
        $this->transpoallowance->load($this->uri->segment(3));
        $this->employees->load($this->transpoallowance->EmployeeNumber);
        
        $data['transpoallowance'] = $this->transpoallowance;
        $data['employee'] = $this->employees;
        $data['coverage'] = $this->transport->transpo_coverage($this->transpoallowance->EmployeeNumber, convert_date($this->transpoallowance->Datefrom, "Y-m-d"), convert_date($this->transpoallowance->Dateto, "Y-m-d"));
        
        $this->load->view('transpohrapproval/viewfiledtranspo', $data);
    }

    // --------------------------------------------------------------------
    
    /**
     * Update filed transpo request.
     */ 
    public function update()
    {
        if ($this->input->post('userlogid'))
        {
            $this->load->model(array(
                'employeerate', 
                'employeelogs', 
                'transpoallowance'));
            
            $this->employeerate->load($this->input->post('empno'));
            
            // Compute for daily transpo allowance
            $dailytranspo = $this->employeerate->Transpo / 22;
            
            if ($this->input->post('status') === 'true')
            {
                $this->employeelogs->ApprovedTranspo = $dailytranspo;
            }
            else
            {
                $this->employeelogs->ApprovedTranspo = '0';
            }
            
            $this->employeelogs->AuditUser = $this->session->userdata('employeenumber');
            $this->employeelogs->AuditDate = date('Y-m-d H:i:s'); 
            $this->employeelogs->update($this->input->post('userlogid'));
            
            # Update status
            $this->transpoallowance->Status = 2;
            $this->transpoallowance->AuditUser = $this->session->userdata('employeenumber');
            $this->transpoallowance->AuditDate = date('Y-m-d H:i:s');
            $this->transpoallowance->update($this->input->post('transpoid'));
        }
    }
    
}
