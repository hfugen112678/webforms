<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employeeleaves extends MY_Mssqlutils 
{
    const DB_TABLE = "[Employee.Leaves]";
    const DB_TABLE_PK = 'EmployeeNumber';
    
    public $EmployeeNumber;
    public $Sick;
    public $Vacation;
    public $Emergency;
    public $Maternity;
    public $Paternity;
    public $AuditUser;
    public $AuditDate;
    
    public function update_leave_count($employeenumber, $leavetype, $leavecount, $audituser)
    {
        $auditdate = date('Y-m-d H:i:s');
        // Disable table trigger to allow update statement to execute.
        $this->db->query("ALTER TABLE [Employee.Leaves] DISABLE TRIGGER [UpdateEmployeeLeaves]");
        
        // This is the actual update        
        $this->db->query("UPDATE " . $this::DB_TABLE . " SET " . $leavetype . " = " . $leavecount . ", AuditUser = " . $audituser . ", AuditDate = '" . $auditdate . "' WHERE EmployeeNumber = " . $employeenumber);
        // $this->update($employeenumber);
                
        // Enable the table trigger.
        $this->db->query("ALTER TABLE [Employee.Leaves] ENABLE TRIGGER [UpdateEmployeeLeaves]");                
    }
    
    public function updateall($employeenumber)
    {
        // Disable table trigger to allow update statement to execute.
        $this->db->query("ALTER TABLE [Employee.Leaves] DISABLE TRIGGER [UpdateEmployeeLeaves]");
        
        $this->update($employeenumber);
        
        // Enable the table trigger.
        $this->db->query("ALTER TABLE [Employee.Leaves] ENABLE TRIGGER [UpdateEmployeeLeaves]");                
    }
}
