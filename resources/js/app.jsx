import '../css/app.css';
import './bootstrap';

import {StrictMode} from 'react';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createRoot} from 'react-dom/client';
import {DevSupport} from "@react-buddy/ide-toolbox";
import {useInitial} from "@/jetbrains_plugin_helper";
import ComponentPreviews from "@/jetbrains_plugin_helper/previews";
import {ConfigProvider} from "antd";

const appName = import.meta.env.VITE_APP_NAME || 'Dr.AI';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({el, App, props}) {
        const root = createRoot(el);

        root.render(
            <>
                <StrictMode>
                    <ConfigProvider
                        theme={{
                            components: {
                                Switch: {
                                    handleBg: '#fff',

                                },

                            },
                            token: {
                                colorPrimary: '#ff1616', // Set primary color globally for all components
                            },
                        }}
                    >
                        <DevSupport
                            ComponentPreviews={<ComponentPreviews/>}
                            useInitialHook={useInitial}
                        >
                            <App {...props} />
                        </DevSupport>
                    </ConfigProvider>
                </StrictMode>
            </>
        );
    },
    progress: {
        color: '#ff1616',
    },
});
