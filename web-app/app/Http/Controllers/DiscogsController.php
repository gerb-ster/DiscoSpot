<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\DiscogsApiClient;
use App\Service\DiscogsApiException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DiscogsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function getFolders(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $discogsApi = new DiscogsApiClient(
            $user->discogs_token,
            $user->discogs_secret
        );

        try {
            $response = $discogsApi->get("/users/{$user->discogs_username}/collection/folders");
            return response()->json($response['folders']);
        } catch (DiscogsApiException $e) {
            return response()->json($e->getMessage());
        } catch (GuzzleException $e) {
            return response()->json($e->getMessage());
        }
    }
}
