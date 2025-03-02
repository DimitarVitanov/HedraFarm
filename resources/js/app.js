import '../css/app.css';
import '../../public/assets/css/bootstrap.min.css';
import '../css/theme.min.css'
import '../css/styles.css'


import Lara from '@primeuix/themes/lara'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue,
                {
                    theme: {
                        preset: Lara,
                        dark:false,
                    }
                }
            )
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
