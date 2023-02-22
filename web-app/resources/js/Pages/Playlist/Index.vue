<template>
    <Head title="Playlist Overview" />
    <v-container class="align-start">
        <v-row class="" no-gutters >
            <v-col cols="4" v-for="playlist in playlists" :key="playlist.id">
                <v-card class="pa-2 ma-2 bg-amber-lighten-4" :href="$route('playlist.show', playlist.uuid)">
                    <h6 class="text-h6">{{ playlist.name }}</h6>
                    <p class="text-body-1">{{ playlist.spotify_identifier }}</p>
                    <p class="text-caption">{{ playlist.last_sync }}</p>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
    <v-container class="align-start">
        <v-btn
            prepend-icon="mdi-plus"
            color="green-darken-1"
            size="large"
            :href="$route('playlist.create')"
        >
            Create Playlist
        </v-btn>
    </v-container>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '../../Shared/Layout.vue'

export default {
    components: {
        Head,
    },
    layout: Layout,
    props: {
        playlists: Object,
    },
}
</script>

