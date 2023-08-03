<template>
    <Head :title="$t('playlistShow.pageTitle')" />
    <Layout menu-icon="mdi-playlist-music-outline" :menu-title="$t('playlistShow.title')" :auth="auth" :bread-crumbs="breadCrumbs">
        <v-row>
            <v-col class="mt-3 ms-3" cols="12">
                <h2 class="text-h4 font-weight-black">{{ playlist.name }}</h2>
                <p class="text-body-2 mb-4">
                    {{ $t('playlistShow.spotifyId') }}: {{ playlist.spotify_identifier }}
                </p>
                <p class="text-caption mb-4">
                    {{ $t('playlistShow.lastSynced') }} @ {{ playlist.last_sync }}
                </p>
            </v-col>
            <v-col class="mt-3 mb-3 ms-3" cols="12">
                <v-spacer></v-spacer>
                <v-btn
                    prepend-icon="mdi-sync-circle"
                    color="deep-orange-darken-1"
                    border
                    :href="$route('playlist.sync', playlist.uuid)"
                >
                    {{ $t('playlistShow.syncPlaylistBtn') }}
                </v-btn>
            </v-col>
        </v-row>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";
import { ref } from 'vue';
import {useI18n} from "vue-i18n";

const props = defineProps(['playlist', 'auth']);

const { t } = useI18n();

const breadCrumbs = ref([{
        title: t('breadCrumbs.myPlaylists'),
        disabled: false,
        href: route('playlist.index'),
    }, {
        title: props.playlist.name,
        disabled: true,
    }
]);

</script>

