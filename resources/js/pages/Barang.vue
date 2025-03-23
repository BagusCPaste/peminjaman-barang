<template>
    <Head title="Data Barang" />

    <MainLayout>
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Data Barang
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Daftar semua barang yang tersedia untuk dipinjam
                    </p>
                </div>
                <div v-if="isAdmin" class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button
                        @click="showModal = true"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                        :disabled="isLoading"
                    >
                        Tambah Barang
                    </button>
                </div>
            </div>

            <!-- Debug info untuk admin -->
            <div v-if="isAdmin" class="mt-2 text-xs text-gray-500">
                Admin Mode Active -
                {{ page.props.isAdmin ? "From props" : "From user role" }}
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading"
                class="flex justify-center items-center py-12"
            >
                <div class="animate-pulse text-lg text-gray-600">
                    Memuat data barang...
                </div>
            </div>

            <!-- Tabel Barang -->
            <div v-else class="mt-8 flex flex-col">
                <!-- Search dan Filter -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="onSearchChange"
                                placeholder="Cari barang..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <select
                            v-model="filterKategori"
                            @change="resetPagination"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="Semua">Semua Kategori</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Perlengkapan">Perlengkapan</option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filterStatus"
                            @change="resetPagination"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="Semua">Semua Status</option>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Dipinjam">Dipinjam</option>
                        </select>
                    </div>
                </div>

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div
                        class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                    >
                        <div
                            class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
                        >
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Kode
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Nama Barang
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Kategori
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Stok
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Status
                                        </th>
                                        <th
                                            v-if="isAdmin"
                                            scope="col"
                                            class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                        >
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr v-if="paginatedBarangList.length === 0">
                                        <td
                                            colspan="6"
                                            class="px-6 py-4 text-center text-gray-500"
                                        >
                                            Tidak ada data barang yang sesuai
                                            dengan kriteria pencarian
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="barang in paginatedBarangList"
                                        :key="barang.id"
                                    >
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                barang.kode ||
                                                "(Tidak ada kode)"
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                barang.nama ||
                                                "(Tidak ada nama)"
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ barang.kategori }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ barang.stok }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                :class="[
                                                    barang.status === 'Tersedia'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                    'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                ]"
                                            >
                                                {{ barang.status }}
                                            </span>
                                        </td>
                                        <td
                                            v-if="isAdmin"
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                        >
                                            <button
                                                @click="editBarang(barang)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                                :disabled="isLoading"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="deleteBarang(barang.id)"
                                                class="text-red-600 hover:text-red-900 mr-2"
                                                :disabled="isLoading"
                                            >
                                                Hapus
                                            </button>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.peminjaman.barang',
                                                        { barang_id: barang.id }
                                                    )
                                                "
                                                class="text-emerald-600 hover:text-emerald-900"
                                                v-if="
                                                    barang.status === 'Dipinjam'
                                                "
                                            >
                                                Lihat Peminjam
                                            </Link>
                                        </td>
                                        <td
                                            v-else-if="
                                                !isAdmin &&
                                                barang.status === 'Tersedia'
                                            "
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'user.peminjaman.create',
                                                        { barang_id: barang.id }
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Pinjam
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="totalPages > 1"
                    class="flex justify-center items-center space-x-2 mt-6"
                >
                    <button
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        &laquo; Prev
                    </button>

                    <button
                        v-for="page in totalPages"
                        :key="page"
                        @click="changePage(page)"
                        :class="[
                            'px-3 py-1 rounded-md',
                            currentPage === page
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                        ]"
                    >
                        {{ page }}
                    </button>

                    <button
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next &raquo;
                    </button>

                    <div class="ml-4 text-sm text-gray-600">
                        Menampilkan {{ (currentPage - 1) * itemsPerPage + 1 }} -
                        {{
                            Math.min(
                                currentPage * itemsPerPage,
                                filteredBarangList.length
                            )
                        }}
                        dari {{ filteredBarangList.length }} data
                    </div>
                </div>
            </div>

            <!-- Modal Form - Hanya untuk Admin -->
            <div
                v-if="showModal && isAdmin"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
            >
                <div
                    class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
                >
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mt-3 text-center sm:mt-0 sm:text-left w-full"
                        >
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900"
                            >
                                {{
                                    editMode
                                        ? "Edit Barang"
                                        : "Tambah Barang Baru"
                                }}
                            </h3>
                            <div class="mt-4">
                                <form @submit.prevent="debouncedSubmit">
                                    <div class="grid grid-cols-1 gap-y-6">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Kode Barang</label
                                            >
                                            <input
                                                type="text"
                                                v-model="formData.kode"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Nama Barang</label
                                            >
                                            <input
                                                type="text"
                                                v-model="formData.nama"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Kategori</label
                                            >
                                            <select
                                                v-model="formData.kategori"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            >
                                                <option value="">
                                                    Pilih Kategori
                                                </option>
                                                <option value="Elektronik">
                                                    Elektronik
                                                </option>
                                                <option value="Furniture">
                                                    Furniture
                                                </option>
                                                <option value="Perlengkapan">
                                                    Perlengkapan
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Stok</label
                                            >
                                            <input
                                                type="number"
                                                v-model="formData.stok"
                                                min="0"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Status</label
                                            >
                                            <select
                                                v-model="formData.status"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            >
                                                <option value="Tersedia">
                                                    Tersedia
                                                </option>
                                                <option value="Dipinjam">
                                                    Dipinjam
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse"
                                    >
                                        <button
                                            type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                                            :disabled="isLoading"
                                        >
                                            {{ editMode ? "Update" : "Simpan" }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                        >
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import {
    ref,
    shallowRef,
    onMounted,
    onBeforeUnmount,
    nextTick,
    computed,
} from "vue";
import axios from "axios";
import { Head, usePage, Link } from "@inertiajs/vue3";
import MainLayout from "@/layouts/MainLayout.vue";
import { useToast } from "vue-toastification";

// Get toast instance
const toast = useToast();

// Mendapatkan informasi user dan role
const page = usePage();
const user = computed(() => {
    console.log("User data:", page.props.auth.user);
    return page.props.auth.user;
});

// Prioritaskan prop isAdmin dari controller jika tersedia
const isAdmin = computed(() => {
    // Jika prop isAdmin tersedia dari controller, gunakan itu
    if (typeof page.props.isAdmin !== "undefined") {
        console.log("Using isAdmin from props:", page.props.isAdmin);
        return page.props.isAdmin;
    }

    // Jika tidak, gunakan role dari user
    const role = user.value?.role;
    console.log(
        "Using role for isAdmin, role:",
        role,
        "isAdmin:",
        role === "admin"
    );
    return role === "admin";
});

const barangList = shallowRef([]);
const filteredBarangList = computed(() => {
    // Filter berdasarkan pencarian
    let filtered = barangList.value;
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (item) =>
                item.kode.toLowerCase().includes(query) ||
                item.nama.toLowerCase().includes(query) ||
                item.kategori.toLowerCase().includes(query)
        );
    }

    // Filter berdasarkan kategori
    if (filterKategori.value && filterKategori.value !== "Semua") {
        filtered = filtered.filter(
            (item) => item.kategori === filterKategori.value
        );
    }

    // Filter berdasarkan status
    if (filterStatus.value && filterStatus.value !== "Semua") {
        filtered = filtered.filter(
            (item) => item.status === filterStatus.value
        );
    }

    return filtered;
});

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);
const totalPages = computed(() =>
    Math.ceil(filteredBarangList.value.length / itemsPerPage.value)
);
const paginatedBarangList = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage.value;
    const endIndex = startIndex + itemsPerPage.value;
    return filteredBarangList.value.slice(startIndex, endIndex);
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// Filter dan search
const searchQuery = ref("");
const filterKategori = ref("Semua");
const filterStatus = ref("Semua");

