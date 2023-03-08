<?php

namespace App\Controller\Pages;
use App\Utils\View;

class About extends Page {
    public static function getAbout(){
        $content =  View::render('pages/about', [
            'content' => 'Mundo'
        ]);
        return parent::getPage('Ola Sobre', $content);
    }
}