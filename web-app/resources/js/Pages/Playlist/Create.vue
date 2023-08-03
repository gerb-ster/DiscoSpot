<template>
    <Head :title="$t('playlistCreate.pageTitle')" />
    <Layout menu-icon="mdi-playlist-plus" :menu-title="$t('playlistCreate.title')" :auth="auth" :bread-crumbs="breadCrumbs">
        <v-form @submit.prevent="submit">
            <v-stepper
                bg-color="transparent"
                :items=steps
                :prev-text="$t('playlistCreate.prevBtn')"
                :next-text="$t('playlistCreate.nextBtn')"
            >
                <template v-slot:item.1>
                    <CreateStepOne :form="form" :playlistTypes="playlistTypes"></CreateStepOne>
                </template>
                <template v-slot:item.2>
                    <CreateSelectFolder :form="form" v-if="form.typeId === 1"></CreateSelectFolder>
                    <CreateSelectList :form="form" v-if="form.typeId === 2"></CreateSelectList>
                </template>
                <template v-slot:item.3>
                    <CreateAddFilters :form="form" :filterTypes="filterTypes"></CreateAddFilters>
                </template>
                <template v-slot:item.4>
                    <CreateSummary :form="form"></CreateSummary>
                </template>
            </v-stepper>
        </v-form>
    </Layout>
</template>

<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import Layout from '../../Shared/Layout.vue'
import {useI18n} from "vue-i18n";
import { ref} from "vue";
import CreateStepOne from "../../Components/Playlist/CreateStepOne.vue";
import CreateSelectFolder from "../../Components/Playlist/CreateSelectFolder.vue";
import CreateAddFilters from "../../Components/Playlist/CreateAddFilters.vue";
import CreateSummary from "../../Components/Playlist/CreateSummary.vue";
import CreateSelectList from "../../Components/Playlist/CreateSelectList.vue";

const form = useForm({
    name: null,
    typeId: null,
    selectedFolder: null,
    selectedList: null,
    filterItems: []
});

const props = defineProps(['playlistTypes', 'filterTypes', 'auth']);
const steps = ref(['Step 1', 'Step 2', 'Step 3', 'Step 4']);

async function submit (event) {
    const results = await event;

    if(results.valid) {
        form.post(route('playlist.store'));
    }
}

const { t } = useI18n();

const breadCrumbs = ref([{
    title: t('breadCrumbs.myPlaylists'),
    disabled: false,
    href: route('playlist.index'),
}, {
    title: t('breadCrumbs.createPlaylist'),
    disabled: true,
}]);

</script>

