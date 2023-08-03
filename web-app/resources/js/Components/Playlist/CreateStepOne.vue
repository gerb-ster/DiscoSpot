<template>
    <v-card color="transparent">
        <h5 class="text-h5 mb-6">{{ $t('playlistCreate.stepOneTitle')}}</h5>
        <v-text-field
            :counter="64"
            :label="$t('playlistCreate.fields.name')"
            v-model="form.name"
        ></v-text-field>
        <v-select
            :label="$t('playlistCreate.fields.type')"
            :items="playlistTypes"
            :item-title="item => $t('playlistTypes.'+item.name)"
            item-value="id"
            v-model="form.typeId"
        ></v-select>
    </v-card>
</template>

<script setup>
const props = defineProps(['playlistTypes', 'form']);
</script>
