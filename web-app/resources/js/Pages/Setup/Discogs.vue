<template>
    <Head title="Connect to Discogs" />
    <div>
        <h2 class="text-h4 font-weight-black">Connect to Discogs</h2>
        <p class="text-body-2 mb-4">
            Grant DiscoSpot access to your Discogs account.
        </p>
        <v-btn
            prepend-icon="mdi-connection"
            color="indigo-darken-2"
            size="large"
            :href="$route('auth.discogs.connect')"
        >
            Connect to Discogs
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
