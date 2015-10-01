<?php

/**
 * Checks wether a variable is set and not empty. Returns the variable if 
 * true.
 * 
 * @access public
 * @param mixed $variable
 * @return mixed
 */
if (!function_exists('is_variable_set'))
{
    function is_variable_set($variable) 
    {
        if (isset($variable) && !empty($variable))
        {
            return $variable;
        }
    }
}

// --------------------------------------------------------------------

/**
 * Shorthand for simple if/else conditions
 * 
 * @param mixed $condition
 * @param mixed $true
 * @param mixed $false
 * @return mixed 
 */
if (!function_exists('iif'))
{
    function iif($condition, $true, $false)
    {
        if ($condition)
        {
            return $true;
        }
        else
        {
            return $false;
        }
    }
}

// --------------------------------------------------------------------

/**
 * Format overtime. If any form of overtime is greater than 5 hours, 
 * 1 hour will be deducted from the said overtime.
 * 
 * @param int $overtime
 * @return int
 */
if (!function_exists('format_ot'))
{
    function format_ot($overtime)
    {
        // Prevent div by zero error.
        if (intval($overtime) != 0)
        {
            return sprintf('%.2f', iif(($overtime / 60) > 5, ($overtime - 60) / 60, ($overtime / 60)));
        }
        else
        {
            return sprintf('%.2f', $overtime);
        }
    }
}