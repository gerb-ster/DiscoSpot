<template>
    <v-card color="transparent">
        <h5 class="text-h5 mb-6">{{ $t("playlistCreate.stepTwoFoldersTitle")}}</h5>
        <v-select
            label="Folder Items"
            :items="folderItems"
            item-title="name"
            item-value="id"
            v-model="form.selectedFolder"
        ></v-select>
    </v-card>
</template>

<script setup>

import {ref} from "vue";

const props = defineProps(['form']);
const folderItems = ref([]);

import axios from "axios";

function loadFolders() {
    axios.get(route('discogs.get-folders'))
        .then((response) => {
            folderItems.value = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
}

loadFolders();

</script>
