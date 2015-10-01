<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filedtranspo extends MY_Controller 
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
            // Live
            $data['filedtranspo'] = $this->transport->filter_transpo($this->session->userdata('employeenumber'), $this->input->post('startdate'), $this->input->post('enddate'));

            // Test
            // $data['filedtranspo'] = $this->transport->filter_transpo('051039', $this->input->post('startdate'), $this->input->post('enddate'));
            $data['startdate'] = $this->input->post('startdate');
            $data['enddate'] = $this->input->post('enddate');
        }
        else
        {
            // Live
            $data['filedtranspo'] = $this->transport->filter_transpo($this->session->userdata('employeenumber'));

            // Test
            // $data['filedtranspo'] = $this->transport->filter_transpo('051039');
        }
        
        $this->page_title('Transportation - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable', 
            'filedtranspo'));
        $this->display_master('index', $data); 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Team lead view of filed transpo allowance
     */
    public function teamleadview()
    {
        $this->load->model(array('transpoallowance', 'employees'));
        $this->load->helper(array('my_datetime_helper'));
                
        $this->transpoallowance->load($this->uri->segment(3));        
        $this->employees->load($this->transpoallowance->EmployeeNumber);
        
        $data['transpo'] = $this->transpoallowance;
        $data['employee'] = $this->employees;
        
        $this->load->view('filedtranspo/teamleadview', $data);
    }  

    // --------------------------------------------------------------------
    
    /**
     * Approve/ decline filed transpo allowance.
     */
    public function update()
    {
        $this->load->model(array('transpoallowance'));
        
        if ($this->input->post('action') == 'btnapprovetranspo')
        {
            // Approved
            $this->transpoallowance->Status = 1;
            echo 'Approved';
        }
        else
        {
            // Denied
            $this->transpoallowance->Status = 3;
            echo 'Denied';
        }
        
        $this->transpoallowance->AuditUser = $this->session->userdata('employeenumber');
        $this->transpoallowance->AuditDate = date('Y-m-d H:i:s');
        $this->transpoallowance->update($this->input->post('id'));
        
    }
}
