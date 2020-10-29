<?php

spl_autoload_register(
    function ($name){
        $file = plugin_dir_path(__FILE__) ."classes/" . $name . ".php";
        if (file_exists($file)) {
            include $file;
        }
    }
);