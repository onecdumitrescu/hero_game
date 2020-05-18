<?php

// Simple function for quick access to array stored config variables
if (!function_exists('config')) {
    function config($key = null, $fallback = null)
    {
        static $config;

        // If we don't have an instance of the config array get it
        if (is_null($config)) {
            $config = include 'config.php';
        }

        // If the config array doesn't exist or is invalid return the fallback value
        if (!arr_accessible($config)) {
            return value($fallback);
        }

        // If the key is null return the config array
        if (is_null($key)) {
            return $config;
        }

        // If the key is array accessible return the value
        if (arr_key_exists($config, $key)) {
            return $config[$key];
        }

        // If the config key doesn't contain a dot than return the fallback value or closure
        if (strpos($key, '.') === false) {
            return $config[$key] ?? value($fallback);
        }

        // Parse the array and get the final segment's value or return the fallback value
        $array = $config;
        foreach (explode('.', $key) as $segment) {
            if (arr_accessible($array) && arr_key_exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($fallback);
            }
        }

        return $array;
    }
}

if (!function_exists('is_valid_numeric_value')) {
    function is_valid_numeric_value($config, $key) {
        if (!is_array($config))
            return false;

        if (!array_key_exists($key, $config))
            return false;

        if (is_int($config[$key])) {
            return true;
        } else if (is_array($config[$key])) {
            if (count($config[$key]) !== 2)
                return false;

            if ($config[$key][0] !== (int)$config[$key][0])
                return false;

            if ($config[$key][1] !== (int)$config[$key][1])
                return false;

            if ($config[$key][0] > $config[$key][1])
                return false;

            return true;
        }

        return false;
    }
}

// Wrapper function for array validation
if (!function_exists('arr_accessible')) {
    function arr_accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

// Wrapper function for array key validation
if (!function_exists('arr_key_exists')) {
    function arr_key_exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

// Simple function that returns the value or the closure result
if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}