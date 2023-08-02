<template>
    <Head title="Account Page" />
    <div>
        <v-container>
            <v-row no-gutters>
                <v-col align-self="center" cols="4" offset="4">
                    <form @submit.prevent="submit">
                        <v-text-field
                            v-model="auth.user.name"
                            :counter="10"
                            label="Name"
                            readonly="true"
                        ></v-text-field>

                        <v-text-field
                            v-model="auth.user.email"
                            :counter="10"
                            label="Email"
                            readonly="true"
                        ></v-text-field>

                        <v-text-field
                            v-model="auth.user.discogs_username"
                            :counter="10"
                            label="Discogs Username"
                            readonly="true"
                        ></v-text-field>

                        <v-select
                            id="account_type_id"
                            label="Account Type"
                            :items="accountTypes"
                            item-title="name"
                            item-value="id"
                            v-model="form.account_type_id"
                        ></v-select>

                        <v-btn
                            class="me-4"
                            type="submit"
                        >
                            submit
                        </v-btn>
                    </form>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script setup>

import { reactive } from "vue";
import { router } from '@inertiajs/vue3'

const form = reactive({
    account_type_id: null
})

function submit() {
    router.post(route('account.save'), form)
}

</script>
<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '../../Shared/Layout.vue'

export default {
    components: {
        Head,
    },
    layout: Layout,
    props: {
        auth: Object,
        accountTypes: Object,
    },
    methods: {
    }
}
</script>
