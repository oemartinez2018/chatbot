<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function index(){

    $pago = DB::table('customers as c')
    ->select('c.id','c.nombre_cliente','cedula_cliente','c.numero_telefono','c.email','c.promesa_pago')
    ->where('c.cedula_cliente','=','12345678')
    ->get();
    return['pago'=>$pago];

    }
}
