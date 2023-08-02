<template>
    <div>
        <Head title="Playlist Details" />
        <h2 class="text-h4 font-weight-black">{{ playlist.name }}</h2>
        <p class="text-body-2 mb-4">
            SpotifiyId: {{ playlist.spotify_identifier }}
        </p>
        <p class="text-caption mb-4">
            Last Synced @ {{ playlist.last_sync }}
        </p>
        <v-btn
            prepend-icon="mdi-sync-circle"
            color="deep-orange-darken-1"
            size="large"
            :href="$route('playlist.sync', playlist.uuid)"
        >
            Start Synchronization
        </v-btn>
    </div>
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
        playlist: Object,
    },
}
</script>

