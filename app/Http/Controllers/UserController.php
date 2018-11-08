<?php

namespace App\Http\Controllers;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $usuarios = User::join('roles','users.idrol','=','roles.roleid')
            ->select('users.userid','users.usuario','users.password','users.idrol','roles.role as rol')
            ->orderBy('users.userid', 'desc')->paginate(3);
        }
        else{
            $usuarios = User::join('roles','users.idrol','=','roles.roleid')
            ->select('users.userid','users.usuario','users.password','users.idrol','roles.role as rol')
            ->where('users.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('users.userid', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page'     => $usuarios->perPage(),
                'last_page'    => $usuarios->lastPage(),
                'from'         => $usuarios->firstItem(),
                'to'           => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

   public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = new User();
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->idrol = $request->idrol;
        $user->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $user = User::find($request->usuarioid);
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->idrol = $request->idrol;
        $user->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::find($request->userid);
        $user->delete();

    }

}
