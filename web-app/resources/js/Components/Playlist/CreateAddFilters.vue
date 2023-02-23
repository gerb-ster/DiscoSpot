<template>
    <h5 class="text-h5 mb-6">Add any Filters</h5>
    <v-text-field
        v-model="name"
        :counter="64"
        label="Name"
    ></v-text-field>
    <v-select
        label="Playlist Type"
        :items="[]"
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
export default {
    components: {
    },
    data: () => ({
        selectedItem: null
    }),
    emits: ["customChange"],
    props: {
    },
    methods: {
        onNextClick: function() {
            this.$emit("customChange", 'CreateSummary')
        },
        gotoPrevious: function() {
            this.$emit("customChange", 'CreateSelectFolder')
        }
    }
}
</script>
