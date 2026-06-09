import '../css/app.css';
// import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { loadSlim } from "@tsparticles/slim";
import Particles from "@tsparticles/vue3";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import './theme';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(Particles, {
            init: async (engine) => {
                await loadSlim(engine);
            },
        });
        // Register Particles component globally (do not `use` it as a plugin)
        // Particles component is loaded dynamically per-page to avoid export/plugin conflicts
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