// Reset pagination saat filter berubah
const resetPagination = () => {
    currentPage.value = 1;
};

// UI state
const showModal = ref(false);
const editMode = ref(false);
const isLoading = ref(true);
const formData = ref({
    kode: "",
    nama: "",
    kategori: "",
    stok: 0,
    status: "Tersedia",
});

// Menambahkan cancel token source
let cancelTokenSource = axios.CancelToken.source();

// Function untuk debounce
function debounce(fn, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    };
}

const debouncedSearch = debounce(() => {
    resetPagination();
}, 300);

// Fungsi untuk transformasi data
const transformasiData = (data) => {
    console.log("Transformasi data, sebelum:", data);

    const result = data.map((item) => {
        // Debug log untuk memeriksa struktur data item
        console.log("Data item original:", item);

        // Cek apakah ada properti tersembunyi lainnya
        for (const key in item) {
            console.log(`Property ${key}:`, item[key]);
        }

        // Jika data API menggunakan format kode_barang dan nama_barang,
        // kita transformasi menjadi kode dan nama yang diharapkan oleh template
        const transformed = {
            id: item.id,
            kode: item.kode || item.kode_barang || "",
            nama: item.nama || item.nama_barang || "",
            kategori: item.kategori,
            stok: item.stok,
            status: item.status,
        };

        console.log("Data item transformed:", transformed);
        return transformed;
    });

    console.log("Transformasi data, sesudah:", result);
    return result;
};

