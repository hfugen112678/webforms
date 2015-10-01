<?php
    
/**
 * Convert overtime from seconds to hours.
 * Please note that according to the finance department: For every 5 
 * hours of overtime 1 hour shall be deducted, i.e. only 4 hours shall be 
 * credited should an employee file a 5 hour over time.
 * 
 * Also, overtimes should be rounded off to whole numbers.
 * 
 * @access public
 * @param int
 * @return int
 */
if (!function_exists('format_overtime'))
{
    function format_overtime($overtime)
    {                
        // Convert seconds to hours.
        $formatted = $overtime / 60;               
        return floor($formatted);
    }
}

// --------------------------------------------------------------------

/**
 * Convert date to y-m-d from M dd, YYYY h:i:s
 * 
 * @access public
 * @param string $date
 * @param string $format
 * @return string
 */
if (!function_exists('convert_date'))
{
    function convert_date($date, $format)
    {
        $create_date = date_create($date);
        return date_format($create_date, $format);
    }
}

// --------------------------------------------------------------------

/**
 * Subtracts a specified number of days from a start date.
 * 
 * @access public 
 * @param int $no_of_days
 * @param date $start_date
 * @param string $format
 * @return date
 */
if (!function_exists('date_subtract'))
{
    function date_subtract($no_of_days,$start_date = NULL, $format = "Y-m-d")
    {
        if (isset($start_date))
        {
            $date = $start_date;
        }
        else
        {
            $date = date_create(date("Y-m-d"));
        }
        date_sub($date, date_interval_create_from_date_string($no_of_days . ' days'));
        return date_format($date, $format);
    }
}

// --------------------------------------------------------------------

if (!function_exists('add_date'))
{
    function add_date($no_of_days,$start_date = NULL, $format = "Y-m-d")
    {
        if (isset($start_date))
        {
            $date = $start_date;
        }
        else
        {
            $date = date_create(date("Y-m-d"));
        }
        date_add($date, date_interval_create_from_date_string($no_of_days . ' days'));
        return date_format($date, $format);
    }    
}

// --------------------------------------------------------------------

/**
 * Gets the number of days between two dates.
 * 
 * @access public 
 * @param datetime $datefrom
 * @param datetime $dateto
 * @return string
 */
if (!function_exists('number_of_days'))
{
    function number_of_days($datefrom, $dateto)
    {
        $date1 = date_create($datefrom);
        $date2 = date_create($dateto);
        $datediff = date_diff($date1,$date2);
        return format_date_diff($datediff);
    }
}

// --------------------------------------------------------------------

/**
 * Format an interval to show all existing components.
 * If the interval doesn't have a time component (years, months, etc)
 * That component won't be displayed.
 *
 * @param DateInterval $interval
 * @return string
 */
if (!function_exists('format_date_diff'))
{
    function format_date_diff(DateInterval $interval)
    {
        $result = "";
        if ($interval->y) { $result .= $interval->format("%y years "); }
        if ($interval->m) { $result .= $interval->format("%m months "); }
        if ($interval->d) { $result .= $interval->format("%d days "); }
        if ($interval->h) { $result .= $interval->format("%h hours "); }
        if ($interval->i) { $result .= $interval->format("%i minutes "); }
        if ($interval->s) { $result .= $interval->format("%s seconds "); }

        return $result;
    }
}

// --------------------------------------------------------------------

/**
 * Variation of the number_of_days function
 * 
 * @access public
 * @param date $datefrom 
 * @param date $dateto
 * @return int
 */
if (!function_exists('days_difference'))
{
    function days_difference($datefrom, $dateto)
    {
        return round(abs(strtotime($dateto) - strtotime($datefrom))/86400) + 1;
    }
}