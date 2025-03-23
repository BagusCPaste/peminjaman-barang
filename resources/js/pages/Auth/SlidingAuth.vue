<template>
    <Head title="Masuk / Daftar" />

    <div
        class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Tab Header -->
                <div class="flex">
                    <button
                        @click="activeTab = 'login'"
                        class="w-1/2 py-4 text-center transition-colors duration-300"
                        :class="
                            activeTab === 'login'
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                        "
                    >
                        Masuk
                    </button>
                    <button
                        @click="activeTab = 'register'"
                        class="w-1/2 py-4 text-center transition-colors duration-300"
                        :class="
                            activeTab === 'register'
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                        "
                    >
                        Daftar
                    </button>
                </div>

                <!-- Form Container with 3D Perspective Effect -->
                <div
                    class="relative overflow-hidden perspective"
                    style="height: 480px"
                >
                    <!-- Forms Wrapper -->
                    <div
                        class="transform-3d transition-transform duration-700 ease-in-out w-full h-full"
                        :class="{ 'rotate-y-180': activeTab === 'register' }"
                    >
                        <!-- Login Form - Front Side -->
                        <div
                            class="absolute top-0 w-full h-full p-6 backface-hidden"
                        >
                            <h2
                                class="text-center text-2xl font-bold mb-6 text-gray-800"
                            >
                                Masuk ke Akun Anda
                            </h2>

                            <form @submit.prevent="submit">
                                <div class="mb-4">
                                    <label
                                        for="login-email"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Email</label
                                    >
                                    <input
                                        id="login-email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan email anda"
                                    />
                                    <div
                                        v-if="form.errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="login-password"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Password</label
                                    >
                                    <input
                                        id="login-password"
                                        v-model="form.password"
                                        type="password"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan password anda"
                                    />
                                    <div
                                        v-if="form.errors.password"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.password }}
                                    </div>
                                </div>

                                <div class="flex items-center mb-4">
                                    <input
                                        id="remember-me"
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                    <label
                                        for="remember-me"
                                        class="ml-2 block text-sm text-gray-700"
                                    >
                                        Ingat saya
                                    </label>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    :class="{ 'opacity-75': form.processing }"
                                    :disabled="form.processing"
                                >
                                    {{
                                        form.processing
                                            ? "Memproses..."
                                            : "Masuk"
                                    }}
                                </button>
                            </form>

                            <div class="mt-4 text-center">
                                <Link
                                    :href="route('password.request')"
                                    class="text-sm text-indigo-600 hover:text-indigo-500"
                                >
                                    Lupa password?
                                </Link>
                            </div>
                        </div>

                        <!-- Register Form - Back Side (Rotated) -->
                        <div
                            class="absolute top-0 w-full h-full p-6 backface-hidden rotate-y-180"
                        >
                            <h2
                                class="text-center text-2xl font-bold mb-6 text-gray-800"
                            >
                                Buat Akun Baru
                            </h2>

                            <form @submit.prevent="submitRegister">
                                <div class="mb-4">
                                    <label
                                        for="register-name"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Nama</label
                                    >
                                    <input
                                        id="register-name"
                                        v-model="registerForm.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan nama anda"
                                    />
                                    <div
                                        v-if="registerForm.errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ registerForm.errors.name }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="register-email"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Email</label
                                    >
                                    <input
                                        id="register-email"
                                        v-model="registerForm.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan email anda"
                                    />
                                    <div
                                        v-if="registerForm.errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ registerForm.errors.email }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label
                                        for="register-password"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Password</label
                                    >
                                    <input
                                        id="register-password"
                                        v-model="registerForm.password"
                                        type="password"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Masukkan password anda"
                                    />
                                    <div
                                        v-if="registerForm.errors.password"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ registerForm.errors.password }}
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label
                                        for="password-confirm"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Konfirmasi Password</label
                                    >
                                    <input
                                        id="password-confirm"
                                        v-model="
                                            registerForm.password_confirmation
                                        "
                                        type="password"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Konfirmasi password anda"
                                    />
                                    <div
                                        v-if="
                                            registerForm.errors
                                                .password_confirmation
                                        "
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{
                                            registerForm.errors
                                                .password_confirmation
                                        }}
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    :class="{
                                        'opacity-75': registerForm.processing,
                                    }"
                                    :disabled="registerForm.processing"
                                >
                                    {{
                                        registerForm.processing
                                            ? "Memproses..."
                                            : "Daftar"
                                    }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

// Get toast instance
const toast = useToast();

// Active tab state
const activeTab = ref("login");

// Login form data using Inertia useForm
const form = useForm({
    email: "",
    password: "",
    remember: false,
});

// Register form data using Inertia useForm
const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

// Login form handler
const submit = () => {
    form.post(route("login"), {
        onSuccess: () => {
            toast.success("Login berhasil, mengalihkan...");
        },
        onError: () => {
            // Check for errors from Inertia form object
            if (Object.keys(form.errors).length > 0) {
                const errorMessage =
                    form.errors.email ||
                    form.errors.password ||
                    "Login gagal, cek kembali data anda";

                toast.error(errorMessage);
            } else {
                toast.error("Login gagal, coba lagi nanti");
            }
        },
        onFinish: () => form.reset("password"),
    });
};

// Register form handler
const submitRegister = () => {
    registerForm.post(route("register"), {
        onSuccess: () => {
            toast.success("Registrasi berhasil, mengalihkan...");
        },
        onError: () => {
            // Check for errors from Inertia form object
            if (Object.keys(registerForm.errors).length > 0) {
                const errorMessage =
                    registerForm.errors.email ||
                    registerForm.errors.name ||
                    registerForm.errors.password ||
                    registerForm.errors.password_confirmation ||
                    "Registrasi gagal, cek kembali data anda";

                toast.error(errorMessage);
            } else {
                toast.error("Registrasi gagal, coba lagi nanti");
            }
        },
        onFinish: () => registerForm.reset("password", "password_confirmation"),
    });
};
</script>

<style>
/* Efek 3D */
.perspective {
    perspective: 1000px;
}

.transform-3d {
    transform-style: preserve-3d;
}

.backface-hidden {
    backface-visibility: hidden;
}

.rotate-y-180 {
    transform: rotateY(180deg);
}

.transition-transform {
    transition-property: transform;
}
</style>
