<?php

use Src\Core\Utilities;


if (!function_exists('redirect')) {
    function redirect($url)
    {
        return Utilities::redirect($url);
    }
}
if (!function_exists('dump')) {
    function dump($data)
    {
        return Utilities::dump($data);
    }
}

if (!function_exists('dd')) {
    function dd($data)
    {
        dump($data);
        die;
    }
}

if(!function_exists('flashMessage')) {
    function flashMessage($message, $type = "success")
    {
        return Utilities::flashMessage($message, $type);
    }
}
