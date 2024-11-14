<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return back()->with('mensaje', 'Crendenciales incorrectas');
        }

        // Obtener el username del usuario autenticado
        $username = Auth::user()->username;

        // Redirigimos a la ruta posts.index, pasando el username
        return redirect()->route('posts.index', ['user' => $username]);
    }
}
