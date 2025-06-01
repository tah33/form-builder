import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import {createBootstrap} from 'bootstrap-vue-next'
import { createPinia } from 'pinia'
import DashboardLayout from './components/DashboardLayout.vue';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        const page = pages[`./Pages/${name}.vue`]()
        page.then((module) => {
            module.default.layout = module.default.layout ||
                (name.startsWith('Admin/') ? DashboardLayout : undefined)
        })
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(createBootstrap())
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
