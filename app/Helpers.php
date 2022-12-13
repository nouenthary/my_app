<?php

if (!function_exists('lang')) {
    /**
     * Greeting a person
     *
     * @param $text
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
     * @param $tag
     * @param string $text
     * @param string $classname
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
     * @param $img
     * @param string $width
     * @return string
     */
    function image($img, $width = '25px'){
        $src = "/uploads/$img";
        $error = "this.src='/uploads/none.jpg'";
        return  "<img src='$src' width='$width' height='$width' onerror=".$error." > ";
    }
}

if (!function_exists('get_current_date')) {
    /**
     * Greeting a person
     *
     * @return string
     */
    function get_current_date(){
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('guid4')) {
    /**
     * Greeting a person
     *
     * @return string
     */
    function guid4()
    {
        $data = openssl_random_pseudo_bytes(16);

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}


