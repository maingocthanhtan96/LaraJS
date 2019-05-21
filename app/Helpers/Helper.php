<?php

/**
 * @param $path
 * @param string $active
 * @return string
 * @author tanmnt
 */
function setActive($path, $active = 'active') {
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}
/**
 * slug text uppercase every string
 * @param $title
 * @param string $separator
 * @author tanmnt
 * @return string
 */
function str_slug_uppercase($title, $separator = '')
{
    $title = ucwords($title);
    $string = str_replace(array('[\', \']'), '', $title);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', $separator, $title);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), $separator, $string);
    return trim($string, '-');
}