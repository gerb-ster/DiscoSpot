<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * @param Request $request
     * @return
     */
    public function index(Request $request): Factory|View|Application
    {
        // return view
        return view('playlist.index', [
        ]);
    }

}
