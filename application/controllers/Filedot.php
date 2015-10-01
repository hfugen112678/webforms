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
			'bootbox.min',
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
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
        
        $this->employeelogs->load($this->input->get('logid'));
        $data['ot_details'] = $this->employeelogs;
        $this->load->view('filedot/viewfiledovertime', $data);
    }
    
    // --------------------------------------------------------------------
    
    public function updateot($declined = NULL)
    {
        // Pontiac is set to America/New_York, we'll need to set it to Asia
        date_default_timezone_set("Asia/Singapore");
        $this->load->model('employeelogs');
        
        if (isset($declined) && trim($declined) != "")
        {
            $this->employeelogs->OTReason = 'Declined - ' . $this->input->post('OTReason');
        }
        else
        {
            foreach ($this->input->post(NULL, TRUE) as $key => $val)
            {
                if ($key != 'UserlogID') 
                {
                    if ($key == 'ApprovedOT')
                    {
                        // For regular over time.
                        $this->employeelogs->StatusOT = 'Y';
                    }
                    $this->employeelogs->{$key} = $val;
                }
            }
        }
        
        $this->employeelogs->AuditUser = trim($this->session->userdata('employeenumber')); 
        $this->employeelogs->AuditDate = date("Y-m-d H:i:s");
        $this->employeelogs->update($this->input->post('UserlogID'));
        echo "Updated";
    }
    
    // --------------------------------------------------------------------
}
