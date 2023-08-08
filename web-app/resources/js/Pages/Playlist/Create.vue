<template>
    <Head :title="$t('playlistCreate.pageTitle')" />
    <Layout menu-icon="mdi-playlist-plus" :menu-title="$t('playlistCreate.title')" :auth="auth" :bread-crumbs="breadCrumbs">
        <v-form @submit.prevent="submit">
            <v-stepper
                v-model="e1"
                elevation="0"
                color="transparent"
            >
                <template v-slot:default="{ prev, next }">
                    <v-stepper-header>
                        <template v-for="(item, index) in steps" :key="`${index}-step`">
                            <v-stepper-item
                                :complete="e1 > index"
                                :step="index"
                                :value="index + 1"
                                :title="$t(item)"
                            ></v-stepper-item>
                            <v-divider
                                v-if="index < (steps.length - 1)"
                                :key="index"
                            ></v-divider>
                        </template>
                    </v-stepper-header>
                    <v-stepper-window>
                        <v-stepper-window-item :value="1">
                            <CreateStepOne :form="form" :playlistTypes="playlistTypes"></CreateStepOne>
                        </v-stepper-window-item>
                        <v-stepper-window-item :value="2">
                            <CreateSelectFolder :form="form" v-if="form.typeId === 1"></CreateSelectFolder>
                            <CreateSelectList :form="form" v-if="form.typeId === 2"></CreateSelectList>
                        </v-stepper-window-item>
                        <v-stepper-window-item :value="3">
                            <CreateAddFilters :form="form" :filterTypes="filterTypes"></CreateAddFilters>
                        </v-stepper-window-item>
                        <v-stepper-window-item :value="4">
                            <CreateSummary :form="form"></CreateSummary>
                        </v-stepper-window-item>
                    </v-stepper-window>
                    <v-stepper-actions
                        :disable="disable"
                        @click:prev="prev"
                        @click:next="next"
                    ></v-stepper-actions>
                </template>
            </v-stepper>
        </v-form>
    </Layout>
</template>

<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import Layout from '../../Shared/Layout.vue'
import {useI18n} from "vue-i18n";
import { computed, ref } from "vue";
import CreateStepOne from "../../Components/Playlist/CreateStepOne.vue";
import CreateSelectFolder from "../../Components/Playlist/CreateSelectFolder.vue";
import CreateAddFilters from "../../Components/Playlist/CreateAddFilters.vue";
import CreateSummary from "../../Components/Playlist/CreateSummary.vue";
import CreateSelectList from "../../Components/Playlist/CreateSelectList.vue";

const { t } = useI18n();

const form = useForm({
    name: null,
    typeId: null,
    selectedFolder: null,
    selectedList: null,
    filterItems: []
});

const props = defineProps(['playlistTypes', 'filterTypes', 'auth']);

const e1 = ref(1)
const steps = ref([
    'playlistCreate.steps.one',
    'playlistCreate.steps.two',
    'playlistCreate.steps.three',
    'playlistCreate.steps.four'
]);

const disable = computed(() => {
    return e1.value === 1 ? 'prev' : e1.value === steps.value.length ? 'next' : undefined
})

async function submit (event) {
    const results = await event;

    if(results.valid) {
        form.post(route('playlist.store'));
    }
}


const breadCrumbs = ref([{
    title: t('breadCrumbs.myPlaylists'),
    disabled: false,
    href: route('playlist.index'),
}, {
    title: t('breadCrumbs.createPlaylist'),
    disabled: true,
}]);

</script>

