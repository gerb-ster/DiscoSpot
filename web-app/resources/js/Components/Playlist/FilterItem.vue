<template>
    <v-select
        label="Filter on Field"
        :items="['genre', 'label', 'artist', 'style', 'year', 'format']"
        item-title="name"
        item-value="id"
        v-model="selectedItem"
    ></v-select>
    <v-text-field
        :counter="64"
        label="Filter Value"
    ></v-text-field>
    <v-btn
        class="me-4"
        @click="removeFilterItem()"
    >
        Remove Filter Item
    </v-btn>
    <v-divider></v-divider>
</template>

<script>
export default {
    components: {
    },
    data: () => ({
        selectedItem: null
    }),
    props: {
        index: Number
    },
    methods: {
        removeFilterItem: function() {
            this.$emit("removeFilterItem", this.index)
        },
    }
}
</script>
