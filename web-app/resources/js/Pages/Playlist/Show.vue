<template>
    <Head :title="$t('playlistShow.pageTitle')" />
    <Confirm ref="confirmDelete"></Confirm>
    <Layout menu-icon="mdi-playlist-music-outline" :menu-title="$t('playlistShow.title')" :auth="auth" :bread-crumbs="breadCrumbs">
        <v-row>
            <v-col class="mt-3 ms-3" cols="12">
                <h2 class="text-h4 font-weight-black mb-4">{{ playlist.name }}</h2>
                <p class="text-body-2 mb-4" v-if="playlist.spotifyIdentifier">
                    <v-btn
                        prepend-icon="mdi-spotify"
                        variant="flat"
                        :href="'https://open.spotify.com/playlist/'+playlist.spotifyIdentifier"
                        color="light-green-lighten-1"
                        target="_blank"
                    >{{ $t('playlistShow.openPlaylistSpotify') }}</v-btn>
                </p>
                <p class="text-body-1 mb-2">
                    {{ t('playlistTypes.'+playlist.playlistTypeName) }}
                </p>
                <p class="text-caption mb-4">
                    {{ $t('playlistShow.lastSynced') }} @ {{ playlist.lastSyncTimestamp }}
                </p>
            </v-col>
            <v-col class="mt-3 mb-5 ms-3" cols="12">
                <v-btn
                    class="me-3"
                    prepend-icon="mdi-trash-can"
                    color="red-darken-1"
                    variant="flat"
                    @click="deleteItem(playlist)"
                >
                    {{ $t('playlistShow.deletePlaylistBtn') }}
                </v-btn>
                <v-btn
                    prepend-icon="mdi-sync-circle"
                    color="deep-orange-darken-1"
                    variant="flat"
                    :href="$route('playlist.sync', playlist.uuid)"
                    :disabled="playlist.isSynchronizing"
                    :loading="playlist.isSynchronizing"
                    class="active"
                >
                    {{ $t('playlistShow.syncPlaylistBtn') }}
                    <template v-slot:loader>
                        <v-progress-circular
                            :size="20"
                            :width="3"
                            indeterminate
                            class="me-2"
                        ></v-progress-circular>
                        {{ $t('playlistShow.busySynchronizing') }}
                    </template>
                </v-btn>
            </v-col>
        </v-row>
    </Layout>
</template>

<style lang="scss" scoped>
.v-btn.active .v-icon {
    transform: rotate(-180deg);
}
</style>

<script setup>

import { router, Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";
import { ref } from 'vue';
import {useI18n} from "vue-i18n";
import Confirm from "../../Components/Confirm.vue";

const props = defineProps(['playlist', 'auth']);
const confirmDelete = ref(null);

console.log(props.playlist);

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

function deleteItem (item) {
    confirmDelete.value.open(
        t('form.confirmRemoveDialogTitle'),
        t('playlistShow.confirmRemoveDialogText', {name: item.name}),{
            color: 'secondary'
        }
    ).then((confirm) => {
        if (confirm) {
            router.delete(route('playlist.destroy', item.uuid));
        }
    });
}

</script>

