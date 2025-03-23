<template>
    <div class="min-h-screen bg-gray-100">
        <Navbar />

        <!-- Konten utama -->
        <main v-if="isLayoutReady" class="pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
        <div v-else class="flex justify-center items-center min-h-screen">
            <div class="animate-pulse text-lg text-gray-600">Memuat...</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar.vue";

const isLayoutReady = ref(false);

onMounted(() => {
    // Berikan sedikit delay untuk pastikan DOM siap
    setTimeout(() => {
        isLayoutReady.value = true;
    }, 10);
});
</script>
