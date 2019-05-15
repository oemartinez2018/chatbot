<?php
//use App\Http\Controllers\BotManController;
use App\Http\Controllers\DialogController;

$botman = resolve('botman');

/*$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});*/
//$botman->hears('hola bot', BotManController::class.'@startConversation');

$botman->hears('(hola asistente|asistencia|Hola bot|Consulta)', DialogController::class.'@index');
       
$botman->fallback('App\Http\Controllers\FailChatController@index');
