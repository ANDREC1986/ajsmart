<?php

namespace App\Utils;

class View{

    private static $vars = [];

    public static function init($vars = []){
        self::$vars = $vars;

    }

    private static function getContetView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    public static function render($view, $vars = []){
        $vars = array_merge(self::$vars,$vars);
        $contentview = self::getContetView($view);
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);
        return str_replace($keys, array_values($vars), $contentview);

    }
}