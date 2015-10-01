<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filedot extends MY_Controller
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
            // Live
            $data['subordinates'] = $this->overtime->filed_overtime($this->session->userdata('employeenumber'), $this->input->post('startdate'), $this->input->post('enddate'));            
            // Test
            // $data['subordinates'] = $this->overtime->filed_overtime('051039', $this->input->post('startdate'), $this->input->post('enddate'));
            $data['startdate'] = $this->input->post('startdate');
            $data['enddate'] = $this->input->post('enddate');
        }
        else
        {
            // Use this for live.
            $data['subordinates'] = $this->overtime->filed_overtime($this->session->userdata('employeenumber'), date_subtract(3), date('Y-m-d'));
            // Test
            // $data['subordinates'] = $this->overtime->filed_overtime('051039', date_subtract(3), date('Y-m-d'));
        }
        
        $this->page_title('Filed Overtime - TREC Webforms');
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
     * View approved or declined overtime
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
        $this->load->view('filedot/viewfiledovertime', $data);
    }
    
    // --------------------------------------------------------------------
    
    public function updateot($declined = "")
    {
        // Pontiac timezone is set to America/New_York, 
        // we'll need to set it to Asia
        date_default_timezone_set("Asia/Singapore");
        
        $this->load->model('employeelogs');
        
        if (isset($declined) && trim($declined) != "")
        {
            if ($this->input->post('ottype') == 'Overtime')
            {
                $this->employeelogs->{'ApprovedOT'} = '0';
                $this->employeelogs->{'StatusOT'} = 'N';
            } 
            else 
            {
                $this->employeelogs->{'Approved' . $this->input->post('ottype')} = '0';
            }

            $this->employeelogs->AuditUser = NULL;
            $this->employeelogs->AuditDate = NULL;        
        }
        else
        {
            if ($this->input->post('ottype') == 'Overtime')
            {
                $this->employeelogs->{'ApprovedOT'} = $this->input->post('othours');
                $this->employeelogs->{'StatusOT'} = 'Y';
            } 
            else 
            {
                $this->employeelogs->{'Approved' . $this->input->post('ottype')} = $this->input->post('othours');
            }

            $this->employeelogs->AuditUser = $this->session->userdata('employeenumber');            
            $this->employeelogs->AuditDate = date("Y-m-d H:i:s");
        }
        
        $this->employeelogs->update($this->input->post('userlogid'));
        echo "Updated";
    }
    
    // --------------------------------------------------------------------
}
