import '../css/app.css';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import '../../public/assets/css/bootstrap.min.css';
import '../css/theme.min.css'
import '../css/styles.css'
import '../css/storefront.css'
import '@fortawesome/fontawesome-free/css/all.min.css'; // Includes solid, regular, and brands
import Lara from '@primeuix/themes/lara'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';

import { storefrontVersion } from './utils/preview';

const appName = import.meta.env.VITE_APP_NAME || 'Хедерафарм+';
const pages = import.meta.glob('./Pages/**/*.vue');
const pagesV2 = import.meta.glob('./PagesV2/**/*.vue');

// Force light theme - remove dark mode
document.documentElement.removeAttribute('data-bs-theme');
document.body.removeAttribute('data-bs-theme');
document.documentElement.setAttribute('data-bs-theme', 'light');

createInertiaApp({
    title: (title) => `Хедерафарм+`,
    resolve: (name) => {
        // Redesigned storefront pages live in PagesV2 (see utils/preview.js)
        if (storefrontVersion() === 'v2' && pagesV2[`./PagesV2/${name}.vue`]) {
            return resolvePageComponent(`./PagesV2/${name}.vue`, pagesV2);
        }
        return resolvePageComponent(`./Pages/${name}.vue`, pages);
    },
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

// Smooth scroll with Lenis
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smooth: true,
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}
requestAnimationFrame(raf);
