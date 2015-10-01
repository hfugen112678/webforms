<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport 
{
    /**
     * Reference to CI 
     * @var object 
     */
    private $_ci;
    
    /**
     * Table headers
     * 
     * @var array 
     */
    private $_filedtranspo = array("#", 
                                "Date Filed", 
                                "Date From", 
                                "Date To", 
                                "# of days",
                                "Status");
    
    /**
     * Table headers
     * 
     * @var array 
     */
    private $_coverageheaders = array("#", 
                                    "Schedule In",
                                    "Schedule Out",
                                    "Time In",
                                    "Time Out",
                                    "Approve");
    
    /**
     * Transpo Allowance approval status
     * 
     * @var array 
     */
    private $_status;
    
    /**
     * Empty results.
     * 
     * @var array 
     */
    private $_empty;
    
    /**
     * Class constructor
     * 
     * @return	void
     */
    public function __construct()
    {
        $this->_ci = get_instance();
        $this->_ci->load->model(array('transpoallowance','schedules'));
        $this->_ci->load->helper(array('my_datetime_helper'));
        $this->_status = $this->_ci->config->item('transpo_status');
        $this->_empty[] = array('No result(s) found.', '', '', '', '', '');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display filed transpo allowance.
     * 
     * @param int $employeenumber
     * @return mixed
     */
    public function filed_transpo($employeenumber)
    {
        $params = array('header' => $this->_filedtranspo, 'class' => array('table table-striped table-bordered dt-table'));
        $this->_ci->load->library('MY_table', $params, 'transpo_listing');
        
        $this->_ci->transpoallowance->EmployeeNumber = $employeenumber;
        $transpoallowance = $this->_ci->transpoallowance->get();

        if (isset($transpoallowance) && !empty($transpoallowance)) 
        {
            for ($i = 0; $i < count($transpoallowance); $i++)
            {
                $tabledata[] = array($this->_modaltranspo($transpoallowance[$i]->ID, 'transportation/viewfiledtranspo'), 
                    convert_date($transpoallowance[$i]->Datefiled, "M d, Y h:i A"),
                    convert_date($transpoallowance[$i]->Datefrom, "M d, Y"),
                    convert_date($transpoallowance[$i]->Dateto, "M d, Y"),
                    number_of_days($transpoallowance[$i]->Datefrom, $transpoallowance[$i]->Dateto),
                    $this->_status[$transpoallowance[$i]->Status],);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->transpo_listing->generate($tabledata);
            }
            else
            {
                return $this->_ci->transpo_listing->generate($this->_empty);
            }
        }
        else
        {
            return $this->_ci->transpo_listing->generate($this->_empty);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display filed transpo allowance (Team Lead view)
     * 
     * @param date $datefrom
     * @param date $dateto
     * @return mixed
     */
    public function filter_transpo($reportto, $datefrom = "", $dateto ="")
    {        
        if (isset($datefrom) && !empty($datefrom)) 
        {
            $datefrom .= ' 00:00:00';
        }
        else
        {
            $datefrom = date('Y-m-d 00:00:00');
        }
        
        if (isset($dateto) && !empty($dateto)) 
        {
            $dateto .= ' 23:59:59';
        }
        else
        {
            $dateto = date('Y-m-d 23:59:59');
        }
        
        $params = array('header' => $this->_filedtranspo, 'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'transpo_listing');
        
        $transpoallowance = $this->_ci->transpoallowance->filed_transpo($reportto, $datefrom, $dateto);
        
        if (isset($transpoallowance) && !empty($transpoallowance)) 
        {
            for ($i = 0; $i < count($transpoallowance); $i++)
            {
                $tabledata[] = array($this->_modaltranspo($transpoallowance[$i]->ID, 'filedtranspo/teamleadview'), 
                    convert_date($transpoallowance[$i]->Datefiled, "M d, Y h:i A"),
                    convert_date($transpoallowance[$i]->Datefrom, "M d, Y"),
                    convert_date($transpoallowance[$i]->Dateto, "M d, Y"),
                    number_of_days($transpoallowance[$i]->Datefrom, $transpoallowance[$i]->Dateto),
                    $this->_status[$transpoallowance[$i]->Status],);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->transpo_listing->generate($tabledata);
            }
            else
            {
                return $this->_ci->transpo_listing->generate($this->_empty);
            }
        }
        else
        {
            return $this->_ci->transpo_listing->generate($this->_empty);
        }
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Human Resources' view of filed transpo requests.
     * 
     * @param date $datefrom
     * @param date $dateto
     * @return mixed
     */
    public function hr_view($datefrom = "", $dateto = "")
    {
        
        if (isset($datefrom) && !empty($datefrom)) 
        {
            $datefrom .= ' 00:00:00';
        }
        else
        {
            $datefrom = date('Y-m-d 00:00:00');
        }
        
        if (isset($dateto) && !empty($dateto)) 
        {
            $dateto .= ' 23:59:59';
        }
        else
        {
            $dateto = date('Y-m-d 23:59:59');
        }
                
        $transpoallowance =$this->_ci->transpoallowance->hr_approval($datefrom, $dateto); 
        
        $params = array('header' => $this->_filedtranspo, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'transpo_listing');
        
        if (isset($transpoallowance) && !empty($transpoallowance)) 
        {
            for ($i = 0; $i < count($transpoallowance); $i++)
            {
                $tabledata[] = array($this->_modaltranspo($transpoallowance[$i]->ID, 'transpohrapproval/viewfiledtranspo'), 
                    convert_date($transpoallowance[$i]->Datefiled, "M d, Y h:i A"),
                    convert_date($transpoallowance[$i]->Datefrom, "M d, Y"),
                    convert_date($transpoallowance[$i]->Dateto, "M d, Y"),
                    number_of_days($transpoallowance[$i]->Datefrom, $transpoallowance[$i]->Dateto),
                    $this->_status[$transpoallowance[$i]->Status],);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->transpo_listing->generate($tabledata);
            }
            else
            {
                return $this->_ci->transpo_listing->generate($this->_empty);
            }
        }
        else
        {
            return $this->_ci->transpo_listing->generate($this->_empty);
        }
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Display the dates covered by the transpo allowance request
     * 
     * @param datetime $datefrom
     * @param datetime $dateto
     * @return mixed
     */
    public function transpo_coverage($employeenumber, $datefrom, $dateto)
    {
        $datefrom .= ' 00:00:00';
        $dateto .= ' 23:59:59';
        
        $transpocoverage = $this->_ci->schedules->transpo_coverage($employeenumber, $datefrom, $dateto);
        
        $params = array('header' => $this->_coverageheaders, 
            'class' => array('table table-striped table-bordered'));
        
        $this->_ci->load->library('MY_table', $params, 'coverage');
        
        if (isset($transpocoverage) && !empty($transpocoverage)) 
        {
            for ($i = 0; $i < count($transpocoverage); $i++)
            {
                $tabledata[] = array($transpocoverage[$i]->UserLogID,
                    convert_date($transpocoverage[$i]->SchedIn, "M d, Y h:i A"),
                    convert_date($transpocoverage[$i]->SchedOut, "M d, Y h:i A"),
                    convert_date($transpocoverage[$i]->TimeIn, "M d, Y h:i A"),
                    convert_date($transpocoverage[$i]->Timeout, "M d, Y h:i A"),
                    $this->_transpochk($transpocoverage[$i]->UserLogID, $transpocoverage[$i]->ApprovedTranspo));
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->coverage->generate($tabledata);
            }
            else
            {
                return $this->_ci->coverage->generate($this->_empty);
            }
        }
        else
        {
            
            return $this->_ci->coverage->generate($this->_empty);
        }
        
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Create a button for the modal pop up.
     * 
     * @param int $id
     * @param string $view
     */
    private function _modaltranspo($id, $view)
    {
        return form_button(array(
            'id' => $id,
            'name' => $id,
            'value' => $id,
            'content' => $id,
            'class' => 'btn btn-link',
            'data-toggle' => 'modal',
            'data-target' => '#updateTranspo',
            'data-remote' => site_url($view . '/' . $id)
        ));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Create checkbox for transpo approval.
     * 
     * @param int $id UserLogID
     * @param float $amount ApprovedTranspo
     * @return mixed
     */
    private function _transpochk($id, $amount)
    {
        $checked = FALSE;
        if (isset($amount) && $amount != 0)
        {
            $checked = TRUE;
        }
        return form_checkbox(array(
        'name' => 'chktranspo' . $id, 
        'id' => 'chktranspo' . $id,
        'value' => $id,
        'class' => 'form-control chkapprove',
        'checked' => $checked));
    }
}
