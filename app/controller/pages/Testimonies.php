<?php

namespace App\Controller\Pages;
use App\Utils\View;

class Testimonies extends Page {
    public static function getTestimonies(){
        $content =  View::render('pages/testimonies', [
            'content' => 'Mundo'
        ]);
        return parent::getPage('Ola mundo', $content);
    }
}