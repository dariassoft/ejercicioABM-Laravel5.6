<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
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
            //$clientes = Clientes::orderBy('clienteid','desc')->paginate(2);
            $clientes = DB::table('clientes')
                ->select('clientes.clienteid','clientes.name', DB::raw('COUNT(pedidos.clienteid) as cantpedidos'))
                ->leftjoin('pedidos','pedidos.clienteid','=','clientes.clienteid')
                ->orderBy('clientes.clienteid','desc')
                ->groupBy('clientes.clienteid')
                ->paginate(2);
        }
        else{
            //$clientes = Clientes::where($criterio, 'like', '%' . $buscar . '%')->orderBy('clienteid','desc')->paginate(2);
            $clientes = DB::table('clientes')
                ->select('clientes.clienteid','clientes.name', DB::raw('COUNT(pedidos.clienteid) as cantpedidos'))
                ->leftjoin('pedidos','pedidos.clienteid','=','clientes.clienteid')
                ->where('clientes.'.$criterio, 'like', '%' . $buscar . '%')
                ->orderBy('clientes.clienteid','desc')
                ->groupBy('clientes.clienteid')
                ->paginate(2);
        }

        return [
            'pagination' => [
                'total' => $clientes->total(),
                'current_page'  => $clientes->currentPage(),
                'per_page'  => $clientes->perPage(),
                'last_page' =>  $clientes->lastPage(),
                'from' =>  $clientes->firstItem(),
                'to' =>  $clientes->lastItem(),
            ],
            'clientes'  => $clientes
        ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $cliente = new Clientes();
        $cliente->name = $request->name;
        $cliente->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cliente = Clientes::find($request->clienteid);
        $cliente->name = $request->name;
        $cliente->save();
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
        $cliente = Clientes::find($request->clienteid);
        $cliente->delete();

    }

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');
        $clientes = Clientes::all();
        return ['clientes' => $clientes];
    }

}
