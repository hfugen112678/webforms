<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustmentfinance extends MY_Controller
{
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        parent::__construct();     
        
        // This module can only be viewed or used by Finance.
        if ($this->session->userdata('department') != 5)
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
    
        if ($this->input->post('start') && $this->input->post('end'))
        {
            $data['adjustments'] = $this->disputes->finance_approval($this->input->post('start'), $this->input->post('end'));
            $data['start'] = $this->input->post('start');
            $data['end'] = $this->input->post('end');
        } 
        else
        {
            $data['adjustments'] = $this->disputes->finance_approval();
            $data['start'] = '';
            $data['end'] = '';
        }
        
        $this->page_title('Finance Approval - TREC Webforms');
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
     * View filed adjustment
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
        
        $this->load->view('adjustmentfinance/view', $data);        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Approve/ decline adjustment
     */
    public function update()
    {
        $this->load->model('employeedisputes');
        $this->load->library('disputes');
        $this->load->helper(array('my_payday_helper'));
        
        $empdisputes = new Employeedisputes();
        
        if ($this->input->post('btndeclineadj'))
        {
            $this->employeedisputes->Status = 3;
        }
        else 
        {
            $this->employeedisputes->Status = 2;
            $this->employeedisputes->AdjustmentAmt = $this->input->post('amount');
            $this->employeedisputes->PeriodStart = cut_off_from($this->input->post('paydays'));
            $this->employeedisputes->PeriodEnd = cut_off_to($this->input->post('paydays'));
        }
        
        $this->employeedisputes->Remarks = $this->db->escape($this->input->post('remarks'));
        $this->employeedisputes->AuditUser = $this->session->userdata('employeenumber');                
        $this->employeedisputes->AuditDate = date('Y-m-d H:i:s');        
        $this->employeedisputes->update($this->input->post('id'));
        
        if ($this->input->post('btnapproveadj'))
        {     
            if ($this->input->post('adjustments') != 3)
            {
                $this->disputes->copy_to_adjustment($this->input->post('id'));
				
                if ($this->input->post('taxableamount') != 0)
                {
                    $this->disputes->copy_to_representation($this->input->post('id'));                    
                }				
            }
            else
            {
                $this->disputes->copy_to_representation($this->input->post('id'));
            }
        }
        
        
        redirect('/adjustmentfinance');
    }    
}