const fetchBarang = async () => {
    isLoading.value = true;
    try {
        console.log("Fetching barang data...");
        console.log("Current isAdmin value:", isAdmin.value);

        // Hapus cache lama untuk memastikan data terbaru
        localStorage.removeItem("barangData");
        localStorage.removeItem("barangDataTime");

        // PENTING: Pastikan API endpoint yang benar dipanggil berdasarkan role
        // Force isAdmin untuk routing admin
        const forceAdmin =
            typeof page.props.isAdmin !== "undefined"
                ? page.props.isAdmin
                : isAdmin.value;
        console.log("Using forceAdmin value:", forceAdmin);

        // Tambahkan parameter debug untuk testing
        const debug = "&debug=1";

        const apiUrl = forceAdmin ? "/api/barang" : "/api/user/barang";

        console.log("Using API URL:", apiUrl, "based on isAdmin:", forceAdmin);

        // Tambahkan timestamp untuk mencegah caching
        const timestamp = new Date().getTime();
        const cacheBuster = `?t=${timestamp}${debug}`;

        // Tambahkan CSRF token
        const token = document.head.querySelector('meta[name="csrf-token"]');

        const response = await axios.get(`${apiUrl}${cacheBuster}`, {
            cancelToken: cancelTokenSource.token,
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": token ? token.content : "",
                "X-Debug-Role": "bypass",
            },
        });

        console.log("Raw API response:", response);

        // Struktur data yang mungkin:
        // 1. response.data.barang = [...] - properti barang di dalam objek
        // 2. response.data = [...] - array langsung
        // 3. response.data = {data: [...]} - properti data dalam objek

        let barangData = null;

        if (response.data && Array.isArray(response.data)) {
            // Kasus 2: Array langsung
            console.log("Response berisi array langsung");
            barangData = response.data;
        } else if (
            response.data &&
            response.data.barang &&
            Array.isArray(response.data.barang)
        ) {
            // Kasus 1: Property barang
            console.log("Response berisi property barang");
            barangData = response.data.barang;
        } else if (
            response.data &&
            response.data.data &&
            Array.isArray(response.data.data)
        ) {
            // Kasus 3: Property data
            console.log("Response berisi property data");
            barangData = response.data.data;
        }

        if (barangData) {
            console.log(`Total data barang: ${barangData.length}`);
            console.log("Full barang data:", barangData);

            // Gunakan data yang telah diidentifikasi
            barangList.value = transformasiData(barangData);

            // Simpan data ke localStorage
            localStorage.setItem(
                "barangData",
                JSON.stringify(barangList.value)
            );
            localStorage.setItem(
                "barangDataTime",
                new Date().getTime().toString()
            );

            toast.success(
                `Data barang berhasil dimuat (${barangData.length} item)`
            );
        } else {
            console.error("Format data tidak sesuai:", response.data);
            toast.error("Format data dari server tidak valid");
        }
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error fetching barang:", error);
            console.error("Error response:", error.response?.data);
            toast.error(
                error.response?.data?.message ||
                    "Gagal memuat data barang. Silakan coba lagi."
            );
        }
    } finally {
        isLoading.value = false;
    }
};

