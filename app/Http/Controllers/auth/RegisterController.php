<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {
        //dd($request);
        //dd($request->get('username')); obtener valores de post
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $validated = $request->validate([
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        //Crear un usuario
        User::create([
            'name' => $request->name,
            'username' =>  $request->username, 
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //Autenticar un usuario
        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        //otra forma de autenticar
        //$auth = $request()->only('email', 'password')
        ///Auth::attempt($auth);

        return redirect()->route('posts.index', [Auth::user()->username]);
    }
}
