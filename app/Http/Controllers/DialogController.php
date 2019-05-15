<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversations\BotConversation;

class DialogController extends Controller
{
    function index($bot){
        $bot->startConversation(new BotConversation);
        
    }
}
