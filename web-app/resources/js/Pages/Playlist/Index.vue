<template>
    <Head :title="$t('playlistIndex.pageTitle')" />
    <Layout menu-icon="mdi-playlist-music" :menu-title="$t('playlistIndex.title')" :auth="auth">
        <v-row no-gutters >
            <v-col cols="4" v-for="playlist in playlists" :key="playlist.id">
                <v-card class="pa-2 ma-3 bg-amber-lighten-4" :href="$route('playlist.show', playlist.uuid)">
                    <h6 class="text-h6">{{ playlist.name }}</h6>
                    <p class="text-body-1">{{ playlist.spotify_identifier }}</p>
                    <p class="text-caption">{{ playlist.last_sync }}</p>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" class="ms-3 mb-4 mt-3">
                <v-btn
                    prepend-icon="mdi-plus"
                    color="green-darken-1"
                    size="large"
                    :href="$route('playlist.create')"
                >
                    {{ $t('playlistIndex.createPlaylistBtn') }}
                </v-btn>
            </v-col>
        </v-row>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";

const props = defineProps(['playlists', 'auth']);

</script>
