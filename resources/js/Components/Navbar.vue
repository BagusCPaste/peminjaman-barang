<template>
    <nav class="bg-white dark:bg-gray-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <Link
                            href="/"
                            class="text-xl font-bold text-indigo-600 dark:text-indigo-400"
                        >
                            <!-- Logo modern dan elegan -->
                            <svg
                                class="w-10 h-10"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 2L2 7L12 12L22 7L12 2Z"
                                    fill="currentColor"
                                />
                                <path
                                    d="M2 17L12 22L22 17"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M2 12L12 17L22 12"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </Link>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <Link
                            :href="route('dashboard')"
                            class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                            :class="{
                                'border-indigo-500 text-gray-900 dark:text-white':
                                    route().current('dashboard'),
                            }"
                        >
                            Dashboard
                        </Link>

                        <!-- Admin Menu -->
                        <template
                            v-if="
                                $page.props.auth.user &&
                                $page.props.auth.user.role === 'admin'
                            "
                        >
                            <Link
                                :href="route('admin.barang.index')"
                                class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                                :class="{
                                    'border-indigo-500 text-gray-900 dark:text-white':
                                        route().current('admin.barang.*'),
                                }"
                            >
                                Data Barang
                            </Link>
                            <Link
                                :href="route('admin.peminjaman.index')"
                                class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                                :class="{
                                    'border-indigo-500 text-gray-900 dark:text-white':
                                        route().current('admin.peminjaman.*') ||
                                        route().current('peminjaman.*'),
                                }"
                            >
                                Peminjaman
                            </Link>
                            <Link
                                :href="route('admin.pengguna.index')"
                                class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                                :class="{
                                    'border-indigo-500 text-gray-900 dark:text-white':
                                        route().current('admin.pengguna.*'),
                                }"
                            >
                                Data Pengguna
                            </Link>
                        </template>

                        <!-- User Menu -->
                        <template
                            v-if="
                                $page.props.auth.user &&
                                $page.props.auth.user.role === 'user'
                            "
                        >
                            <Link
                                :href="route('user.barang.index')"
                                class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                                :class="{
                                    'border-indigo-500 text-gray-900 dark:text-white':
                                        route().current('user.barang.*') ||
                                        route().current('barang.*'),
                                }"
                            >
                                Daftar Barang
                            </Link>
                            <Link
                                :href="route('user.peminjaman.index')"
                                class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 hover:text-gray-700 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200"
                                :class="{
                                    'border-indigo-500 text-gray-900 dark:text-white':
                                        route().current('peminjaman.*') ||
                                        route().current('user.peminjaman.*'),
                                }"
                            >
                                Peminjaman Saya
                            </Link>
                        </template>
                    </div>
                </div>

                <!-- User Actions di Navbar -->
                <div
                    class="hidden sm:flex sm:items-center sm:space-x-4"
                    v-if="$page.props.auth.user"
                >
                    <!-- Tombol Mode Terang/Gelap -->
                    <button
                        @click="toggleDarkMode"
                        class="p-2 rounded-full text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        :title="
                            isDarkMode
                                ? 'Beralih ke Mode Terang'
                                : 'Beralih ke Mode Gelap'
                        "
                    >
                        <!-- Ikon bulan untuk mode gelap -->
                        <svg
                            v-if="!isDarkMode"
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                            ></path>
                        </svg>

                        <!-- Ikon matahari untuk mode terang -->
                        <svg
                            v-else
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                            ></path>
                        </svg>
                    </button>

                    <!-- Tombol Profil -->
                    <Link
                        :href="route('profile.edit')"
                        class="p-2 rounded-full text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        title="Profil"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                    </Link>

                    <!-- Tombol Logout -->
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="p-2 rounded-full text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                        title="Logout"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            ></path>
                        </svg>
                    </Link>

                    <!-- Info Pengguna -->
                    <span
                        class="text-sm font-medium text-gray-600 dark:text-gray-300 hidden md:inline-block"
                    >
                        {{ $page.props.auth.user.name }}
                    </span>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    >
                        <span class="sr-only">Buka menu utama</span>
                        <svg
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            :class="{
                                hidden: mobileMenuOpen,
                                block: !mobileMenuOpen,
                            }"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            :class="{
                                block: mobileMenuOpen,
                                hidden: !mobileMenuOpen,
                            }"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div
            class="sm:hidden"
            :class="{ block: mobileMenuOpen, hidden: !mobileMenuOpen }"
        >
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </ResponsiveNavLink>

                <!-- Admin Mobile Menu -->
                <template
                    v-if="
                        $page.props.auth.user &&
                        $page.props.auth.user.role === 'admin'
                    "
                >
                    <ResponsiveNavLink
                        :href="route('admin.barang.index')"
                        :active="route().current('admin.barang.*')"
                    >
                        Data Barang
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        :href="route('admin.peminjaman.index')"
                        :active="
                            route().current('admin.peminjaman.*') ||
                            route().current('peminjaman.*')
                        "
                    >
                        Peminjaman
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        :href="route('admin.pengguna.index')"
                        :active="route().current('admin.pengguna.*')"
                    >
                        Data Pengguna
                    </ResponsiveNavLink>
                </template>

                <!-- User Mobile Menu -->
                <template
                    v-if="
                        $page.props.auth.user &&
                        $page.props.auth.user.role === 'user'
                    "
                >
                    <ResponsiveNavLink
                        :href="route('user.barang.index')"
                        :active="
                            route().current('user.barang.*') ||
                            route().current('barang.*')
                        "
                    >
                        Daftar Barang
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        :href="route('user.peminjaman.index')"
                        :active="
                            route().current('peminjaman.*') ||
                            route().current('user.peminjaman.*')
                        "
                    >
                        Peminjaman Saya
                    </ResponsiveNavLink>
                </template>
            </div>

            <!-- Mobile User Menu -->
            <div
                class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700"
                v-if="$page.props.auth.user"
            >
                <div class="px-4">
                    <div
                        class="font-medium text-base text-gray-800 dark:text-gray-200"
                    >
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div
                        class="font-medium text-sm text-gray-500 dark:text-gray-400"
                    >
                        {{ $page.props.auth.user.email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Profil di Mobile -->
                    <ResponsiveNavLink :href="route('profile.edit')">
                        Profil
                    </ResponsiveNavLink>

                    <!-- Logout di Mobile -->
                    <ResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Logout
                    </ResponsiveNavLink>

                    <!-- Tombol Dark Mode Mobile -->
                    <div
                        @click="toggleDarkMode"
                        class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out cursor-pointer"
                    >
                        {{ isDarkMode ? "Mode Terang" : "Mode Gelap" }}
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
// Menggunakan absolute import dengan alias '@' untuk menghindari masalah casing
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const mobileMenuOpen = ref(false);

// Inisialisasi dengan nilai yang aman
const isDarkMode = ref(false);

// Fungsi untuk toggle dark mode
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;

    // Simpan ke localStorage untuk persistensi
    if (typeof window !== "undefined") {
        localStorage.setItem("dark-mode", isDarkMode.value);
    }

    // Terapkan ke dokumen
    applyDarkMode();
};

// Fungsi untuk menerapkan dark mode ke dokumen
const applyDarkMode = () => {
    if (typeof document !== "undefined") {
        if (isDarkMode.value) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    }
};

// Inisialisasi dark mode saat komponen dimount
onMounted(() => {
    // Hanya jalankan di browser
    if (typeof window === "undefined") return;

    // Dapatkan preferensi dari localStorage atau sistem
    const prefersDarkMode = window.matchMedia(
        "(prefers-color-scheme: dark)"
    ).matches;
    const savedMode = localStorage.getItem("dark-mode");

    // Set nilai awal berdasarkan localStorage atau preferensi sistem
    isDarkMode.value =
        savedMode !== null ? savedMode === "true" : prefersDarkMode;

    // Terapkan setelan dark mode
    applyDarkMode();

    // Tambahkan listener untuk perubahan preferensi sistem
    try {
        const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");

        // Gunakan addEventListener atau addListener tergantung browser support
        const listener = (e) => {
            if (localStorage.getItem("dark-mode") === null) {
                isDarkMode.value = e.matches;
                applyDarkMode();
            }
        };

        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener("change", listener);
        } else if (mediaQuery.addListener) {
            // Untuk browser lama
            mediaQuery.addListener(listener);
        }
    } catch (error) {
        console.error("Error setting up dark mode listener:", error);
    }
});
</script>
