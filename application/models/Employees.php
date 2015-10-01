<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends MY_Mssqlutils 
{
    const DB_TABLE = 'Employees';
    const DB_TABLE_PK = 'EmployeeNumber';
    
    public $EmployeeNumber;
    public $FirstName;
    public $LastName;
    public $MiddleName;
    public $Address;
    public $Password;
    public $Campaignid;
    public $Department;
    public $PositionID;
    public $ReportTo;
    public $DateHired;	
    public $IsActive;
    public $IsDeleted;
    
    /**
     * Employee log in
     * 
     * @param char $employeenumber
     * @param varchar $password
     * @return array
     */
    public function employee_login($employeenumber, $password)
    {
        $this->db->select('EmployeeNumber, Campaignid, Department, PositionID');
        $this->db->from($this::DB_TABLE);
        $this->db->where("EmployeeNumber = '" . $employeenumber . "'");
        $this->db->where("Password = CONVERT(VARCHAR(MAX),HashBytes('MD5', '" 
                . $password . "' + CONVERT(VARCHAR(MAX),SALT)))");
        $this->db->where("IsDeleted = 0");
        $this->db->where("IsActive = 'A'");
        
        $query = $this->db->get();      
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }
    
    // --------------------------------------------------------------------
    
    /*
     * Get list of regular employees
     * 
     * @return mixed
     */
    public function regular_employees_with_leaves()
    {
        $columns = "a.EmployeeNumber, FirstName, LastName," 
                   . "CONVERT(VARCHAR,DateHired, 107) AS DateHired,"
                   . "CONVERT(VARCHAR,RegularizationDate, 107) AS RegularizationDate,"
                   . "b.Vacation, b.Emergency, b.Sick, b.Maternity, b.Paternity";
        
        $this->db->select($columns);
        $this->db->from($this::DB_TABLE . ' AS a');
        $this->db->join('[Employee.Leaves] AS b', 'a.EmployeeNumber = b.EmployeeNumber', 'left');
        $this->db->where("CONVERT(VARCHAR,RegularizationDate, 110) <> '01-01-1900'");
        $this->db->where("IsActive = 'A'");
        $this->db->where("a.EmployeeNumber NOT IN ('070003','090070')");
        $this->db->order_by('a.EmployeeNumber ASC');
        
        $query = $this->db->get();      
        
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }    
	
    // --------------------------------------------------------------------
    
    /**
     * Get the list of agents under a certain team leader.
     * 
     * @param string $employeenumber
     * @return array
     */
    
    public function report_to($employeenumber)
    {
        $this->ReportTo = $employeenumber;
        $this->IsActive = $this->db->escape('A');
        return $this->get();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get all active employees
     * 
     * @return array
     */
    public function activeemployees()
    {
        $this->IsActive = $this->db->escape('A');
        return $this->get();        
    }	
}
