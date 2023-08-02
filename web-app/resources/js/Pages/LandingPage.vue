<template>
    <Head :title="$t('landingPage.title')" />
    <v-app id="layout">
        <v-main class="backgroundGradient">
            <div class="d-flex align-center flex-column">
                <v-card
                    width="400"
                    class="mt-10"
                >
                    <v-img
                        class="logoBackground"
                        height="200px"
                        src="/images/logo_placeholder.png"
                        contain
                    />
                    <v-card-title
                        class="text-h5 mb-4 mt-2"
                    >
                        {{ $t('landingPage.title') }}
                    </v-card-title>
                    <v-card-text
                        class="text-body-1"
                    >
                        {{ $t('landingPage.text') }}
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            prepend-icon="mdi-rocket-launch"
                            size="large"
                            variant="tonal"
                            class="flex-grow-1 mt-5"
                            :href="$route('auth.discogs.connect')"
                        >
                            {{ $t('landingPage.startBtn') }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </div>
        </v-main>
    </v-app>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'

</script>
