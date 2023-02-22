<template>
    <div>
        <Head title="Playlist Create New" />
        <form @submit.prevent="submit">
            <v-text-field
                :counter="64"
                label="Name"
            ></v-text-field>

            <v-btn
                class="me-4"
                type="submit"
            >
                submit
            </v-btn>

            <v-btn @click="handleReset">
                clear
            </v-btn>
        </form>
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

