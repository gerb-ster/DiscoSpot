<template>
    <Head title="Account Page" />
    <Layout menu-icon="mdi-account" :menu-title="$t('accountIndex.title')" :auth="auth" :disableMenu="true" :bread-crumbs="breadCrumbs">
        <v-row justify="center">
            <v-col class="d-flex justify-center align-top pt-10" cols="3">
                <v-avatar
                    size="128px"
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
            </v-col>
            <v-col class="d-flex align-top pt-7" cols="9">
                <v-list bg-color="transparent">
                    <v-list-item>
                        <v-list-item-subtitle>{{ $t('accountIndex.name') }}</v-list-item-subtitle>
                        <v-list-item-title>{{ auth.user.name }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-subtitle>{{ $t('accountIndex.email') }}</v-list-item-subtitle>
                        <v-list-item-title>{{ auth.user.email }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-subtitle>{{ $t('accountIndex.discogsUsername') }}</v-list-item-subtitle>
                        <v-list-item-title>{{ auth.user.discogs_username }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-subtitle>{{ $t('accountIndex.spotifyUsername') }}</v-list-item-subtitle>
                        <v-list-item-title>{{ auth.user.discogs_username }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-subtitle>{{ $t('accountIndex.accountType') }}</v-list-item-subtitle>
                        <v-list-item-title>{{ auth.user.account.name }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-col>
        </v-row>
        <v-row justify="center" class="mb-10">
            <v-col class="d-flex align-top" cols="9" offset="3">
                <v-btn
                    border
                    class="ms-3 me-2 text-none"
                    color="red-darken-4"
                    prepend-icon="mdi-trash-can-outline"
                    variant="flat"
                >
                    {{ $t('accountIndex.removeAccount') }}
                </v-btn>
            </v-col>
            <v-col class="d-flex align-top" cols="9" offset="3">
                <v-btn
                    border
                    class="ms-3 me-2 text-none"
                    prepend-icon="mdi-logout-variant"
                    variant="flat"
                >
                    {{ $t('accountIndex.signOut') }}
                </v-btn>
            </v-col>
        </v-row>
    </Layout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3'
import Layout from "../../Shared/Layout.vue";
import {ref} from "vue";
import {useI18n} from "vue-i18n";

const props = defineProps(['accountTypes', 'auth']);
const { t } = useI18n();

const breadCrumbs = ref([{
    title: t('breadCrumbs.myPlaylists'),
    disabled: false,
    href: route('playlist.index'),
}, {
    title: t('breadCrumbs.yourAccount'),
    disabled: true,
}
]);

</script>
