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
                    <v-stepper-header bg-color="transparent">
                        <template v-for="n in steps" :key="`${n}-step`">
                            <v-stepper-item
                                :complete="e1 > n"
                                :step="`Step {{ n }}`"
                                :value="n"
                            ></v-stepper-item>
                            <v-divider
                                v-if="n !== steps"
                                :key="n"
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

const form = useForm({
    name: null,
    typeId: null,
    selectedFolder: null,
    selectedList: null,
    filterItems: []
});

const props = defineProps(['playlistTypes', 'filterTypes', 'auth']);

const e1 = ref(1)
const steps = ref(4)

const disable = computed(() => {
    return e1.value === 1 ? 'prev' : e1.value === steps.value ? 'next' : undefined
})

//const steps = ref(['Step 1', 'Step 2', 'Step 3', 'Step 4']);

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

