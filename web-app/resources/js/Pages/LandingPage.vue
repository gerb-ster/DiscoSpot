<template>
    <Head title="Landing Page" />
    <div>
        <h2 class="text-h4 font-weight-black text-purple">Get Synchronized!</h2>
        <p class="text-body-2 mb-4">
            Create Playlists in Spotify based on your Discogs Record Collection
        </p>
        <v-btn
            prepend-icon="mdi-rocket-launch"
            color="indigo-darken-2"
            size="large"
            :href="$route('auth.discogs.connect')"
        >
            Get Started!
        </v-btn>
    </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '../Shared/Layout.vue'

export default {
    components: {
        Head,
    },
    layout: Layout,
}
</script>
