<?php

use App\Http\Response;
use App\Controller\Pages;

$obRouter->get('/',[
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->get('/sobre',[
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

$obRouter->get('/testimonies',[
function(){
        return new Response(200, Pages\Testimonies::getTestimonies());
    }
]);

$obRouter->post('/testimonies',[
function($request){
        return new Response(200, Pages\Testimonies::getTestimonies());
    }
]);

$obRouter->get('/variavel/{pagina}',[
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);