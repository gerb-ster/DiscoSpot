<template>
    <Head :title="$t('playlistIndex.pageTitle')" />
    <Layout menu-icon="mdi-playlist-music" :menu-title="$t('playlistIndex.title')" :auth="auth">
        <v-row class="" no-gutters >
            <v-col cols="4" v-for="playlist in playlists" :key="playlist.id">
                <v-card class="pa-2 ma-2 bg-amber-lighten-4" :href="$route('playlist.show', playlist.uuid)">
                    <h6 class="text-h6">{{ playlist.name }}</h6>
                    <p class="text-body-1">{{ playlist.spotify_identifier }}</p>
                    <p class="text-caption">{{ playlist.last_sync }}</p>
                </v-card>
            </v-col>
        </v-row>
        <v-btn
            prepend-icon="mdi-plus"
            color="green-darken-1"
            size="large"
            :href="$route('playlist.create')"
        >
            Create Playlist
        </v-btn>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";

const props = defineProps(['playlists', 'auth']);

</script>
