<template>
    <v-card color="transparent">
        <h5 class="text-h5 mb-6">Add Filters</h5>
        <div v-for="(filterItem, index) in form.filterItems">
            <v-row>
                <v-col cols="4">
                    <v-select
                        label="Filter on Field"
                        :items="filterTypes"
                        :item-title="item => $t('filterTypes.'+item.name)"
                        item-value="name"
                        v-model="filterItem.field"
                    ></v-select>
                </v-col>
                <v-col cols="6">
                    <v-text-field
                        :counter="64"
                        label="Filter Value"
                        v-model="filterItem.value"
                    ></v-text-field>
                </v-col>
                <v-col cols="2" class="d-flex">
                    <v-spacer />
                    <v-btn
                        icon="mdi-close"
                        color="deep-orange-darken-3"
                        @click="removeFilterItem(index)"
                    ></v-btn>
                </v-col>
            </v-row>
        </div>
        <v-btn
            class="me-4 mb-3"
            variant="flat"
            prepend-icon="mdi-plus"
            color="green-lighten-3"
            @click="addFilterItem()"
        >
            Add Filter Item
        </v-btn>
    </v-card>
</template>

<script setup>

const props = defineProps(['form', 'filterTypes']);

function addFilterItem() {
    props.form.filterItems.push({
        field: null,
        value: null
    })
}

function removeFilterItem(index) {
    props.form.filterItems.splice(index,1);
}

</script>
