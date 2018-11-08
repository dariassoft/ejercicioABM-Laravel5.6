<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $pedidos = Pedido::join('clientes','pedidos.clienteid', '=', 'clientes.clienteid')
            ->select('pedidos.pedidoid','pedidos.clienteid','pedidos.descripcion','clientes.name')
            ->orderBy('pedidos.pedidoid','desc')->paginate(2);
        }
        else{
            $pedidos = Pedido::join('clientes','pedidos.clienteid', '=', 'clientes.clienteid')
                ->select('pedidos.pedidoid','pedidos.clienteid','pedidos.descripcion','clientes.name')
                ->where('pedidos.'.$criterio, 'like', '%' . $buscar . '%')
                ->orderBy('pedidos.pedidoid','desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total' => $pedidos->total(),
                'current_page'  => $pedidos->currentPage(),
                'per_page'  => $pedidos->perPage(),
                'last_page' =>  $pedidos->lastPage(),
                'from' =>  $pedidos->firstItem(),
                'to' =>  $pedidos->lastItem(),
            ],
            'pedidos'  => $pedidos
        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $pedido = new Pedido();
        $pedido->clienteid = $request->clienteid;
        $pedido->descripcion = $request->descripcion;
        $pedido->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $pedido = Pedido::find($request->pedido_id);
        $pedido->clienteid = $request->cliente_id;
        $pedido->descripcion = $request->descripcion;
        $pedido->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $pedido = Pedido::find($request->pedidoid);
        $pedido->delete();

    }
}
