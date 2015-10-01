<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operations extends MY_Controller
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
        $this->load->library(array('overtime'));
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
                
        if ($this->input->post('startdate') && $this->input->post('enddate'))
        {
            $data['otlist'] = $this->overtime->display_overtime($this->session->userdata('employeenumber'), $this->input->post('startdate'), $this->input->post('enddate'));           
            $data['startdate'] = $this->input->post('startdate');
            $data['enddate'] = $this->input->post('enddate');
        }
        else
        {
            // Set the default filter to start date equivalent to 5 days prior 
            // to current date, end date to current date.
            $data['otlist'] = $this->overtime->display_overtime($this->session->userdata('employeenumber'), date_subtract(5), date('Y-m-d'));
        }
        
        $this->page_title('Overtime - TREC Webforms');
        $this->add_css(array('datepicker.min', 'dataTables.bootstrap.min'));
        $this->add_js_footer(array('bootstrap-datepicker.min', 
            'input-datepicker', 
            'jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable', 
            'overtime'));
        $this->display_master('index', $data);       
    }
    
    // --------------------------------------------------------------------
    
    /**
     * View filed over time
     */
    public function viewfiledovertime()
    {
        $this->load->model('employeelogs');
        $this->load->library(array('overtime'));
        $this->load->helper(array('my_datetime_helper'));
        
        $this->employeelogs->load($this->input->get('logid'));
        $data['ot_details'] = $this->employeelogs;
        $data['ottypes'] = $this->config->item('ot_types');
        $data['current_ot_type'] = $this->overtime->check_ot_type($data['ot_details']);
        $data['ot_type_column'] = array_search($data['current_ot_type']['type'], 
                $data['ottypes']);
        $this->load->view('operations/viewfiledovertime', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update filed over time
     */
    public function updateot() 
    {
        // Pontiac is set to America/New_York, we'll need to set it to Asia
        date_default_timezone_set("Asia/Singapore");
        
        $this->load->model('employeelogs');      
        $emplogs = new Employeelogs();
        $emplogs->OTReason = $this->db->escape($this->input->post('otreason'));
        $emplogs->{$this->input->post('ottype')} = ( $this->input->post('othours') * 60 );
        
        // In the event the user changes the Overtime type, we'll need to clear the 
        // old column. 
        foreach($this->config->item('ot_types') as $key => $val)
        {
            if ($key != $this->input->post('ottype'))
            {
                $emplogs->{$key} = "0";
            }
        }
        
        $emplogs->AuditUser = trim($this->session->userdata('employeenumber')); 
        $emplogs->AuditDate = date("Y-m-d H:i:s");
        $emplogs->update($this->input->post('userlogid'));
        
        echo "Updated";
    }
}
