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
                        ></v-text-field>

                        <v-text-field
                            v-model="auth.user.email"
                            :counter="10"
                            label="Email"
                        ></v-text-field>

                        <v-text-field
                            v-model="auth.user.discogs_username"
                            :counter="10"
                            label="Discogs Username"
                        ></v-text-field>

                        <v-text-field
                            v-model="auth.user.account.name"
                            :counter="10"
                            label="AccountType"
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
                </v-col>
            </v-row>
        </v-container>
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
    props: {
        auth: Object
    },
}
</script>
