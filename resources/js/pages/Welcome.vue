<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800"
    >
        <!-- Navigation -->
        <nav
            class="fixed w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-50 border-b border-gray-200 dark:border-gray-800"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1
                            class="text-2xl font-bold text-indigo-600 dark:text-indigo-400"
                        >
                            Sistem Peminjaman Barang
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link
                                href="/dashboard"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Dashboard
                            </Link>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Logout
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('auth.sliding')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Masuk / Daftar
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-16 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <template v-if="$page.props.auth.user">
                        <h2
                            class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-4"
                        >
                            Selamat Datang, {{ $page.props.auth.user.name }}!
                        </h2>
                    </template>
                    <h2
                        class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl"
                    >
                        <span class="block">Kelola Peminjaman Barang</span>
                        <span class="block text-indigo-600 dark:text-indigo-400"
                            >Dengan Mudah</span
                        >
                    </h2>
                    <p
                        class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl"
                    >
                        Sistem manajemen peminjaman barang yang modern, mudah
                        digunakan, dan efisien untuk mengelola inventaris Anda.
                    </p>
                </div>

                <!-- Feature Grid -->
                <div class="mt-20">
                    <div
                        class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <!-- Feature 1 -->
                        <div
                            class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300"
                        >
                            <div class="absolute top-6 right-6">
                                <div
                                    class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white"
                            >
                                Inventaris Digital
                            </h3>
                            <p
                                class="mt-2 text-base text-gray-500 dark:text-gray-400"
                            >
                                Kelola semua barang dalam inventaris Anda secara
                                digital dengan mudah dan efisien.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div
                            class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300"
                        >
                            <div class="absolute top-6 right-6">
                                <div
                                    class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white"
                            >
                                Peminjaman Real-time
                            </h3>
                            <p
                                class="mt-2 text-base text-gray-500 dark:text-gray-400"
                            >
                                Lacak status peminjaman secara real-time dan
                                dapatkan notifikasi saat barang dikembalikan.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div
                            class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300"
                        >
                            <div class="absolute top-6 right-6">
                                <div
                                    class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white"
                            >
                                Laporan & Analitik
                            </h3>
                            <p
                                class="mt-2 text-base text-gray-500 dark:text-gray-400"
                            >
                                Dapatkan laporan detail dan analitik untuk
                                membantu pengambilan keputusan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="mt-20 text-center">
                    <template v-if="$page.props.auth.user">
                        <div class="space-y-4">
                            <template
                                v-if="$page.props.auth.user.role === 'admin'"
                            >
                                <Link
                                    href="/admin/barang"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition-colors duration-300 mx-2"
                                >
                                    Kelola Barang
                                </Link>
                                <Link
                                    href="/admin/peminjaman"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10 transition-colors duration-300 mx-2"
                                >
                                    Kelola Peminjaman
                                </Link>
                            </template>
                            <template v-else>
                                <Link
                                    href="/user/barang"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition-colors duration-300 mx-2"
                                >
                                    Lihat Barang
                                </Link>
                                <Link
                                    href="/user/peminjaman"
                                    class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10 transition-colors duration-300 mx-2"
                                >
                                    Peminjaman Saya
                                </Link>
                            </template>
                        </div>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('auth.sliding')"
                            class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition-colors duration-300"
                        >
                            Mulai Sekarang
                        </Link>
                    </template>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer
            class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800"
        >
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div
                    class="text-center text-gray-500 dark:text-gray-400 text-sm"
                >
                    &copy; {{ new Date().getFullYear() }} Sistem Peminjaman
                    Barang. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>
