<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disputes
{
    /**
     * Reference to CI 
     * 
     * @var object 
     */
    private $_ci;
    
    /**
     * Table headers
     * 
     * @var array 
     */
    private $_adjheaders = array("#", 
                                "Amount", 
                                "Type", 
                                "Occurred",
                                "Start", 
                                "End",
                                "Remarks", 
                                "Status");
    
    /**
     * Table headers for filed adjustments
     * 
     * @var array 
     */
    private $_forapprovalheaders = array("#", 
                                "Name", 
                                "Type", 
                                "Filed",
                                "Occurred",
                                "Start", 
                                "End");
    
    /**
     * Table headers for security confirmation
     * 
     * @var array 
     */
    private $_secheaders = array("#", 
                                "Name", 
                                "Type");
    
    /**
     * Adjustment tyoes
     * 
     * @var array 
     */
    private $_adjtypes;
    
    /**
     * Adjustment status
     * 
     * @var array 
     */
    private $_adjstatus;

    /**
     * Class constructor
     * 
     * @return	void
     */    
    public function __construct()
    {
        $this->_ci = get_instance();
        $this->_ci->load->model(array('employeedisputes', 
            'adjustment', 
            'representation'));  
        $this->_ci->load->helper(array('my_datetime_helper', 
            'string', 
            'my_variable_helper'));
        
        $this->_adjtypes = $this->_ci->config->item('adjustments');
        $this->_adjstatus = $this->_ci->config->item('adj_status');        
    }

    // --------------------------------------------------------------------
    
    /**
     * Display employee adjustments
     * 
     * @param int $employeenumber
     * @return mixed
     */
    public function adjustment_listing($employeenumber)
    {
        $params = array('header' => $this->_adjheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $empty[] = array('No results found.', '', '', '', '', '', '', '');
        
        $this->_ci->load->library('MY_table', $params, 'adjustments');
        
        $this->_ci->employeedisputes->EmployeeNumber = $employeenumber;
        
        $adjustments = $this->_ci->employeedisputes->get();
        
        if (isset($adjustments) && !empty($adjustments))
        {
            for ($i = 0; $i < count($adjustments); $i++)
            {
                $tabledata[] = array($this->_empdispute_modal('adjustments/view/', $adjustments[$i]->ID), 
                    sprintf('%01.2f', $adjustments[$i]->AdjustmentAmt), 
                    $this->_adjtypes[$adjustments[$i]->Type], 
                    convert_date($adjustments[$i]->DateOccurred, 'M d, Y'),
                    convert_date($adjustments[$i]->PeriodStart, 'M d, Y'), 
                    convert_date($adjustments[$i]->PeriodEnd, 'M d, Y'), 
                    strip_quotes(substr($adjustments[$i]->Remarks, 0, 12)), 
                    $this->_adjstatus[$adjustments[$i]->Status]);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->adjustments->generate($tabledata);
            }
            else
            {
                return $this->_ci->adjustments->generate($empty);
            }            
        }
        else
        {
            return $this->_ci->adjustments->generate($empty);
        }        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display the list of adjustments for teamlead/ manager approval.
     * 
     * @param int $reporto
     * @param date $datefrom
     * @param date $dateto
     * @return mixed
     */
    public function lead_approval($reporto, $start = NULL, $end = NULL)
    {        
        // If date parameters are empty set it to the current date.
        $sdate = iif(isset($start), $start, date('Y-m-d'));
        $edate = iif(isset($end), $end, date('Y-m-d'));
        
        $empty[] = array('No results found.', '', '', '', '', '', '');
        
        $params = array('header' => $this->_forapprovalheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'forapproval');
        
        $forapproval = $this->_ci->employeedisputes->for_approval($reporto, $sdate, $edate);
        
        if (isset($forapproval) && !empty($forapproval))
        {
            for ($i = 0; $i < count($forapproval); $i++)
            {
                $tabledata[] = array($this->_empdispute_modal('adjustmentlead/view/', $forapproval[$i]->ID), 
                    $forapproval[$i]->Name, 
                    $this->_adjtypes[$forapproval[$i]->Type],
                    convert_date($forapproval[$i]->AuditDate, 'M d, Y'),
                    convert_date($forapproval[$i]->DateOccurred, 'M d, Y'),                    
                    convert_date($forapproval[$i]->PeriodStart, 'M d, Y'), 
                    convert_date($forapproval[$i]->PeriodEnd, 'M d, Y'));
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->forapproval->generate($tabledata);
            }
            else
            {
                return $this->_ci->forapproval->generate($empty);
            }
        }
        else
        {
            return $this->_ci->forapproval->generate($empty);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the list of filed No Log In/ No Log out that have not yet been 
     * approved by the Team Leader. Security would need to atest the 
     * validity of the request through security footage.
     *      
     * @return string
     */
    public function security_approval()
    {
        $securitylist = $this->_ci->employeedisputes->security_verification();
        $tabledata = array();
        $empty[] = array('No results found.', '', '');
        $types = $this->_ci->config->item('adjustments');
        
        $params = array('header' => $this->_secheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'securities');
        
        if (isset($securitylist) && !empty($securitylist))
        {
            for ($i = 0; $i < count($securitylist); $i++)
            {
                $tabledata[] = array($this->_empdispute_modal('adjustmentsecurity/view/', $securitylist[$i]->ID), 
                    $securitylist[$i]->Name,
                    $types[$securitylist[$i]->Type],);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->securities->generate($tabledata);
            }
            else
            {
                return $this->_ci->securities->generate($empty);
            }
        }
        else
        {
            return $this->_ci->securities->generate($empty);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display list of adjustments for finance approval.
     * 
     * @param date $datefrom
     * @param date $dateto
     */
    public function finance_approval($start = NULL, $end = NULL)
    {
        // If date parameters are empty set it to the current date.
        $sdate = iif(isset($start), $start, date('Y-m-d'));
        $edate = iif(isset($end), $end, date('Y-m-d'));
        
        $empty[] = array('No results found.', '', '', '', '', '', '');
        
        $params = array('header' => $this->_forapprovalheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'forapproval');
        
        $forapproval = $this->_ci->employeedisputes->finance_approval($sdate, $edate);
        
        if (isset($forapproval) && !empty($forapproval))
        {
            for ($i = 0; $i < count($forapproval); $i++)
            {
                $tabledata[] = array($this->_empdispute_modal('adjustmentfinance/view/', $forapproval[$i]->ID), 
                    $forapproval[$i]->Name, 
                    $this->_adjtypes[$forapproval[$i]->Type],
                    convert_date($forapproval[$i]->AuditDate, 'M d, Y'),
                    convert_date($forapproval[$i]->DateOccurred, 'M d, Y'),                    
                    convert_date($forapproval[$i]->PeriodStart, 'M d, Y'), 
                    convert_date($forapproval[$i]->PeriodEnd, 'M d, Y'));
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->forapproval->generate($tabledata);
            }
            else
            {
                return $this->_ci->forapproval->generate($empty);
            }
        }
        else
        {
            return $this->_ci->forapproval->generate($empty);
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Copy details of dispute to the Adjustment table.
     * 
     * @param int $id
     * @return void
     */
    public function copy_to_adjustment($id)
    {
        $this->_ci->employeedisputes->load($id);
        $this->_ci->adjustment->EmployeeNumber = $this->_ci->employeedisputes->EmployeeNumber;
        $this->_ci->adjustment->AdjustmentAmt =  $this->_ci->employeedisputes->AdjustmentAmt;
        $this->_ci->adjustment->Type = $this->_ci->db->escape('ADJ');
        $this->_ci->adjustment->PeriodStart =  $this->_ci->db->escape($this->_ci->employeedisputes->PeriodStart);
        $this->_ci->adjustment->PeriodEnd =  $this->_ci->db->escape($this->_ci->employeedisputes->PeriodEnd);
        $this->_ci->adjustment->Remarks = $this->_ci->db->escape($this->_adjtypes[$this->_ci->employeedisputes->Type] . ' - ' . $this->_ci->employeedisputes->Remarks);
        $this->_ci->adjustment->AuditUser = $this->_ci->employeedisputes->AuditUser;
        $this->_ci->adjustment->AuditDate = $this->_ci->db->escape($this->_ci->employeedisputes->AuditDate);
        $this->_ci->adjustment->insert();
        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Copy details of dispute to the Representation table.
     * 
     * @param int $id
     * @return void
     */
    public function copy_to_representation($id)
    {
        $this->_ci->employeedisputes->load($id);
        $this->_ci->representation->EmployeeNumber = $this->_ci->employeedisputes->EmployeeNumber;
        $this->_ci->representation->RepresentationAmt =  $this->_ci->employeedisputes->TaxableAmt;
        $this->_ci->representation->Type = $this->_ci->db->escape('REP');
        $this->_ci->representation->PeriodStart =  $this->_ci->db->escape($this->_ci->employeedisputes->PeriodStart);
        $this->_ci->representation->PeriodEnd =  $this->_ci->db->escape($this->_ci->employeedisputes->PeriodEnd);
        $this->_ci->representation->Remarks = $this->_ci->db->escape($this->_adjtypes[$this->_ci->employeedisputes->Type] . ' - ' . $this->_ci->employeedisputes->Remarks);
        $this->_ci->representation->AuditUser = $this->_ci->employeedisputes->AuditUser;
        $this->_ci->representation->AuditDate = $this->_ci->db->escape($this->_ci->employeedisputes->AuditDate);
        $this->_ci->representation->insert();
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Create a link to the modal pop up
     * 
     * @param type $id
     * @return mixed
     */
    private function _empdispute_modal($link, $id)
    {
        return form_button(array(
            'name' => 'btn-adj-modal',
            'id' => $id,
            'value' => $id,
            'content' => $id,
            'class' => 'btn btn-link',
            'data-toggle' => 'modal',
            'data-target' => '#updateAdjustment',
            'data-remote' => site_url($link .  $id)
        )); 
    }    
}
