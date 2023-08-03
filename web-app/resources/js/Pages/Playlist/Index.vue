<template>
    <Head :title="$t('playlistIndex.pageTitle')" />
    <Layout menu-icon="mdi-playlist-music" :menu-title="$t('playlistIndex.title')" :auth="auth">
        <div class="mt-3 ms-3 mb-5 me-3">
            <p class="text-body-1 mb-4">{{ $t('playlistIndex.introText') }}</p>
            <v-row>
                <v-col
                    cols="4"
                    v-for="playlist in playlists"
                    :key="playlist.id"
                >
                    <v-card
                        class="pa-2 bg-lime-lighten-2"
                        :href="$route('playlist.show', playlist.uuid)"
                    >
                        <h6 class="text-h6">{{ playlist.name }}</h6>
                        <p class="text-body-1">{{ $t('playlistTypes.'+playlist.playlist_type.name) }}</p>
                        <p class="text-caption" v-if="playlist.last_sync">{{ $t('playlistShow.lastSynced') }} @ {{ playlist.last_sync }}</p>
                        <p class="text-caption" v-else>{{ $t('playlistShow.neverSynced') }}</p>
                    </v-card>
                </v-col>
            </v-row>
            <v-btn
                class="mt-10"
                prepend-icon="mdi-plus"
                color="green-darken-1"
                size="large"
                :href="$route('playlist.create')"
                variant="flat"
            >
                {{ $t('playlistIndex.createPlaylistBtn') }}
            </v-btn>
        </div>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";

const props = defineProps(['playlists', 'auth']);

</script>
