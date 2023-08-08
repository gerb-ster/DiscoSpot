<?php

namespace App\Policies;

use App\Models\AccountType;
use App\Models\Playlist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function viewAny(User $user): Response|bool
    {
        // everybody is allowed to view these resources
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function view(User $user, Playlist $playlist): Response|bool
    {
        // everybody is allowed to view these resources
        return $playlist->owner_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        switch ($user->account_type_id) {
            case AccountType::FREE:
                return Playlist::where('owner_id', $user->id)->count() < AccountType::MAX_PLAYLIST_FREE;

            case AccountType::BASIC:
                return Playlist::where('owner_id', $user->id)->count() < AccountType::MAX_PLAYLIST_BASIC;

            case AccountType::FULL:
                return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return bool
     */
    public function update(User $user, Playlist $playlist): bool
    {
        return $playlist->owner_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function delete(User $user, Playlist $playlist): Response|bool
    {
        return $playlist->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function restore(User $user, Playlist $playlist): Response|bool
    {
        return $playlist->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function forceDelete(User $user, Playlist $playlist): Response|bool
    {
        return $playlist->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return Response|bool
     */
    public function sync(User $user, Playlist $playlist): Response|bool
    {
        if (is_null($playlist->last_sync)) {
            return true;
        }

        switch ($user->account_type_id) {
            case AccountType::FREE:
                return Carbon::parse($playlist->last_sync)->diffInMinutes(Carbon::now()) >= AccountType::MAX_SYNC_INTERVAL_FREE;

            case AccountType::BASIC:
                return Carbon::parse($playlist->last_sync)->diffInMinutes(Carbon::now()) >= AccountType::MAX_SYNC_INTERVAL_BASIC;

            case AccountType::FULL:
                return true;
        }
    }
}
