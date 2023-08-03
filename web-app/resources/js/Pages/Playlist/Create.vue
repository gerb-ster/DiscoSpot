<template>
    <Head :title="$t('playlistCreate.pageTitle')" />
    <Layout menu-icon="mdi-playlist-plus" :menu-title="$t('playlistCreate.title')" :auth="auth" :bread-crumbs="breadCrumbs">
        <v-row no-gutters>
            <v-col cols="12">
                <v-sheet class="pa-2 ma-2">
                    <form>
                        <component
                            v-bind:is="currentStep.value"
                            v-bind="$props"
                            @custom-change="handleCustomChange"
                        />
                    </form>
                </v-sheet>
            </v-col>
        </v-row>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/inertia-vue3'
import Layout from '../../Shared/Layout.vue'
import CreateSelectType from '../../Components/Playlist/CreateSelectType.vue'
import CreateSelectFolder from '../../Components/Playlist/CreateSelectFolder.vue'
import CreateAddFilters from '../../Components/Playlist/CreateAddFilters.vue'
import CreateSummary from '../../Components/Playlist/CreateSummary.vue'
import {useI18n} from "vue-i18n";
import {ref} from "vue";

const props = defineProps(['playlistTypes', 'auth']);
const currentStep = ref('CreateSelectType');

const { t } = useI18n();

const breadCrumbs = ref([{
    title: t('breadCrumbs.myPlaylists'),
    disabled: false,
    href: route('playlist.index'),
}, {
    title: t('breadCrumbs.createPlaylist'),
    disabled: true,
}]);

function handleCustomChange (component) {
    currentStep.value = component;
}

</script>

