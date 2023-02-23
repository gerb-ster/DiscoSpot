<template>
    <h5 class="text-h5 mb-6">Select the folder you which to include in the playlist</h5>
    <v-select
        label="Folder Items"
        :items="folderItems"
        item-title="name"
        item-value="id"
        v-model="selectedItem"
    ></v-select>
    <v-btn
        class="me-4"
        @click="gotoPrevious()"
    >
        Previous
    </v-btn>
    <v-btn
        class="me-4"
        :disabled="selectedItem === null"
        @click="onNextClick()"
    >
        Next
    </v-btn>
</template>

<script>
import axios from "axios";

export default {
    components: {
    },
    data: () => ({
        selectedItem: null,
        folderItems: [],
    }),
    created() {
        this.getClient();
    },
    methods: {
        getClient: function () {
            axios.get(route('discogs.get-folders'))
                .then((response) => {
                    this.folderItems = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        gotoPrevious: function () {
            this.$emit("customChange", 'CreateSelectType')
        },
        onNextClick: function() {
            console.log(this.selectedItem);
            this.$emit("customChange", 'CreateAddFilters')
        }
    },
    emits: ["customChange"],
}
</script>
