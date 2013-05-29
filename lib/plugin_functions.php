<?php

if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }
}

if (!function_exists('endsWith')) {
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}

if (!function_exists('contains')) {
    function contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }
}