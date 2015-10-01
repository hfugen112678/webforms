<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medcerts extends MY_Controller 
{
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        parent::__construct();
        
        // This module can only be viewed or used by Human Resources 
        // and nurses.
        if ($this->session->userdata('department') != 6)
        {
            redirect("/");
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Index
     */
    public function index()
    {
        $this->load->library('medcert');
        $this->load->helper('my_variable_helper');
        
        if ($this->input->post('start') && $this->input->post('end'))
        {
            $data['medcerts'] = $this->medcert->sick_leaves($this->input->post('start'), $this->input->post('end'));
            $data['start'] = $this->input->post('start');
            $data['end'] = $this->input->post('end');
        }
        else
        {
            $data['medcerts'] = $this->medcert->sick_leaves();
        }
        
        $this->page_title('Medical Certificates - TREC Webforms');  
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));        
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable',
            'medcert'));
        $this->display_master('index', $data);            
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update or create employee medical certificates.
     */
    public function update()
    {
        $this->load->model(array('employeemedcert'));
        
        if ($this->input->post('id') && $this->input->post('colval'))
        {
            $this->employeemedcert->LeaveID = $this->input->post('id');
            $cert = $this->employeemedcert->get();
            if (isset($cert) && !empty($cert))
            {
                $this->employeemedcert->WithMedCert = $this->input->post('colval');
                $this->employeemedcert->AuditUser = $this->session->userdata('employeenumber');
                $this->employeemedcert->AuditDate = date('Y-m-d h:i:s');
                $this->employeemedcert->update($cert[0]->ID);
            }
            else
            {
                $this->employeemedcert->LeaveID = $this->input->post('id');
                $this->employeemedcert->WithMedCert = '\'Y\'';
                $this->employeemedcert->AuditUser = $this->session->userdata('employeenumber');
                $this->employeemedcert->AuditDate = '\'' . date('Y-m-d h:i:s') . '\'';
                $this->employeemedcert->insert();
            }
        }        
    }
}
