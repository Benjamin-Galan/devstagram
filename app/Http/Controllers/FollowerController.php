<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function store(Request $request, User $user)
    {
        //guarda a quien sigue el usuario autenticado
        $user->followers()->attach(Auth::user()->id);

        return back();
    }

    public function destroy(User $user){
        $user->followers()->detach(Auth::user()->id);

        return back();
    }
}
