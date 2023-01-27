<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $collection = Collection::where('username', 'gerbster')
            ->with('releases')
            ->with('releases.folder')
            ->first();

        // return view
        return response()->json($collection);
    }

    /**
     * @return View
     */
    public function show(): View
    {
        // return view
        return view('wantlist.show', [
        ]);
    }
}
