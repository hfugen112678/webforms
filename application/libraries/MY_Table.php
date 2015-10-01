<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Table extends CI_Table {
    
    private $my_template;
    
    /**
     * Class constructor
     *
     * @return	void
     */
    public function __construct( $params = "" ) {
        parent::__construct();          
        if ( !empty( $params['header'] ) ) {
            $this->set_heading( $params['header'] );
        }
        
        if ( !empty( $params['class'] ) ) {
            if ( !empty( $params['id'] ) ) {
                $this->set_table_style( $params['class'], $params['id'] );
            } else {
                $this->set_table_style( $params['class'] );
            }
        }
        
        if ( !empty( $params['cell_start'] ) ) {
            $this->set_table_cell( $params['cell_start'] );
        }                
        $this->set_template( $this->my_template );
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set the table stye and table id
     * 
     * @param array $cssclass
     * @param string $id
     * @void
     */
    private function set_table_style($cssclass = "", $id = "")
    {
        $tableclass = "";
        if ( !empty( $cssclass ) ) {
            for( $i = 0; $i < count( $cssclass ); $i++ ) {
                $tableclass .= $cssclass[$i] . " ";
            }
            if ( isset( $id ) ) {
                $this->my_template['table_open'] = '<table id="' . $id . '" class="' . trim( $tableclass ) . '">';
            } else {
                $this->my_template['table_open'] = '<table class="' . trim( $tableclass ) . '">';
            }
        } else {
           if ( isset( $id ) ) {
               $this->my_template['table_open'] = '<table id="' . $id . '">';
           } else {
                $this->my_template['table_open'] = '<table>';
           }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set style for table cell
     * 
     * @param array $cell_start
     * @return void
     */
    private function set_table_cell( $cell_start = "" )
    {
        $cellstart = "";
        if ( !empty( $cell_start ) ) {
            for ( $i = 0; $i < count( $cell_start ); $i++ ) {
                $cellstart .= $cell_start[$i] . " ";
            }
            $this->my_template['cell_start'] = '<td class="' . trim( $cellstart ) . '">';
            $this->my_template['cell_alt_start'] = '<td class="' . trim( $cellstart ) . '">';
        } else {
            $this->my_template['cell_start'] = '<td>';
            $this->my_template['cell_alt_start'] = '<td>';
        }
    }
}
