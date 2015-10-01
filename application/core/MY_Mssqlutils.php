<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MS SQL database class
 */
class MY_Mssqlutils extends CI_Model {
    
    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';
    
    /**
     * Retrieve a single record
     * 
     * @param string $id
     */
    public function load($id)
    {
        $query = $this->db->get_where($this::DB_TABLE, array($this::DB_TABLE_PK => $id));        
        $this->_populate($query->row());
    }
    
    // --------------------------------------------------------------------

    /**
     * Retrieve multiple records
     * 
     * @return array
     */
    public function get()
    {
       $condition = $this->_setcondition();       
       $return_value = array();
       if (!empty($condition))
       {           
           $query = $this->db->get_where($this::DB_TABLE, $condition);           
           $return_value = $this->_populaterows($query);  
       }       
       return $return_value;
    }
        
    // --------------------------------------------------------------------
    
    /**
     * Execute SQL INSERT statement
     * 
     */
    public function insert()
    {        
        foreach ($this as $key => $value)
        {
            if ( $value != "" )
            {
                $insert[$key] = $value;
            }
        }
        
        $this->db->insert($this::DB_TABLE, $insert);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * This is a copy of the insert sql statement, but with unescaped 
     * parameters. Using the regular escaped query causes problems when 
     * table names contain periods(.)
     * 
     */
    public function unescape_insert()
    {
                foreach ($this as $key => $value)
        {
            if ( $value != "" )
            {
                $insert[$key] = $this->db->escape($value);
            }
        }
        
        $this->db->insert($this::DB_TABLE, $insert, FALSE);
        $this->{$this::DB_TABLE_PK} = $this->db->insert_id();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Execute SQL UPDATE statement
     * 
     * @param int $id
     */
    public function update($id)
    {
        foreach ($this as $key => $value)
        {
            if ($value != "")
            {
                $update[$key] = $this->db->escape($value);
            }
        }        
        
        $this->db->update($this::DB_TABLE, $update, array($this::DB_TABLE_PK => $id));        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Call stored procedure
     * 
     * @param string $function
     * @return array
     */
    public function call_function($function)
    {   
        $query = $this->db->query($function);
        $ret_val = $this->_populaterows($query);
        return $ret_val;
    }    
    
    // --------------------------------------------------------------------
    
    /**
     * Populate a single row
     * 
     * @param array $row
     */
    protected function _populate($row) 
    {
        if (!empty($row))
        {
            foreach ($row as $key => $value) 
            {              
              $this->$key = $value;
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Populate multiple rows
     * 
     * @param resource $queryresult
     * @param array $return_value
     * @return array
     */
    protected function _populaterows($queryresult)
    {
        $class = get_class($this); 
        $return_value = array();
        $rec = 0;                
        foreach($queryresult->result() as $row)
        {           
           $model = new $class;
           $model->_populate($row);           
           $return_value[$rec] = $model;
           $rec++;
        }        
        return $return_value;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set conditions for database query.
     * 
     * @return array
     */
    private function _setcondition()
    {
        foreach ($this as $key => $val)
        {
           if (isset($val) && !empty($val)) 
           {
               $condition[$key] = $val;
           }
        }
        return $condition;
    }
    
}
