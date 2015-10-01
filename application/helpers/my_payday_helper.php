<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Payday Helper
 *
 * Description:
 *
 * Determines the start and end date of a pay period based on the pay day.
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
 * Return the start date of the pay period.
 */
if (!function_exists('cut_off_from'))
{
    function cut_off_from($payday)
    {       
        if ($payday == 10)
        {
            $datefrom = date('Y-m-d', strtotime(date('Y-m-16')." -1 month"));
        }
        else
        {
            $datefrom = date('Y-m-d', strtotime(date('Y-m-1')));
        }
        return $datefrom;
    }
}

/**
 * Return the end date of the pay period.
 */
if (!function_exists('cut_off_to'))
{
    function cut_off_to($payday)
    {
        if ($payday == 10)
        {
            $dateto = date("Y-m-t", strtotime("-1 month"));
        }
        else
        {
            $dateto = date('Y-m-d', strtotime(date('Y-m-15'))); 
        }
        return $dateto;
    }
}
