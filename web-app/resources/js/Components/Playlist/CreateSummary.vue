<template>
    <v-card color="transparent">
        <h5 class="text-h5 mb-6">{{ $t('playlistCreate.stepThreeSummary')}}</h5>
        <p>All done</p>
        <v-btn
            size="large"
            color="primary"
            prepend-icon="mdi-content-save"
            type="submit"
        > {{ $t('playlistCreate.saveBtn') }}</v-btn>
    </v-card>
</template>

<script setup>

const props = defineProps(['form']);

</script>
