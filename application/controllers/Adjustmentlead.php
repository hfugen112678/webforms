<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustmentlead extends MY_Controller
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
        
        # live
        $employeenumber = $this->session->userdata('employeenumber');
        
        # test
        // $employeenumber = '100084';
        
        if ($this->input->post('start') && $this->input->post('end'))
        {
            $data['lead_approval'] = $this->disputes->lead_approval($employeenumber, $this->input->post('start'), $this->input->post('end'));
            $data['start'] = $this->input->post('start');
            $data['end'] = $this->input->post('end');
        } 
        else
        {
            $data['lead_approval'] = $this->disputes->lead_approval($employeenumber);
            $data['start'] = '';
            $data['end'] = '';
        }
        
        $this->page_title('Adjustments Approval - TREC Webforms');
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
        
        $this->load->view('adjustmentlead/view', $data);        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Approve/ decline adjustment
     */
    public function update()
    {
        $this->load->model('employeedisputes');
        
        if ($this->input->post('btndeclineadj'))
        {
            $this->employeedisputes->Status = 3;
        }
        else 
        {
            $this->employeedisputes->Status = 1;
        }
        
        // Use for live.
        $this->employeedisputes->AuditUser = $this->session->userdata('employeenumber');
        
        // Use for test.
        // $empdisputes->AuditUser = 131372;
        
        $this->employeedisputes->AuditDate = date('Y-m-d H:i:s');
        
        $this->employeedisputes->update($this->input->post('id'));
        redirect('/adjustmentlead');
    }    
}
