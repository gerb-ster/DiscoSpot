<template>
    <v-app id="layout">
        <v-main class="backgroundGradient">
            <v-container>
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
                                <v-icon>{{ menuIcon }}</v-icon> {{ menuTitle }}
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-menu location="top end" v-if="!disableMenu">
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
                        <slot />
                    </v-card>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>

const props = defineProps(['menuIcon', 'menuTitle', 'auth', 'disableMenu']);

</script>
