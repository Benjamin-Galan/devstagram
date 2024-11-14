<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Modifiers\FillModifier;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array{
        return [
            new Middleware('auth', except: ['show', 'index']),
        ];
    }

    //importa el modelo de usuario para traer su información
    public function index(User $user){
        //obtiene los resultados de la base de datos (get()todos  paginate()paginados) 
        $posts = Post::where('user_id', $user->id)->latest()->paginate(4);
        
        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => Auth::user()->id
        // ]);

        //otra forma de crear registros

        // $post = new Post();
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = Auth::user()->id;
        // $post->save();

        //crear registros con las relaciones
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('posts.index', Auth::user()->username);
    }

    public function show(User $user, Post $post){
        return view('posts.show', [
            'user' => $user,
            'post' => $post
        ]);
    }

    public function destroy(Post $post){
       Gate::authorize('delete', $post);
       $post->delete();

       $imagen_path = public_path('uploads/' . $post->imagen);

       if(File::exists($imagen_path)){
            unlink($imagen_path);
       }

       return redirect()->route('posts.index', Auth::user()->username);
    }
}
