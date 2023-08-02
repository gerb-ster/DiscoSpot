<template>
    <Head title="Connect to Spotify" />
    <div>
        <h2 class="text-h4 font-weight-black">Connect to Spotify</h2>
        <p class="text-body-2 mb-4">
            Grant DiscoSpot access to your Spotify account.
        </p>
        <v-btn
            prepend-icon="mdi-spotify"
            color="light-green-lighten-1"
            size="large"
            :href="$route('auth.spotify.connect')"
        >
            Click here to connect to Spotify
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
}
</script>
