<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function index() 
    {
        $this->load->helper(array('my_login_helper'));        
        $data = array();
        
        if ($this->input->post('employeenumber') && $this->input->post('password'))
        {
            $data['login_failed'] = employee_login($this->input);
            $data['employeenumber'] = $this->input->post('employeenumber');
            $data['password'] = $this->input->post('password');
        }
        
        $this->page_title('Webforms - Log in');
        $this->add_css(array('bootstrap.min', 'signin'));
        $this->add_js(array('jquery-1.11.3.min', 'bootstrap.min'));
        $this->display_template('login', $data);
    }
}
