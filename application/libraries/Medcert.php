<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medcert
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
    private $_tableheaders = array("Date Filed", 
                                "Name",
                                "From", 
                                "To", 
                                "# of days",
                                "Status", 
                                "Med Cert."
                                );    
    
    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->_ci = get_instance();  
        $this->_ci->load->model(array('employeeleavesfiled', 
            'employees', 
            'employeemedcert'));
        
        $this->_ci->load->helper(array('my_datetime_helper', 
            'my_leavestatus_helper', 
            'my_variable_helper'));        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Retrieve list of sick leaves that may or may not require medical 
     * certificates.
     * 
     * @param date $datefrom
     * @param date $dateto
     * @return string
     */
    public function sick_leaves($start = NULL, $end = NULL)
    {
        // If date parameters are empty set it to the current date.
        $sdate = iif(isset($start), $start, date('Y-m-d'));
        $edate = iif(isset($end), $end, date('Y-m-d'));
        
        $empty[] = array('No results found.', '', '', '', '', '', '');
        
        $params = array('header' => $this->_tableheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'medcerts');
        
        $sickleaves = $this->_ci->employeeleavesfiled->medcerts($sdate, $edate);
        
        if (isset($sickleaves) && !empty($sickleaves))
        {
            for ($i = 0; $i < count($sickleaves); $i++)
            {
                $tabledata[] = array(convert_date($sickleaves[$i]->DateFiled, 'M d, Y'),
                                     $sickleaves[$i]->Name, 
                                     convert_date($sickleaves[$i]->LeaveFrom, 'M d, Y'), 
                                     convert_date($sickleaves[$i]->LeaveTo, 'M d, Y'),
                                     $sickleaves[$i]->LeaveCount,
                                     leave_status($sickleaves[$i]),
                                     $this->_check_medcert($sickleaves[$i]->WithMedCert, $sickleaves[$i]->LeaveID));
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->medcerts->generate($tabledata);
            }
            else
            {
                return $this->_ci->medcerts->generate($empty);
            }
        }
        else
        {
            return $this->_ci->medcerts->generate($empty);
        }        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Check if employee submitted a medical certificate.
     * 
     * @param int $withmedcert
     * @param int $leaveid
     * @return string
     */
    private function _check_medcert($withmedcert, $leaveid)
    {
        $checked = FALSE;
        $checkval = "N";
        
        switch ($withmedcert)
            {
                case "Y" :
                    $checkval = "Y";
                    $checked = TRUE;
                    break;
                case "N" :
                    $checkval = "N";
                    break;
                default :
                    $checkval = "N";
            }
        
        return form_checkbox('medcert', $checkval, $checked, 'id="' . $leaveid . '" class="cert"');
    }
}
