import { createRouter, createWebHistory } from "vue-router";
import MainLayout from "./layouts/MainLayout.vue";
import Dashboard from "./pages/Dashboard.vue";
import Barang from "./pages/Barang.vue";
import Pengguna from "./pages/Pengguna.vue";
import Peminjaman from "./pages/Peminjaman.vue";

const routes = [
    {
        path: "/",
        component: MainLayout,
        children: [
            {
                path: "",
                name: "dashboard",
                component: Dashboard,
            },
            {
                path: "/barang",
                name: "barang",
                component: Barang,
            },
            {
                path: "/pengguna",
                name: "pengguna",
                component: Pengguna,
            },
            {
                path: "/peminjaman",
                name: "peminjaman",
                component: Peminjaman,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
