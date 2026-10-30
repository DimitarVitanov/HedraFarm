import '../css/app.css';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import '../../public/assets/css/bootstrap.min.css';
import '../css/theme.min.css'
import '../css/styles.css'
import '../css/storefront.css'
import '../css/admin.css'
import '@fortawesome/fontawesome-free/css/all.min.css'; // Includes solid, regular, and brands
import Lara from '@primeuix/themes/lara'
import { definePreset } from '@primeuix/themes';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';

import { storefrontVersion } from './utils/preview';

const appName = import.meta.env.VITE_APP_NAME || 'Хедерафарм+';

// PrimeVue components (admin tables, selects, paginator) in the brand teal
const HederaPreset = definePreset(Lara, {
    semantic: {
        primary: {
            50: '#e6f5f2',
            100: '#c3e8e1',
            200: '#9bd9cd',
            300: '#6ec7b7',
            400: '#3aaf9d',
            500: '#0b8f7f',
            600: '#08766a',
            700: '#075f56',
            800: '#064b44',
            900: '#053d38',
            950: '#032421',
        },
    },
});
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
                        preset: HederaPreset,
                        options: { darkModeSelector: 'none' },
                    }
                }
            )
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Pages post with the token from <meta name="csrf-token">. Inertia visits don't reload the
// <head>, so after login/logout (which regenerate the token) keep the tag in sync.
router.on('navigate', (event) => {
    const token = event.detail.page.props.csrf_token;
    if (token) {
        document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', token);
    }
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
