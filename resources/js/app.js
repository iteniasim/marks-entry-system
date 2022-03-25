require('./bootstrap')

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { variantJS } from '@variantjs/vue'

const configuration = {
    TButton: {
        // The fixed classes will never change and will be merged with the `classes` value or the active variant
        fixedClasses: 'focus:outline-none focus:shadow-outline inline-flex items-center transition ease-in-out duration-150',
        // Classes used when any variant is active
        classes: 'text-white bg-blue-600 hover:bg-blue-500 focus:border-blue-700 active:bg-blue-700 text-sm font-medium border border-transparent px-3 py-2 rounded-md',
        variants: {
            // A red variant of the button (applied when `<t-button variant="error" />`)
            error: {
                classes: 'text-white bg-red-600 hover:bg-red-500 focus:border-red-700 active:bg-red-700 text-sm font-medium border border-transparent px-3 py-2 rounded-md',
            },
            // A green variant of the button (applied when `<t-button variant="success" />`)
            success: {
                classes: 'text-white bg-green-600 hover:bg-green-500 focus:border-green-700 active:bg-green-700 text-sm font-medium border border-transparent px-3 py-2 rounded-md',
            },
        },
    },
}

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(variantJS, configuration)
            .mixin({ methods: { route } })
            .mount(el)
    },
})

InertiaProgress.init({ color: '#4B5563' })
