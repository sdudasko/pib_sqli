<?php

if (!function_exists('mixer')) {
    /**
     * @param $path
     * @param string $manifestDirectory
     *
     * @return \Illuminate\Support\HtmlString|string
     * @throws Exception
     */
    function mixer($path, $manifestDirectory = '')
    {
        if (app()->runningUnitTests()) {
            return '';
        }

        return mix($path, $manifestDirectory);
    }
}