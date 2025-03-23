import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "ziggy-js";

// Import ApexCharts
import VueApexCharts from "vue3-apexcharts"; 

// Import Toast
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        console.log("Resolving page:", name);
        return resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
    },
    setup({ el, App, props, plugin }) {
        console.log("Setting up app with props:", props);
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueApexCharts); // 🔥 Daftarkan plugin ApexCharts

        // Daftarkan komponen global
        app.component("apexchart", VueApexCharts); // 🔥 Registrasi komponen

        // Konfigurasi Toast
        const toastOptions = {
            transition: "Vue-Toastification__bounce",
            maxToasts: 5,
            newestOnTop: true,
            position: "top-center",
            timeout: 3000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            toastClassName: "custom-toast",
            bodyClassName: "custom-toast-body",
            containerClassName: "custom-toast-container",
        };

        // Gunakan plugin toast
        app.use(Toast, toastOptions);

        // Tambahkan custom CSS untuk toast
        const style = document.createElement("style");
        style.textContent = `
            .custom-toast {
                font-size: 16px !important;
                font-weight: 500 !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
                border-radius: 8px !important;
                padding: 12px 16px !important;
                min-width: 300px !important;
            }
            .custom-toast.Vue-Toastification__toast--success {
                background-color: #10B981 !important;
            }
            .custom-toast.Vue-Toastification__toast--error {
                background-color: #EF4444 !important;
            }
            .custom-toast-body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
                line-height: 1.5 !important;
            }
            .custom-toast-container {
                z-index: 9999 !important;
            }
        `;
        document.head.appendChild(style);

        return app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
