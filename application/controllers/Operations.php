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
			'bootbox.min',
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
        $this->load->helper(array('my_datetime_helper', 'my_variable_helper'));
        
        $this->employeelogs->load($this->input->get('logid'));
        $data['ot_details'] = $this->employeelogs;
		
        // Check if overtime has been approved. 
        if ($this->employeelogs->StatusOT == 'Y')
        {
            $data['approved'] = 'Y';
        } 
        else if ($this->overtime->sum_overtime($this->employeelogs) != 0)
        {
            $data['approved'] = 'Y';
        }
        else
        {
            $data['approved'] = 'N';
        }		
		
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
        foreach ($this->input->post(NULL, TRUE) as $key => $val)
        {
            if ($key != 'UserlogID' && $key != 'OTReason')
            {
               if ($val > 5)
               {
                   $newval = intval(($val + 1) * 60);
               }
               else
               {
                   $newval = intval($val * 60);
               }
               $this->employeelogs->{$key} = $newval;
            }
            else 
            {
                if ($key != 'UserlogID') 
                {
                    $this->employeelogs->{$key} = $val;
                }
            }
        }
        $this->employeelogs->AuditUser = trim($this->session->userdata('employeenumber')); 
        $this->employeelogs->AuditDate = date("Y-m-d H:i:s");       
        $this->employeelogs->update($this->input->post('UserlogID'));
        echo "Updated";
    }
}
