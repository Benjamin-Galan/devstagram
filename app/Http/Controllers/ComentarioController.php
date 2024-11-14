<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    //validar
    public function store(Request $request, User $user, Post $post){
        $request->validate([
            'comentario' => 'required|max:255'
        ]);

        //almacenar el resultado
        Comentario::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
        ]);

        return back()->with('mensaje', 'Comentario realizado correctamente');
    }

    
}
