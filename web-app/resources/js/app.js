import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import Layout from "./Shared/Layout.vue";
import {VDataTable, VDataTableServer} from "vuetify/labs/components";
import axios from 'axios'
import VueAxios from 'vue-axios'
import en from '../lang/en.json';
import nl from '../lang/nl.json';
import {createI18n} from "vue-i18n";

// Custom theming
import '../sass/app.scss';

const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
    legacy: false,
    messages: {
        nl,
        en
    }
});

const vuetify = createVuetify({
    components: {
        VDataTable,
        VDataTableServer
    },
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        }
    }
});

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || Layout
        return page
    },
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(vuetify)
            .use(i18n)
            .use(VueAxios, axios);

        app.config.globalProperties.$route = route;
        app.mount(el);

        return app;
    },
}).then(r => {
    console.log('App started!')
})
