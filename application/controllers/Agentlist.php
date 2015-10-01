<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agentlist extends MY_Controller
{
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        parent::__construct();    
        $this->load->helper(array('my_variable_helper'));
        
        // This module can only be viewed by team leads, HR etc.
        if (!in_array($this->session->userdata('positionid'), $this->config->item('management_positions')))
        {
            redirect("/operations");
        }
    }
    
    // -------------------------------------------------------------------- 
    
    /**
     * Index
     */
    public function index()
    {
        $this->load->library(array('agentprofile'));
                
        # live
        $employeenumber = $this->session->userdata('employeenumber');
        
        # test
        # $employeenumber = '051039';
        
        $data['agents'] = $this->agentprofile->agentlist($employeenumber, $this->session->userdata('positionid'));
        
        $this->page_title('Agent List - TREC Webforms');
        $this->add_css(array('dataTables.bootstrap.min', 
            'datepicker.min'));
        $this->add_js_footer(array('jquery.dataTables.min', 
            'dataTables.bootstrap.min', 
            'initdttable', 
            'agentlist'));
        $this->display_master('index', $data);  
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Employee attendance
     */
    public function attendance()
    {
        $this->load->model('employees');
        // Get employee information
        $this->employees->load($this->uri->segment(3));
        $data['employee'] =  $this->employees;
        
        $this->load->library(array('agentprofile'));
        $data['attendance'] = $this->agentprofile->attendance($this->uri->segment(3));
        $data['employeenumber'] = $this->uri->segment(3);
        $this->load->view('agentlist/attendance', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Contents of the attendance print preivew
     */
    public function printtableattendance()
    {
        $this->load->model('employees');
        // Get employee information
        $this->employees->load($this->uri->segment(3));
        $data['employee'] =  $this->employees;
        
        $this->load->library(array('agentprofile'));
        $data['attendance'] = $this->agentprofile->attendance($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
        
        $this->page_title('Print Employee Attendance');            
        $this->add_css(array('bootstrap.min', 
            'googlefonts',
            'font-awesome.min',
            'ace.min'));
        // For print media type
        $this->add_print_css(array('bootstrap.min', 
            'googlefonts',
            'font-awesome.min',
            'ace.min'));
        $this->add_js_footer(array('jquery-2.1.1.min',
            'jquery.PrintArea.js_4', 
            'printpage'));
        $this->body_class('no-skin');
        $this->display_template('printtableattendance', $data);        
    }

    // --------------------------------------------------------------------    
    
    /**
     * List of employee's QA Audits
     */
    public function qascores()
    {
        $this->load->model('employees');
        // Get employee information
        $this->employees->load($this->uri->segment(3));
        $data['employee'] =  $this->employees;
                
        $params = array('header' => array('#', 'Date of Call', 'Audit Notes', 'Date of Audit'), 
            'class' => array('table table-striped table-bordered'), 
            'id' => 'emp-audits');
        
        $this->load->library('MY_table', $params, 'auditlist');
        $data['auditlist'] = $this->auditlist->generate();
        $this->load->view('agentlist/qascores', $data);
    }
    
    // --------------------------------------------------------------------
    
    public function violations()
    {
        $this->load->model('employees');
        // Get employee information
        $this->employees->load($this->uri->segment(3));
        $data['employee'] =  $this->employees;
        
        // Get the list of violations.
        $json_violations = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/ireport/index.php/webforms/myoffenses/' . $this->uri->segment(3));
        $decoded_violations = json_decode($json_violations); 
        
        $tabledata = array();
        
        for ($i = 0; $i < count($decoded_violations); $i++) 
        {
            $tabledata[] = array(
                $decoded_violations[$i]->OffenseDate,
                anchor(site_url('agentlist/offensedetails/' . $decoded_violations[$i]->OffenseCountID . '/' . $this->employees->EmployeeNumber), $decoded_violations[$i]->OffenseCode),
                $decoded_violations[$i]->OffenseDescription,
                $decoded_violations[$i]->Points
            );
        }

        $params = array('header' => array('Date', 'Offense Code', 'Description', 'Points'), 
            'class' => array('table table-striped table-bordered dt-table'), 
            'id' => 'emp-offense-list');
        
        $this->load->library('MY_table', $params, 'offenselist');
        $data['offenselist'] = $this->offenselist->generate($tabledata);        
        $this->load->view('agentlist/violations', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display details of violation
     */
    public function offensedetails()
    {
        $this->load->helper(array('my_datetime_helper'));
        
        // Get the list of violations.
        $json_offense = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/ireport/index.php/webforms/offensedetails/' . $this->uri->segment(3));
        $offense = json_decode($json_offense); 
        
        $data['offense'] = $offense[0];
        
        $this->page_title('Employee Offense - TREC Webforms');
        // $this->add_js_footer(array('agentlist'));
        $this->display_master('offensedetails', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display details of employee qa audit.
     */
    public function empscores()
    {
        $this->load->model('employees');
        $this->load->helper(array('my_datetime_helper'));
        
        // Get employee information
        $this->employees->load($this->uri->segment(4));
        $data['employee'] =  $this->employees;
        
        // Audit scores
        $json_scores = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/trecresource/index.php/webforms/auditscores/' . $this->uri->segment(3));
        
        // Audit details
        $json_details = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/trecresource/index.php/webforms/auditdetails/' . $this->uri->segment(3));
                
        $params = array('header' => array('Parameters','PV', 'Met', 'Score'), 
            'class' => array('table table-striped table-bordered'), 
            'id' => 'emp-scores');
        
        $this->load->library('MY_table', $params, 'result');
        $data['empscore'] = $this->result->generate(json_decode($json_scores));
        $data['details'] = json_decode($json_details);
        $data['auditid'] = $this->uri->segment(3);
        
        $this->page_title('QA Scores - TREC Webforms');
        $this->add_js_footer(array('agentlist'));
        $this->display_master('empscores', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display print preview modal
     */
    public function printtableempscores()
    {
        $this->load->helper(array('my_datetime_helper'));
        
        $this->load->view('agentlist/printtableempscores');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Contents of the print preivew
     */
    public function printscore() 
    {
        $this->load->model('employees');
        $this->load->helper(array('my_datetime_helper'));
        
        // Get employee information
        $this->employees->load($this->uri->segment(4));
        $data['employee'] =  $this->employees;
        
        // Audit scores
        $json_scores = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/trecresource/index.php/webforms/auditscores/' . $this->uri->segment(3));
        
        // Audit details
        $json_details = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/trecresource/index.php/webforms/auditdetails/' . $this->uri->segment(3));
                
        $params = array('header' => array('Parameters','PV', 'Met', 'Score'), 
            'class' => array('table table-striped table-bordered'), 
            'id' => 'emp-scores');
        
        $this->load->library('MY_table', $params, 'result');
        $data['empscore'] = $this->result->generate(json_decode($json_scores));
        $data['details'] = json_decode($json_details);
        $data['auditid'] = $this->uri->segment(3);
        
        $this->page_title('Print Employee Scores');            
        $this->add_css(array('bootstrap.min', 
            'googlefonts',
            'font-awesome.min',
            'ace.min'));
        // For print media type
        $this->add_print_css(array('bootstrap.min', 
            'googlefonts',
            'font-awesome.min',
            'ace.min'));
        $this->add_js_footer(array('jquery-2.1.1.min',
            'jquery.PrintArea.js_4', 
            'printpage'));
        $this->body_class('no-skin');
        $this->display_template('printscore', $data);
    }
    
    // --------------------------------------------------------------------    
    
    /**
     * Filter attendance using dates
     */
    public function filterattendance()
    {
        $this->load->model(array('employeelogs'));
        $this->load->helper(array('my_datetime_helper'));
        
        $result = array();
        
        $start = date_format(date_create($this->input->post('start')), 'm/d/Y');
        $end = date_format(date_create($this->input->post('end')), 'm/d/Y');
        $attendance = $this->employeelogs->getattendance($this->input->post('empno'), $start, $end);
        
        for ($i = 0; $i < count($attendance); $i++)
        {
            $result[] = array('day' => $attendance[$i]->Day, 
                    'schedule' => $attendance[$i]->Schedule, 
                    'remarks' => iif(($attendance[$i]->Late * 60) >= 1, 'Late', ''),
                    'timein' => convert_date($attendance[$i]->TimeIn, 'M d, Y H:i:s'),
                    'timeout' => convert_date($attendance[$i]->Timeout, 'M d, Y H:i:s'));
        }
        
        echo json_encode($result);
    }
}
