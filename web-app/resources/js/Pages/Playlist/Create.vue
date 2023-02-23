<template>
    <Head title="Playlist Create New" />
    <v-container>
        <v-row no-gutters>
            <v-col align-self="center" cols="4" offset="4">
                <v-sheet class="pa-2 ma-2">
                    <form @submit.prevent="submit">
                        <component
                            v-bind:is="currentStep"
                            @custom-change="handleCustomChange"
                        />
                    </form>
                </v-sheet>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '../../Shared/Layout.vue'
import CreateSelectType from '../../Components/Playlist/CreateSelectType.vue'
import CreateSelectFolder from '../../Components/Playlist/CreateSelectFolder.vue'
import CreateAddFilters from '../../Components/Playlist/CreateAddFilters.vue'
import CreateSummary from '../../Components/Playlist/CreateSummary.vue'

export default {
    components: {
        Head,
        CreateSelectType,
        CreateSelectFolder,
        CreateAddFilters,
        CreateSummary
    },
    layout: Layout,
    data: () => ({
        currentStep: 'CreateSelectType'
    }),
    props: {
        playlistTypes: Object,
    },
    methods: {
        handleCustomChange (component) {
            this.currentStep = component;
        }
    }
}
</script>

