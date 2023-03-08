<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class Page {

    private static function getHeader(){
        return View::render('pages/header');
    }
    
    private static function getFooter(){
        return View::render('pages/footer');
    }

    private static function getNav(){
        return View::render('pages/nav');
    }

    private static function getContent($content){
        $content = substr($content, 0, strpos($content,'@desc'));
        return $content;
    }

    private static function getDescription($content){
        $description = explode('@desc ', $content);
        return $description[1];
    }

    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'nav' => self::getNav(),
            'description' => self::getDescription($content),
            'content' => self::getContent($content),
            'footer' => self::getFooter()
        ]);
    }
}