<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $listasPropias = $user->listas()->get();
        $listasCompartidas = $user->sharedLists()->get();
        return view('home', compact('listasPropias', 'listasCompartidas'));
    }
}
