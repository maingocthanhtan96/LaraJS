<?php

/**
 * @param $path
 * @param string $active
 * @return string
 * @author tanmnt
 */
function set_active($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}

/**
 * slug text uppercase every string
 * @param $title
 * @param string $separator
 * @return string
 * @author tanmnt
 */
function str_slug_uppercase($title, $separator = '')
{
    $title = ucwords($title);
    $string = str_replace(['[\', \']'], '', $title);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', $separator, $title);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    $string = preg_replace(['/[^a-z0-9]/i', '/[-]+/'], $separator, $string);
    return trim($string, '-');
}

/**
 * Find the position of the Xth occurrence of a substring in a string
 * @param $haystack
 * @param $needle
 * @param $number integer > 0
 * @return int
 */
function strpos_x($haystack, $needle, $number)
{
    if ($number == '1') {
        return strpos($haystack, $needle);
    } elseif ($number > '1') {
        return strpos($haystack, $needle, strpos_x($haystack, $needle, $number - 1) + strlen($needle));
    } else {
        return error_log('Error: Value for parameter $number is out of range');
    }
}

function get_between_content($content, $start, $end)
{
    $r = explode($start, $content);
    if (isset($r[1])) {
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function write_log_exception(\Exception $e = null)
{
    \Log::error('➤Message ex::' . $e->getMessage() . PHP_EOL . '#0 More exception::' . get_between_content($e->getTraceAsString(), '#0', '#10') . PHP_EOL . PHP_EOL);
}
