<template>
    <h5 class="text-h5 mb-6">Add any Filters</h5>
    <div v-for="(item, index) in filterItems">
        <filter-item
            @remove-filter-item="removeFilterItem"
            :index="index"
        />
    </div>
    <v-btn
        class="me-4"
        @click="addFilterItem()"
    >
        Add Filter Item
    </v-btn>
    <v-divider></v-divider>
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
import FilterItem from "./FilterItem.vue";
export default {
    components: {
        FilterItem
    },
    data: () => ({
        filterItems: [{
            uuid: 'blob'
        }]
    }),
    emits: ["customChange"],
    props: {
    },
    methods: {
        addFilterItem: function() {
            this.filterItems.push({
                uuid: 'blob'
            })
        },
        removeFilterItem: function(index) {
            this.filterItems.splice(index, 1);
        },
        onNextClick: function() {
            this.$emit("customChange", 'CreateSummary')
        },
        gotoPrevious: function() {
            this.$emit("customChange", 'CreateSelectFolder')
        }
    }
}
</script>
