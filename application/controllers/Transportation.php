<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transportation extends MY_Controller 
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
        $data['transpo'] = $this->transport->filed_transpo($this->session->userdata('employeenumber'));
        
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
        
        $this->page_title('Transportation - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable',));
        $this->display_master('index', $data); 
    }
    
    // --------------------------------------------------------------------
    
    /**
     * File tranpo allowance request
     */
    public function filetranspo()
    {
        if ($this->input->post('btnfiletranspo'))
        {
            $this->load->model('transpoallowance');
            
            $this->transpoallowance->ID = "";
            $this->transpoallowance->EmployeeNumber = "'" . $this->session->userdata('employeenumber') . "'";
            $this->transpoallowance->Datefiled = $this->db->escape(date('Y-m-d H:i:s'));
            $this->transpoallowance->Datefrom = $this->db->escape($this->input->post('startdate'));
            $this->transpoallowance->Dateto = $this->db->escape($this->input->post('enddate'));
            $this->transpoallowance->Remarks = $this->db->escape($this->input->post('remarks'));
            $this->transpoallowance->AuditUser = "'" . $this->session->userdata('employeenumber') . "'";
            $this->transpoallowance->AuditDate = $this->db->escape(date('Y-m-d H:i:s'));
            $this->transpoallowance->Status = $this->db->escape("0");
            $this->transpoallowance->insert();
        }
        
        redirect('/transportation');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View filed transpo allowance request
     */
    public function viewfiledtranspo()
    {
        if ($this->uri->segment(3))
        {
            $this->load->model(array('transpoallowance'));
            $this->load->helper(array('my_datetime_helper'));

            $this->transpoallowance->load($this->uri->segment(3));
            $data['transpo'] = $this->transpoallowance;

            $this->load->view('transportation/viewfiledtranspo', $data);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update transpo allowance request
     */
    public function updatetranspo()
    {
        $this->load->model(array('transpoallowance'));
        
        $this->transpoallowance->Datefrom = date('Y-m-d H:i:s', strtotime($this->input->post('transpofrom')));
        $this->transpoallowance->Dateto = date('Y-m-d H:i:s', strtotime($this->input->post('transpoto')));
        $this->transpoallowance->Remarks = $this->input->post('remarks');
        $this->transpoallowance->AuditUser = $this->session->userdata('employeenumber');
        $this->transpoallowance->AuditDate = date('Y-m-d H:i:s');
        $this->transpoallowance->update($this->input->post('id'));
        redirect(site_url('/transportation'));
    }
}
