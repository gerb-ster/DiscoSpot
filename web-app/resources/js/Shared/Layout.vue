<template>
    <v-app id="layout">
        <v-app-bar flat>
            <v-container class="fill-height d-flex align-center">
                <v-avatar
                    class="me-10 ms-4"
                    color="grey-darken-1"
                    size="32"
                ></v-avatar>
                <v-app-bar-title>DiscoSpot</v-app-bar-title>
                <v-menu v-if="auth.user">
                    <template v-slot:activator="{ props }">
                        <v-btn append-icon="mdi-account-circle" v-bind="props">{{ auth.user.name }}</v-btn>
                    </template>
                    <v-list>
                        <v-list-item :href="$route('account')">
                            <v-list-item-title>Account</v-list-item-title>
                        </v-list-item>
                        <v-list-item :href="$route('sign-out')">
                            <v-list-item-title>Sign Out</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-container>
        </v-app-bar>
        <v-main class="bg-grey-lighten-3">
            <v-container>
                <v-breadcrumbs :items="['Foo', 'Bar', 'Fizz']"></v-breadcrumbs>
                <flash-messages />
                <v-sheet
                    min-height="70vh"
                    rounded="lg"
                    class="d-flex mx-auto pa-6"
                >
                    <slot />
                </v-sheet>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import FlashMessages from './FlashMessages.vue'

export default {
    components: {
        Link,
        FlashMessages
    },
    props: {
        auth: Object
    },
}
</script>