// Debounce submit function untuk mencegah double submits
const debouncedSubmit = debounce(async () => {
    isLoading.value = true;
    try {
        // Log data form untuk debugging
        console.log("Form data yang akan dikirim:", formData.value);

        // Prepare data yang akan dikirim ke API
        const apiFormData = {
            nama: formData.value.nama,
            kode: formData.value.kode,
            kategori: formData.value.kategori,
            stok: formData.value.stok,
            status: formData.value.status || "Tersedia",
        };

        console.log("Data API yang sudah diformat:", apiFormData);

        // Tambahkan CSRF token
        const token = document.head.querySelector('meta[name="csrf-token"]');

        if (editMode.value) {
            console.log(`Updating barang dengan ID: ${formData.value.id}`);
            const response = await axios.put(
                `/api/barang/${formData.value.id}`,
                apiFormData,
                {
                    cancelToken: cancelTokenSource.token,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token ? token.content : "",
                    },
                }
            );
            console.log("Response update:", response.data);
            toast.success("Data barang berhasil diperbarui");
        } else {
            console.log("Membuat barang baru");
            const response = await axios.post("/api/barang", apiFormData, {
                cancelToken: cancelTokenSource.token,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token ? token.content : "",
                },
            });
            console.log("Response create:", response.data);
            toast.success("Data barang berhasil ditambahkan");
        }

        // Invalidate cache setelah update
        localStorage.removeItem("barangData");
        localStorage.removeItem("barangDataTime");

        // Tunggu sebentar untuk memastikan data tersimpan di server
        setTimeout(async () => {
            await fetchBarang();
            closeModal();
        }, 500);
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error submitting form:", error);
            console.error("Error response:", error.response?.data);
            toast.error(
                error.response?.data?.message ||
                    "Gagal menyimpan data barang. Silakan coba lagi."
            );
        }
    } finally {
        isLoading.value = false;
    }
}, 300);

const editBarang = (barang) => {
    editMode.value = true;

    // Pastikan format data sesuai dengan form
    formData.value = {
        id: barang.id,
        kode: barang.kode, // Property kode sudah di-transform
        nama: barang.nama, // Property nama sudah di-transform
        kategori: barang.kategori,
        stok: barang.stok,
        status: barang.status,
    };

    console.log("Editing barang:", formData.value);
    showModal.value = true;
};

const deleteBarang = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
        isLoading.value = true;
        try {
            const response = await axios.delete(`/api/barang/${id}`, {
                cancelToken: cancelTokenSource.token,
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            // Invalidate cache setelah delete
            localStorage.removeItem("barangData");
            localStorage.removeItem("barangDataTime");

            if (response.data && response.data.status === "success") {
                toast.success(
                    response.data.message || "Data barang berhasil dihapus"
                );
            } else {
                toast.success("Data barang berhasil dihapus");
            }

            await fetchBarang();
        } catch (error) {
            if (!axios.isCancel(error)) {
                console.error("Error deleting barang:", error);
                toast.error(
                    error.response?.data?.message ||
                        "Gagal menghapus data barang. Silakan coba lagi."
                );
            }
        } finally {
            isLoading.value = false;
        }
    }
};

const closeModal = () => {
    showModal.value = false;
    editMode.value = false;
    formData.value = {
        kode: "",
        nama: "",
        kategori: "",
        stok: 0,
        status: "Tersedia",
    };
};

onMounted(() => {
    // Reset cancel token pada mount
    cancelTokenSource = axios.CancelToken.source();

    // Log status admin
    console.log("Component mounted, isAdmin:", isAdmin.value);
    console.log("Page props:", page.props);

    // Delay loading untuk mengurangi beban awal
    nextTick(() => {
        fetchBarang();
    });
});

// Membatalkan semua permintaan API ketika komponen di-unmount
onBeforeUnmount(() => {
    cancelTokenSource.cancel("Component unmounted");
});

// Watch untuk search query
const onSearchChange = () => {
    debouncedSearch();
};
</script>
