import './bootstrap'
import '../css/app.css'
import 'remixicon/fonts/remixicon.css'


import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import VueClipboard from 'vue3-clipboard'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup ({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueClipboard, {
                autoSetContainer: true,
                appendToBody: true
            })
            .mount(el)
    }
})

InertiaProgress.init({ color: '#4B5563' })
