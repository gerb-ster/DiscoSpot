<template>
    <v-card color="transparent">
        <h5 class="text-h5 mb-6">{{ $t("playlistCreate.stepTwoListsTitle")}}</h5>
        <v-select
            label="List Items"
            :items="listItems"
            item-title="name"
            item-value="id"
            v-model="form.selectedList"
        ></v-select>
    </v-card>
</template>

<script setup>

import {ref} from "vue";

const props = defineProps(['form']);
const listItems = ref([]);

import axios from "axios";

function loadLists() {
    axios.get(route('discogs.get-lists'))
        .then((response) => {
            listItems.value = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
}

loadLists();

</script>
