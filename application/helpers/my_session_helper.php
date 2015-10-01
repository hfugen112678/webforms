<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Session Helper
 *
 * Description:
 *
 * Checks wether the user has an active session. If no session is active, function 
 * redirects the user to the log in page.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/

/**
 * Checks if session is active
 * 
 * @access public
 * @param mixed
 * @return null
 */
if (!function_exists('session_check'))
{
    // function session_check($session_variable)
    function session_check()
    {       
        $_ci =& get_instance();
        if (!$_ci->session->userdata('employeenumber'))
        {
            // Exempt the login page from session check.
            if ($_ci->router->fetch_class() != 'login')
            {
                // Check if it's an ajax request.
                if (!$_ci->input->is_ajax_request())
                {
                    redirect("/");
                }
                else
                {
                    $_ci->load->view('errors/html/error_sessionexpired', NULL);
                    # redirect("/");
                    # echo $_ci->router->class . "5";
                }
            }
        }
    }
}