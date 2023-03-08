<?php

require __DIR__.'/vendor/autoload.php';

use App\Http\Roteador;
use App\Utils\View;
use App\Utils\Enviorement;
use App\Utils\DB;

Enviorement::load(__DIR__);
View::init([
    'URL' => getenv('URL')
]);
$database = new DB('depoimentos');
$result = $database->get();

$obRouter = new Roteador(getenv('URL'));

include __DIR__.'/rotas/pages.php';

$obRouter->run()->sendResponse();