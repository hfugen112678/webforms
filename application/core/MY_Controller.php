<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * Extends the CI_Controller super class with additional methods 
 * such as adding css and javascript files.
 */
class MY_Controller extends CI_Controller 
{
    
    private $_data = array();
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Adds css to the header template
     * 
     * @param array $css
     */
    public function add_css($css)
    {
        $this->_data['stylesheet'] = $css;
    }
    
    // --------------------------------------------------------------------
	
	/**
     * Print media type
     * 
     * @param array $css
     */
    public function add_print_css($css)
    {
        $this->_data['print_css'] = $css;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Adds javascript to the header template
     * 
     * @param array $javascript
     */
    public function add_js($javascript)
    {
        $this->_data['javascript'] = $javascript;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Adds javascript to the footer template
     * 
     * @param array $javascript
     */
    public function add_js_footer($javascript)
    {
        $this->_data['js_footer'] = $javascript;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set page title
     * 
     * @param string $title
     */
    public function page_title($title)
    {
        $this->_data['title'] = $title;
    }

    // --------------------------------------------------------------------
    
    /**
     * Add optional body css class
     * 
     * @param string $class
     */
    public function body_class($class)
    {
        $this->_data['bodyclass'] = $class;
    }

    // --------------------------------------------------------------------
    
    /**
     * Display template
     * 
     * @param string $template
     * @param array $template_data
     */
    public function display_template($template, $template_data = NULL)
    {
        // Append the directory name to the template.
        $tpl = $this->router->class . '/' . $template;
        // Header
        $this->load->view($this->config->item('header'), $this->_data);
        // Main content
        $this->load->view($tpl, $template_data);
        // Footer
        $this->load->view($this->config->item( 'footer' ), NULL);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Render master page. The master template is based on the CSS Boostrap
     * template Ace.
     * URL : http://responsiweb.com/themes/preview/ace/1.3.3/
     * 
     * @param string $template
     * @param array $template_data
     * @return void
     */
    public function display_master($template, $template_data = NULL)
    {
        // Append the directory name to the template.
        $this->_data['tpl'] = $this->router->class . '/' . $template;
        if (isset($template_data))
        {
            $this->_data['template_data'] = $template_data;
        }
        
        $this->load->view($this->config->item('master'), $this->_data);
    }
}
// END MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/Controller.php */