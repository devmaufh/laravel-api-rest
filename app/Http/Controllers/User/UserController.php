<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\User;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios= User::all();
        return $this->showAll($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required|min:4',
            'email' => 'email|required|max:255|unique:users',
            'password' => 'required|confirmed|min:6'
        ];
        $this->validate( $request , $reglas);
        //Get all request fields
        $fields = $request->all();

        //Default fields
        $fields['password'] = bcrypt($request->password);
        $fields['verified'] = User::USUARIO_NO_VERIFICADO;
        $fields['verification_token'] = User::generarVerificationToken();
        $fields['admin'] = User::USUARIO_REGULAR;

        //Create user model
        $usuario = User::create($fields);

        return response()->json(['data' => $usuario], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->showOne($user);
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
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'confirmed|min:6',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR
        ];
        $this->validate($request, $rules);


        if($request->has('email') && $user->email != $request->email){
            if($user->email != $request->email){
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verification_token  =User::generarVerificationToken();
                $user->email = $request->email;
            }
        }
        if($request->has('name')){
            $user->name = $request->name;
        }
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if($request->has('admin')){
            if(!$user->esVerificado()){
                return response()->json(['error' => 'No puedes realizar esta accion, el usuario no esta verificado','code' => 409 ], 409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            return response()->json(['error' => 'Debes especificar un valor diferente para actualizar', 'code' => 409],409);
        }
        $user->save();
        return response()->json(['data' => $user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['data' => $user, 'code' => "200"], 200);
    }
}
