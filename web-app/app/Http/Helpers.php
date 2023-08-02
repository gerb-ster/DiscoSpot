<?php

if (! function_exists('csvToArray')) {
    function csvToArray($filename = '', $headers = null, $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            $headerSet = false;
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (empty($headers) && !$headerSet) {
                    $headers = $row;
                    $headerSet = true;
                    continue;
                }
                $data[] = array_combine($headers, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}

if (! function_exists('get_flag_for_locale')) {
    function get_flag_for_locale($locale): string
    {
        switch ($locale) {
            case "en":
                return "gb";
            case "nl":
                return "nl";
            case "fr":
                return "fr";
            default:
                return "sr";
        }
    }
}

if (! function_exists('camelize')) {
    function camelize($input, $separator = '_'): string
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }
}

if (! function_exists('bytes_to_human')) {
    function bytes_to_human($size): string
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}

if (! function_exists('show_progress_percentage')) {
    function show_progress_percentage($progress): string
    {
        return (int) (($progress['answered'] / $progress['total']) * 100);
    }
}

if (! function_exists('multi_array_key_exists')) {
    function multi_array_key_exists(array $path, array $array): bool
    {
        if (empty($path)) {
            return false;
        }
        foreach ($path as $key) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $array = $array[$key];
                continue;
            }

            return false;
        }

        return true;
    }
}

if (! function_exists('clear_zero_values')) {
    function clear_zero_values(&$array, $keys)
    {
        if (!is_array($array)) {
            return;
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $array) && $array[$key] === "0") {
                unset($array[$key]);
            }
        }
    }
}

if (! function_exists('clear_null_values')) {
    function clear_null_values(&$array, $keys)
    {
        if (!is_array($array)) {
            return;
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $array) && is_null($array[$key])) {
                unset($array[$key]);
            }
        }
    }
}

if (! function_exists('return_from_array')) {
    function return_from_array(&$array, $key)
    {
        if (!array_key_exists($key, $array)) {
            return false;
        }

        $value = $array[$key];
        unset($array[$key]);

        return $value;
    }
}
