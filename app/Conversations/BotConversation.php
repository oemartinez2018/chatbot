<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class BotConversation extends Conversation
{

 public function saludo(){

    $question = Question::create("Hola! Elige una opción") //Saludamos al usuario
    ->fallback('Unable to ask question')
    ->callbackId('ask_reason')
    ->addButtons([
        Button::create('Ingresa tu cedula para verificar tu estado!')->value('who'),//Primera opcion, esta tendra el value who
        Button::create('¿Desea contactar a un agente?')->value('info'), //Segunda opcion, esta tendra el value info
    ]);
//Cuando el usuario elija la respuesta, se enviará el value aquí:
return $this->ask($question, function (Answer $answer) {
    if ($answer->isInteractiveMessageReply()) {
        if ($answer->getValue() === 'who') {//Si es el value who, contestará con este mensaje
            $this->ingreseNombre();/*say('Soy un chatbot, te ayudo a navegar por esta aplicación, 
            solo debes escribir "Hola bot"');*/
            //Si es el value info, llamaremos a la funcion options
        } else if ($answer->getValue() === 'info'){
            $this->options();
        }
    }
});
    

 }

   public function hello(){
        $question = Question::create("¡Hola! Elige una opción") //Saludamos al usuario
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('¿Quién eres?')->value('who'),//Primera opcion, esta tendra el value who
            Button::create('¿Qué puedes decirme?')->value('info'), //Segunda opcion, esta tendra el value info
        ]);
    //Cuando el usuario elija la respuesta, se enviará el value aquí:
    return $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'who') {//Si es el value who, contestará con este mensaje
                $this->say('Soy un chatbot, te ayudo a navegar por esta aplicación, 
                solo debes escribir "Hola bot"');
                //Si es el value info, llamaremos a la funcion options
            } else if ($answer->getValue() === 'info'){
                $this->options();
            }
        }
    });
    }

    public function ingreseNombre()
    {
        $this->ask('Ha seleccionado la opcion de Ingresar su cedula, despues de visualizar este mensaje por favor digite su numero de cedula ', function(Answer $answer) {
            $this->bot->userStorage()->save([
                'mobile' => $answer->getText(),
            ]);
            $pago = DB::select("Select Case when pago = 0 then 'No ha realizado su pago'
            Else 'Si ha realizado su pago'
            End as pago,
            nombre_cliente, created_at,
            promesa_pago,cedula_cliente,email,monto
            From customers 
            Where cedula_cliente = ? ",[$answer]);
                    
            /*DB::table('customers as c')
            ->select('c.id','c.nombre_cliente','cedula_cliente','c.numero_telefono','c.email','c.promesa_pago')
            ->where('c.cedula_cliente','=',$answer)
            ->get();*/
            
            $aux_pago = '';
            foreach($pago as $p){
                $aux_pago .=  
                 $p->nombre_cliente.
                 $p->cedula_cliente.
                 $p->email;
                 $p->promesa_pago;
                 $p->pago;
                 $p->monto;
            }

            $this->say(' Estimado '.$p->nombre_cliente.' Su fecha de pago es: '. Carbon::parse($p->promesa_pago)->format('d/m/Y'). ' y el  monto a pagar es : $'.$p->monto);
            $this->opcionesdespuesInfo();
        
        });
    }

    public function opcionesdespuesInfo(){
     
    $question = Question::create("¡Si ya realizo el pago, pongase en contacto con un agente de lo Contrario Elige una opción para reportar") //Saludamos al usuario
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('Bancos')->value('bancos'),//Primera opcion, esta tendra el value who
            Button::create('Red de Pagos')->value('redpago'), //Segunda opcion, esta tendra el value info
            Button::create('Contactar a un agente')->value('agente'), //Segunda opcion, esta tendra el value info
        ]);
    //Cuando el usuario elija la respuesta, se enviará el value aquí:
    return $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'bancos') {//Si es el value who, contestará con este mensaje
                $this->optionsBanco();/*say('Soy un chatbot, te ayudo a navegar por esta aplicación, 
                solo debes escribir "Hola bot"');*/
                //Si es el value info, llamaremos a la funcion options
            } else if ($answer->getValue() === 'redpago'){
                $this->options();
            }else if ($answer->getValue() === 'agente'){
                $this->options();
            }
        }
    });
}

public function optionsBanco(){
    $question = Question::create("Ha seleccionado la opcion para cancelar por medio de Bancos. A continuacion seleccione un banco")//le preguntamos al usuario que quiere saber
    ->fallback('Unable to ask question')
    ->callbackId('ask_reason')
    ->addButtons([
        Button::create('Banco Nacional')->value('hour'),//Opción de hora, con value hour
        Button::create('BacSanJose')->value('day'),//Opción de fecha, con value day
    ]);

    return $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'hour') {//Le muestra la hora la usuario si el value es hour
                $hour = date('H:i');
                $this->optionPagoBanco();
            }else if ($answer->getValue() === 'day'){//Le muestra la hora la usuario si el value es date
                $today = date("d/m/Y");
                $this->optionPagoBanco();
            }
        }
    });

}

public function optionPagoBanco(){
    $question = Question::create("Ha seleccionado la opcion para cancelar por medio de Bancos. A continuacion seleccione una opcion para pagar
    ")//le preguntamos al usuario que quiere saber
    ->fallback('Unable to ask question')
    ->callbackId('ask_reason')
    ->addButtons([
        Button::create('Credito')->value('credito'),//Opción de hora, con value hour
        Button::create('Debito')->value('debito'),//Opción de fecha, con value day
    ]);

    return $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'credito') {//Le muestra la hora la usuario si el value es hour
                $hour = date('H:i');
                $this->say('Ha seleccionado pagar usando credito');
            }else if ($answer->getValue() === 'debito'){//Le muestra la hora la usuario si el value es date
                $today = date("d/m/Y");
                $this->say('Ha seleccionado pagar usando debito ');
            }
            
        }
    });

}


public function options(){
        $question = Question::create("¿Qué quieres saber?")//le preguntamos al usuario que quiere saber
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('¿Qué hora es?')->value('hour'),//Opción de hora, con value hour
                Button::create('¿Qué día es hoy?')->value('day'),//Opción de fecha, con value day
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() === 'hour') {//Le muestra la hora la usuario si el value es hour
                        $hour = date('H:i');
                        $this->say('Son las '.$hour);
                    }else if ($answer->getValue() === 'day'){//Le muestra la hora la usuario si el value es date
                        $today = date("d/m/Y");
                        $this->say('Hoy es : '.$today);
                    }
                }
            });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->saludo();
    }
}
