<template>
    <v-alert
        v-if="$page.props.flash.success"
        closable
        type="success"
        :title="$t('flashMessages.successTitle')"
        :text="$t($page.props.flash.success)"
        class="mb-4 mt-3 ms-3 me-3"
    ></v-alert>
    <v-alert
        v-if="$page.props.flash.warning"
        closable
        type="warning"
        :title="$t('flashMessages.warningTitle')"
        :text="$t($page.props.flash.warning)"
        class="mb-4"
    ></v-alert>
    <v-alert
        v-if="$page.props.flash.error || Object.keys($page.props.errors).length > 0"
        closable
        type="error"
        :title="$t('flashMessages.errorTitle')"
        :text="formatErrors($page.props.errors)"
        class="mb-4"
    ></v-alert>
</template>

<script setup>

function formatErrors(errorObj) {
    let ret = '';
    Object.keys(errorObj).forEach(key => {
        ret += key + ': ' + errorObj[key] + "\n";
    });
    return ret;
}

</script>
