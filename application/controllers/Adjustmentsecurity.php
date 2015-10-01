<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustmentsecurity extends MY_Controller
{
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        parent::__construct();  
        
        // This module can only be viewed or used by Security.
        if ($this->session->userdata('department') != 11)
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
        $this->load->library('disputes');
        $this->load->helper(array('my_datetime_helper'));        
        
        $data['securities'] = $this->disputes->security_approval();
        
        $this->page_title('Adjustments Security Approval - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable'));
        
        $this->display_master('index', $data);          
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View filed dispute
     */
    public function view()
    {
        $this->load->model(array('employeedisputes', 'employees'));
        $this->load->helper(array('my_datetime_helper'));
        
        $this->employeedisputes->load($this->uri->segment(3));
        
        $this->employees->load($this->employeedisputes->EmployeeNumber);
        
        $types = $this->config->item('adjustments');
        
        $data['empadjustments'] = $this->employeedisputes;
        $data['employee'] = $this->employees;
        $data['types'] = $types;
        
        $this->load->view('adjustmentsecurity/view', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update dispute
     */    
    public function update()
    {
        $this->load->model(array('employeedisputes'));
        
        $this->employeedisputes->SecurityNotes = $this->input->post('remarks');
        $this->employeedisputes->SecurityID = $this->session->userdata('employeenumber');        
        $this->employeedisputes->AuditDate = date('Y-m-d H:i:s');        
        $this->employeedisputes->update($this->input->post('disputeid'));
        redirect('/adjustmentsecurity');
    }    
}
