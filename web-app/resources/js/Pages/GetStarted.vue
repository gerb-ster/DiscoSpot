<template>
    <Head :title="$t('getStarted.pageTitle')" />
    <Layout menu-icon="mdi-connection" :menu-title="$t('getStarted.title')" :auth="auth" :disableMenu="true">
        <div class="mt-3 ms-3 mb-5">
            <h2 class="text-h4 font-weight-black mb-3">{{ $t('getStarted.title') }}</h2>
            <p class="text-body-2 mb-4">{{ $t('getStarted.explanation') }}</p>
            <v-btn
                prepend-icon="mdi-connection"
                size="large"
                elevation="0"
                color="grey-darken-4"
                class="flex-grow-1 mt-5"
                :href="$route('auth.discogs.connect')"
            >
                {{ $t('getStarted.connectToDiscogs') }}
            </v-btn>
        </div>
    </Layout>
</template>
<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../Shared/Layout.vue";

const props = defineProps(['auth']);

</script>
