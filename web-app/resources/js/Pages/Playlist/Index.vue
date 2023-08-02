<template>
    <Head :title="$t('playlistIndex.pageTitle')" />
    <div class="d-flex align-center flex-column">
        <v-card
            class="mt-10"
            color="grey-lighten-4"
            flat
            max-width="800"
            rounded="lg"
            width="100%"
        >
            <v-toolbar density="compact">
                <v-toolbar-title>
                    <v-icon>mdi-playlist-music</v-icon> {{ $t('playlistIndex.title') }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-menu location="top end">
                    <template v-slot:activator="{ props }">
                        <v-btn icon="mdi-account-circle" v-bind="props"></v-btn>
                    </template>
                    <v-card
                        class="mx-auto pa-2"
                        max-width="300"
                        :title="auth.user.name"
                        :subtitle="auth.user.email"
                    >
                        <template v-slot:prepend>
                            <v-avatar
                                size="48px"
                            >
                                <v-img
                                    v-if="auth.user.avatar"
                                    alt="Avatar"
                                    :src="auth.user.avatar"
                                ></v-img>
                                <v-icon
                                    v-else
                                    color="#7ac143"
                                    icon="mdi-user"
                                ></v-icon>
                            </v-avatar>
                        </template>
                        <v-list>
                            <v-list-item
                                :href="$route('account.index')"
                                rounded="xl"
                            >
                                <template v-slot:prepend>
                                    <v-icon>mdi-account</v-icon>
                                </template>
                                <v-list-item-title>{{ $t('menuBar.account') }}</v-list-item-title>
                            </v-list-item>
                            <v-list-item
                                :href="$route('sign-out')"
                                rounded="xl"
                            >
                                <template v-slot:prepend>
                                    <v-icon>mdi-logout-variant</v-icon>
                                </template>
                                <v-list-item-title>{{ $t('menuBar.signOut') }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-menu>
            </v-toolbar>
            <v-row class="" no-gutters >
                <v-col cols="4" v-for="playlist in playlists" :key="playlist.id">
                    <v-card class="pa-2 ma-2 bg-amber-lighten-4" :href="$route('playlist.show', playlist.uuid)">
                        <h6 class="text-h6">{{ playlist.name }}</h6>
                        <p class="text-body-1">{{ playlist.spotify_identifier }}</p>
                        <p class="text-caption">{{ playlist.last_sync }}</p>
                    </v-card>
                </v-col>
            </v-row>
            <v-btn
                prepend-icon="mdi-plus"
                color="green-darken-1"
                size="large"
                :href="$route('playlist.create')"
            >
                Create Playlist
            </v-btn>
        </v-card>
    </div>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'

const props = defineProps(['playlists', 'auth']);

</script>
