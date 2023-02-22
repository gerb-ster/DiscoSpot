<template>
    <div>
        <div v-if="$page.props.flash.success && show">
            <v-alert
                type="success"
                title="Success"
                text="{{ $page.props.success }}"
            ></v-alert>
        </div>
        <div v-if="($page.props.flash.error || Object.keys($page.props.errors).length > 0) && show">
            <v-alert
                type="error"
                title="Error"
                text="{{ $page.props.errors }}"
            ></v-alert>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: true,
        }
    },
    watch: {
        '$page.props.flash': {
            handler() {
                this.show = true
            },
            deep: true,
        },
    },
}
</script>
