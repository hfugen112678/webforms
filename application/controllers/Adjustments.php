<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustments extends MY_Controller
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
        $this->load->library('disputes');  
        
        $data['adjustment'] = $this->disputes->adjustment_listing($this->session->userdata('employeenumber'));
        
        $this->load->helper(array('my_datetime_helper'));        
        
        $this->page_title('My Adjustments - TREC Webforms');
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
     * File dispute
     */
    public function file()
    {
        $this->load->model('employeedisputes');
        $this->load->helper('my_payday_helper'); 
        
        $this->employeedisputes->EmployeeNumber = $this->session->userdata('employeenumber');
        $this->employeedisputes->Type = $this->input->post('adjustments');
        $this->employeedisputes->AdjustmentAmt = $this->db->escape('0.00');
		$this->employeedisputes->TaxableAmt = $this->db->escape('0.00');
        $this->employeedisputes->DateOccurred = $this->db->escape($this->input->post('dateofadjustment'));
        $this->employeedisputes->PeriodStart = $this->db->escape(cut_off_from($this->input->post('paydays')));
        $this->employeedisputes->PeriodEnd = $this->db->escape(cut_off_to($this->input->post('paydays')));
        $this->employeedisputes->Remarks = $this->db->escape($this->input->post('remarks'));
        $this->employeedisputes->AuditUser = $this->session->userdata('employeenumber');
        $this->employeedisputes->AuditDate = $this->db->escape(date('Y-m-d H:i:s'));
        $this->employeedisputes->Status =  $this->db->escape("0");
        $this->employeedisputes->insert();
        redirect('/adjustments');        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View adjustment
     */
    public function view()
    {
        $this->load->model('employeedisputes');
        $this->load->helper(array('my_datetime_helper', 'string'));
        
        $this->employeedisputes->load($this->uri->segment(3));
        
        // Determine the payday.
        if (substr($this->employeedisputes->PeriodEnd, 8, 2) == date("t", strtotime($this->employeedisputes->PeriodEnd)))
        {
            $data['payday'] = 10;
        }
        else
        {
            $data['payday'] = 25;
        }
        
        $data['empadjustments'] = $this->employeedisputes;
        
        $this->load->view('adjustments/view', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update adjustment
     */
    public function update()
    {
        $this->load->model('employeedisputes');
        $this->load->helper('my_payday_helper');
                
        $this->employeedisputes->Type = $this->input->post('adjustments');
        $this->employeedisputes->DateOccurred = $this->input->post('dateoccurred');
        $this->employeedisputes->Type = $this->input->post('adjustments');
        $this->employeedisputes->PeriodStart = cut_off_from($this->input->post('paydays'));
        $this->employeedisputes->PeriodEnd = cut_off_to($this->input->post('paydays'));
        $this->employeedisputes->Remarks = $this->input->post('remarks');
        $this->employeedisputes->AuditUser = $this->session->userdata('employeenumber');
        $this->employeedisputes->AuditDate = date('Y-m-d H:i:s');
        $this->employeedisputes->update($this->input->post('id'));
        
        redirect('/adjustments');
    }
}
