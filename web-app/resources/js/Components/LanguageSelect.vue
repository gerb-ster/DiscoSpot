<template>
  <v-menu location="top end">
    <template v-slot:activator="{ props }">
      <v-btn icon="mdi-dots-vertical" v-bind="props">
        <v-img
            :width="20"
            :src="`/images/circle-flags/flags/${$t('language.code')}.svg`"></v-img>
      </v-btn>
    </template>
    <v-list>
      <v-list-item>
        <v-list-item-title>
          <v-btn variant="text" @click="changeLocale('nl')">
            {{ $t('language.dutch') }}
          </v-btn>
        </v-list-item-title>
        <v-list-item-title>
          <v-btn variant="text" @click="changeLocale('en')" >
            {{ $t('language.english') }}
          </v-btn>
        </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script setup>

import axios from 'axios';
import {useI18n} from "vue-i18n";

const { t, locale } = useI18n({});

const props = defineProps(['auth']);

function changeLocale(newLocale) {
  locale.value = newLocale;

  if (props.auth.user) {
      axios.post(route('account.setLocale'), {
          locale: newLocale
      });
  }
}

</script>
