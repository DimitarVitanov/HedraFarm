import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { storefrontVersion } from './utils/preview';

const pages = import.meta.glob('./Pages/**/*.vue');
const pagesV2 = import.meta.glob('./PagesV2/**/*.vue');
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => {
            // Keep in sync with app.js (see utils/preview.js)
            if (storefrontVersion() === 'v2' && pagesV2[`./PagesV2/${name}.vue`]) {
                return resolvePageComponent(`./PagesV2/${name}.vue`, pagesV2);
            }
            return resolvePageComponent(`./Pages/${name}.vue`, pages);
        },
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                });
        },
    }),
);
