<template>
    <v-app id="layout">
        <v-main class="backgroundGradient">
            <v-container>
                <slot />
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
