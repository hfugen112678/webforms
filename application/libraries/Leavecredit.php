<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavecredit 
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
    private $_leaveheaders = array("#", 
                                "First Name", 
                                "Last Name",
                                "Vacation", 
                                "Emergency",
                                "Sick", 
                                "Maternity", 
                                "Paternity");    
    
    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_ci = get_instance();
        $this->_ci->load->model(array('employees', 'employeeleaves'));        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display list of regular employees with corresponding leave credits.
     * 
     * @return mixed
     */
    public function employees_with_leaves()
    {
        $params = array('header' => $this->_leaveheaders, 
            'class' => array('table table-striped table-bordered dt-table'));
        
        $this->_ci->load->library('MY_table', $params, 'regemployees');
        
        $empleaves = $this->_ci->employees->regular_employees_with_leaves();
        
        if (isset($empleaves) && !empty($empleaves))
        {
            for ($i = 0; $i < count($empleaves); $i++)
            {
                $tabledata[] = array($this->_leavemodal($empleaves[$i]->EmployeeNumber),
                    $empleaves[$i]->FirstName,
                    $empleaves[$i]->LastName,
                    $empleaves[$i]->Vacation,
                    $empleaves[$i]->Emergency,
                    $empleaves[$i]->Sick,
                    $empleaves[$i]->Maternity,
                    $empleaves[$i]->Paternity,);
            }
            
            if (isset($tabledata) && !empty($tabledata))
            {
                return $this->_ci->regemployees->generate($tabledata);
            }
            else
            {
                $this->_ci->leave_listings->add_row(array('data' => 'No results found.', 
                'colspan' => count($this->_leaveheaders)));
                return $this->_ci->regemployees->generate();
            }
        }
        else
        {
            $this->_ci->leave_listings->add_row(array('data' => 'No results found.', 
                'colspan' => count($this->_leaveheaders)));
            return $this->_ci->regemployees->generate();
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Construct button link.
     * 
     * @param string $employeenumber
     * @return mixed
     */
    private function _leavemodal($employeenumber)
    {
        $employeenumber = trim($employeenumber);
        return form_button(array(
            'name' => 'btn-ot-modal',
            'id' => $employeenumber,
            'value' => $employeenumber,
            'content' => $employeenumber,
            'class' => 'btn btn-link',
            'data-toggle' => 'modal',
            'data-target' => '#adjustLeave',
            'data-remote' => site_url("leavecredits/view/" . $employeenumber)
        ));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Create textbox for leave credits.
     * 
     * @param string $leavecredit
     * @param string $type
     * @return mixed
     */
    private function _inputcredits($leavecredit, $type)
    {
        return form_input(array(
            'id' => $type,
            'name' => $type,
            'value' => $leavecredit, 
            'class' => 'form-control',
            'required' => 'required'
        ));
    }    
}
