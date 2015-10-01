<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Employee log in.
 * 
 * @param CI_Input $input
 * @return string
 */
if (!function_exists('employee_login'))
{
    function employee_login(CI_Input $input)
    {
        $ci = get_instance();
        $ci->load->model('Employees');
        
        $result = $ci->Employees->employee_login($input->post('employeenumber'), 
                $input->post('password'));
        
        if (isset($result) && count($result) != 0)
        {
            $ci->session->set_userdata(array(
                    'employeenumber' => $result[0]->EmployeeNumber,
                    'campaignid' => $result[0]->Campaignid, 
                    'department' => $result[0]->Department, 
                    'positionid' => $result[0]->PositionID,));
            
            employee_redirect($result[0]->Department);
        }
        else
        {
            return "Employee is either inactive or does not exist.";
        }
    }
}

/**
 * Redirects employee to assigned page.
 * 
 * @param int $departmentid
 * @return void
 */
if (!function_exists('employee_redirect'))
{
    function employee_redirect($departmentid)
    {
        switch ($departmentid) {
            case 5:
                redirect('/adjustmentfinance');
                break;
            case 11:
                redirect('/adjustmentsecurity');
                break;
            case 6:
                redirect('/leavecredits');
                break;            
            default:
                redirect('/operations');
                break;
        }
    }
}

