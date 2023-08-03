<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistCreateRequest;
use App\Models\FilterType;
use App\Models\Playlist;
use App\Models\PlaylistType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Throwable;

class PlaylistController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return inertia('Playlist/Index', [
            'playlists' => Playlist::where('owner_id', Auth::user()->id)->with('playlistType')->get()
        ]);
    }

    /**
     * @param Playlist $playlist
     * @return Response
     */
    public function show(Playlist $playlist): Response
    {
        return inertia('Playlist/Show', [
            'playlist' => $playlist->load('playlistType')
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return inertia('Playlist/Create', [
            'playlistTypes' => PlaylistType::all(),
            'filterTypes' => FilterType::all()
        ]);
    }

    /**
     * @param PlaylistCreateRequest $request
     * @return Redirector|Application|RedirectResponse
     * @throws Throwable
     */
    public function store(PlaylistCreateRequest $request): Redirector|Application|RedirectResponse
    {
        $validated = $request->validated();

        // create a playlist
        $playlist = new Playlist();
        $playlist->name = $validated['name'];
        $playlist->playlist_type_id = $validated['typeId'];

        $discogsQueryData = [];

        switch ($validated['typeId']) {
            case PlaylistType::BASED_ON_FOLDER:
                $discogsQueryData['folder_id'] = $validated['selectedFolder'];
                break;

            case PlaylistType::BASED_ON_LIST:
                $discogsQueryData['list_id'] = $validated['selectedList'];
                break;
        }

        $discogsQueryData['filters'] = [];

        if (!empty($validated['filterItems'])) {
            foreach ($validated['filterItems'] as $filterItem) {
                $discogsQueryData['filters'][$filterItem['field']] = $filterItem['value'];
            }
        }

        $playlist->discogs_query_data = $discogsQueryData;
        $playlist->save();

        return redirect(route('playlist.index'))->with('success', 'Playlist created.');
    }

    /**
     * @param Playlist $playlist
     * @return Application|RedirectResponse|Redirector
     */
    public function sync(Playlist $playlist): Redirector|RedirectResponse|Application
    {
        Artisan::call('sync:start', [
            'playlist_uuid' => $playlist->uuid
        ]);

        return redirect(route('playlist.index'))->with('success', 'Playlist Synchronization Started.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Playlist $playlist
     * @return Redirector|RedirectResponse|Application
     */
    public function destroy(Playlist $playlist): Redirector|RedirectResponse|Application
    {
        // remove playlist
        $playlist->synchronizations()->delete();
        $playlist->delete();

        return redirect(route('playlist.index'))->with('success', 'adminResearchProjects.messages.deleted');
    }
}
