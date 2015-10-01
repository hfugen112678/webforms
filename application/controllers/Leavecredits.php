<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavecredits extends MY_Controller
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
        $this->load->library(array('leavecredit'));
        $this->load->helper(array('my_variable_helper'));
        
        $data['empleaves'] = $this->leavecredit->employees_with_leaves();
        
        $this->page_title('Leave Credits - TREC Webforms');
        $this->add_css(array('dataTables.bootstrap.min'));
        $this->add_js_footer(array('jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable'));        
        
        $this->display_master('index', $data);
    }

    // --------------------------------------------------------------------
    
    /**
     * Adjust credits
     */
    public function view()
    {
        $this->load->model('employees');
        $this->load->library('leave');
        $this->load->helper(array('my_datetime_helper'));
        
        $this->employees->load($this->uri->segment(3));        
        
        $data['employee'] = $this->employees;
        $data['empleaves'] = $this->leave->leave_by_employee($this->uri->segment(3));
        
        $this->load->view('leavecredits/view', $data);
    }

    // --------------------------------------------------------------------
    
    /**
     * Update credits
     */
    public function update()
    {
        if ($this->input->post('btnsave'))
        {        
            $this->load->model('employeeleaves');
            $this->employeeleaves->Vacation = $this->input->post('vacation');
            $this->employeeleaves->Emergency = $this->input->post('emergency');
            $this->employeeleaves->Sick = $this->input->post('sick');
            $this->employeeleaves->Maternity = $this->input->post('maternity');
            $this->employeeleaves->Paternity = $this->input->post('paternity');
            $this->employeeleaves->AuditUser = $this->session->userdata('employeenumber');
            $this->employeeleaves->AuditDate = date('Y-m-d H:i:s');
            $this->employeeleaves->updateall($this->input->post('employeenumber'));
        }        
        redirect('/leavecredits');
    }
}
