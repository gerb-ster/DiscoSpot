<template>
    <h5 class="text-h5 mb-6">Summary</h5>

    <v-btn
        class="me-4"
        @click="gotoPrevious()"
    >
        Previous
    </v-btn>
    <v-btn
        class="me-4"
        @click="onNextClick()"
    >
        Save
    </v-btn>
</template>

<script>
export default {
    components: {
    },
    props: {
    },
    emits: ["customChange"],
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
