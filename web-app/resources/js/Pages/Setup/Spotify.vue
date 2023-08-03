<template>
    <Head :title="$t('spotifyConnect.pageTitle')" />
    <Layout menu-icon="mdi-spotify" :menu-title="$t('spotifyConnect.pageTitle')" :auth="auth">
        <div class="mt-3 ms-3 mb-5">
            <h2 class="text-h4 font-weight-black mb-3">{{ $t('spotifyConnect.title') }}</h2>
            <p class="text-body-2 mb-4">{{ $t('spotifyConnect.explanation') }}</p>
            <v-btn
                prepend-icon="mdi-spotify"
                color="light-green-lighten-1"
                size="large"
                elevation="0"
                :href="$route('auth.spotify.connect')"
            >
                {{ $t('spotifyConnect.connectToSpotify') }}
            </v-btn>
        </div>
    </Layout>
</template>
<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";

const props = defineProps(['auth']);

</script>
