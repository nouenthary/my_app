<?php

if (!function_exists('lang')) {
    /**
     * Greeting a person
     *
     * @param  string $person Name
     * @return string
     */
    function lang($text)
    {
        if(str_contains(__("language.$text"),'.')){
            return ucwords($text);
        }
        return __("language.$text");
    }
}


if (!function_exists('html')) {
    /**
     * Greeting a person
     *
     * @param  string $person Name
     * @return string
     */
    function html($tag, $text = '', $classname = '')
    {
        return "<$tag $classname >" . $text . "</$tag>";
    }
}

if (!function_exists('image')) {
    /**
     * Greeting a person
     *
     * @param  string $person Name
     * @return string
     */
    function image($img, $width = '25px'){
        $src = "/uploads/$img";
        $error = "this.src='/uploads/none.jpg'";
        return  "<img src='$src' width='$width' height='$width' onerror=".$error." > ";
    }
}
