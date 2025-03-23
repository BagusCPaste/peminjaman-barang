<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div v-if="isLoaded" class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-600">
                        Dashboard
                    </h1>
                    <p class="mt-2 text-lg text-gray-700">
                        {{
                            isAdmin
                                ? "Ringkasan data sistem peminjaman barang"
                                : "Selamat datang di sistem peminjaman barang"
                        }}
                    </p>
                </div>
                <button
                    @click="refreshData"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="isRefreshing"
                >
                    <svg
                        v-if="isRefreshing"
                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    {{ isRefreshing ? "Memperbarui..." : "Refresh Data" }}
                </button>
            </div>

            <!-- Grafik Statistik -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 md:grid-cols-2">
                <div
                    v-for="(chart, index) in charts"
                    :key="index"
                    class="rounded-lg shadow-lg p-4 bg-white h-80"
                >
                    <apexchart
                        :type="chart.type"
                        :options="chart.options"
                        :series="chart.series"
                        height="100%"
                        width="100%"
                    />
                </div>
            </div>

            <!-- Ringkasan Peminjaman User -->
            <div
                v-if="
                    !isAdmin &&
                    dashboardData.peminjaman_user &&
                    dashboardData.peminjaman_user.length > 0
                "
                class="mt-8"
            >
                <h2 class="text-xl font-medium text-gray-900">
                    Peminjaman Saya
                </h2>
                <div class="mt-4 overflow-hidden shadow-lg rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th
                                    class="py-3 px-4 text-left text-sm font-bold text-indigo-700"
                                >
                                    Barang
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-sm font-bold text-indigo-700"
                                >
                                    Tanggal Pinjam
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-sm font-bold text-indigo-700"
                                >
                                    Tanggal Kembali
                                </th>
                                <th
                                    class="py-3 px-4 text-left text-sm font-bold text-indigo-700"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="peminjaman in dashboardData.peminjaman_user.slice(
                                    0,
                                    5
                                )"
                                :key="peminjaman.id"
                            >
                                <td class="py-4 px-4">
                                    <ul>
                                        <li
                                            v-for="detail in peminjaman.detail_peminjaman"
                                            :key="detail.id"
                                        >
                                            {{ detail.barang?.nama }}
                                            ({{ detail.jumlah }} unit)
                                        </li>
                                    </ul>
                                </td>
                                <td class="py-4 px-4">
                                    {{
                                        formatTanggal(peminjaman.tanggal_pinjam)
                                    }}
                                </td>
                                <td class="py-4 px-4">
                                    {{
                                        formatTanggal(
                                            peminjaman.tanggal_kembali
                                        )
                                    }}
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        :class="[
                                            peminjaman.status === 'Dipinjam'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-green-100 text-green-800',
                                            'inline-flex rounded-full px-2 text-xs font-semibold',
                                        ]"
                                    >
                                        {{ peminjaman.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else class="flex justify-center items-center min-h-screen">
            <div class="animate-pulse text-lg text-gray-600">
                Memuat data...
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount, computed } from "vue";
import axios from "axios";
import { Head, usePage } from "@inertiajs/vue3";
import MainLayout from "@/layouts/MainLayout.vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === "admin");

const props = defineProps({
    total_barang: Number,
    total_pengguna: Number,
    total_peminjaman_aktif: Number,
    peminjaman_user: Array,
});

// Data reaktif yang akan diperbarui dari API
const dashboardData = ref({
    total_barang: props.total_barang || 0,
    total_pengguna: props.total_pengguna || 0,
    total_peminjaman_aktif: props.total_peminjaman_aktif || 0,
    peminjaman_user: props.peminjaman_user || [],
});

const isLoaded = ref(false);
const isRefreshing = ref(false);
let cancelTokenSource = axios.CancelToken.source();

const charts = computed(() => [
    {
        type: "donut",
        options: {
            labels: ["Total Barang", "Total Pengguna", "Peminjaman Aktif"],
            colors: ["#4F46E5", "#10B981", "#F59E0B"],
            title: { text: "Statistik Utama" },
        },
        series: [
            dashboardData.value.total_barang,
            dashboardData.value.total_pengguna,
            dashboardData.value.total_peminjaman_aktif,
        ],
    },
    {
        type: "bar",
        options: {
            chart: { id: "bar-chart" },
            xaxis: { categories: ["Barang", "Pengguna", "Peminjaman"] },
        },
        series: [
            {
                name: "Data",
                data: [
                    dashboardData.value.total_barang,
                    dashboardData.value.total_pengguna,
                    dashboardData.value.total_peminjaman_aktif,
                ],
            },
        ],
    },
]);

const formatTanggal = (tanggal) => {
    if (!tanggal) return "-";
    return new Date(tanggal).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Fungsi untuk mengambil data dashboard terbaru
const fetchDashboardData = async () => {
    try {
        isRefreshing.value = true;

        // Karena API dashboard-data memerlukan autentikasi, pastikan token CSRF tersedia
        // di cookie untuk Laravel Sanctum
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        // Gunakan fallback ke endpoint individual karena dashboard-data masih bermasalah
        await fetchDataFromIndividualEndpoints();

        toast.success("Data dashboard berhasil diperbarui");
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error fetching dashboard data:", error);
            toast.error("Gagal memuat data dashboard");
        }
    } finally {
        isRefreshing.value = false;
        isLoaded.value = true;
    }
};

// Fungsi untuk mengambil data dari endpoint individual sebagai fallback
const fetchDataFromIndividualEndpoints = async () => {
    try {
        // Ambil total barang
        const barangRes = await axios.get("/api/barang", {
            params: { timestamp: new Date().getTime() },
            cancelToken: cancelTokenSource.token,
        });

        if (barangRes.data && Array.isArray(barangRes.data)) {
            dashboardData.value.total_barang = barangRes.data.length;
        }

        // Ambil total pengguna
        const penggunaRes = await axios.get("/api/pengguna", {
            params: { timestamp: new Date().getTime() },
            cancelToken: cancelTokenSource.token,
        });

        if (penggunaRes.data && Array.isArray(penggunaRes.data)) {
            dashboardData.value.total_pengguna = penggunaRes.data.length;
        }

        // Ambil total peminjaman
        const peminjamanRes = await axios.get("/api/peminjaman", {
            params: { timestamp: new Date().getTime() },
            cancelToken: cancelTokenSource.token,
        });

        if (peminjamanRes.data && Array.isArray(peminjamanRes.data)) {
            dashboardData.value.total_peminjaman_aktif =
                peminjamanRes.data.filter(
                    (p) => p.status === "Dipinjam"
                ).length;
        }

        // Ambil data peminjaman user jika bukan admin
        if (!isAdmin.value) {
            await fetchUserPeminjaman();
        }
    } catch (error) {
        console.error("Error fetching individual data:", error);
        throw error; // Re-throw agar caller bisa menangani
    }
};

// Ambil data peminjaman user jika pengguna bukan admin
const fetchUserPeminjaman = async () => {
    if (isAdmin.value) return;

    try {
        const response = await axios.get(
            `/api/peminjaman/user/${user.value?.id}`,
            {
                params: { timestamp: new Date().getTime() },
                cancelToken: cancelTokenSource.token,
            }
        );

        if (response.data) {
            if (Array.isArray(response.data)) {
                dashboardData.value.peminjaman_user = response.data;
            } else if (
                response.data.data &&
                Array.isArray(response.data.data)
            ) {
                dashboardData.value.peminjaman_user = response.data.data;
            } else if (
                response.data.peminjaman &&
                Array.isArray(response.data.peminjaman)
            ) {
                dashboardData.value.peminjaman_user = response.data.peminjaman;
            }
        }
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error fetching user peminjaman data:", error);
        }
    }
};

// Tombol refresh untuk memperbarui data
const refreshData = () => {
    if (isRefreshing.value) return;

    // Batalkan request yang sedang berjalan
    cancelTokenSource.cancel("Refresh requested");
    cancelTokenSource = axios.CancelToken.source();

    // Ambil data baru
    fetchDashboardData();
};

onMounted(() => {
    // Delay loading untuk mengurangi beban awal
    setTimeout(() => {
        fetchDashboardData();
    }, 100);
});

onBeforeUnmount(() => {
    cancelTokenSource.cancel("Component unmounted");
});
</script>

<style>
.apexcharts-canvas {
    width: 100% !important;
    height: 100% !important;
}

.apexcharts-svg {
    width: 100% !important;
    height: 100% !important;
}

.apexcharts-graphical {
    width: 100% !important;
    height: 100% !important;
}
</style>
