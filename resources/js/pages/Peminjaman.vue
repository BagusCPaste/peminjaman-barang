<template>
    <Head title="Data Peminjaman" />

    <MainLayout>
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{
                            props.title ||
                            (isAdmin ? "Data Peminjaman" : "Peminjaman Saya")
                        }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">
                        {{
                            props.filter_barang
                                ? `Daftar peminjaman untuk barang: ${props.filter_barang.nama}`
                                : isAdmin
                                ? "Daftar semua peminjaman barang"
                                : "Daftar peminjaman barang yang telah Anda lakukan"
                        }}
                    </p>
                </div>
                <div v-if="!isAdmin" class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button
                        @click="showPeminjamanForm"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                        :disabled="isLoading"
                    >
                        Tambah Peminjaman
                    </button>
                </div>
            </div>

            <!-- Notifikasi role -->
            <div class="mt-2 mb-4">
                <div
                    v-if="isAdmin"
                    class="text-sm text-purple-600 bg-purple-50 p-2 rounded-md flex items-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                    <span
                        >Mode Admin: Anda dapat melihat semua peminjaman barang
                        dan mengelola status pengembalian</span
                    >
                </div>
                <div
                    v-else
                    class="text-sm text-blue-600 bg-blue-50 p-2 rounded-md flex items-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                    <span
                        >Mode Pengguna: Anda dapat menambah dan mengelola
                        peminjaman barang Anda sendiri</span
                    >
                </div>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading"
                class="flex justify-center items-center py-12"
            >
                <div class="animate-pulse text-lg text-gray-600">
                    Memuat data peminjaman...
                </div>
            </div>

            <!-- Tabel Peminjaman -->
            <div v-else class="mt-8 flex flex-col">
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
                                            Peminjam
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Barang
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Tanggal Pinjam
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Tanggal Kembali
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Status
                                        </th>
                                        <th
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
                                    <tr
                                        v-for="peminjaman in peminjamanList"
                                        :key="peminjaman.id"
                                    >
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900"
                                        >
                                            {{ peminjaman.pengguna?.nama }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <ul>
                                                <li
                                                    v-for="detail in peminjaman.detail_peminjaman"
                                                    :key="detail.id"
                                                >
                                                    {{ detail.barang?.nama }}
                                                    ({{ detail.jumlah }}
                                                    unit)
                                                </li>
                                            </ul>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                formatTanggal(
                                                    peminjaman.tanggal_pinjam
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                formatTanggal(
                                                    peminjaman.tanggal_kembali
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                :class="[
                                                    peminjaman.status ===
                                                    'Dipinjam'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-green-100 text-green-800',
                                                    'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                ]"
                                            >
                                                {{ peminjaman.status }}
                                            </span>
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                        >
                                            <button
                                                v-if="
                                                    peminjaman.status ===
                                                        'Dipinjam' &&
                                                    (isAdmin ||
                                                        peminjaman.pengguna_id ===
                                                            user?.id)
                                                "
                                                @click="
                                                    kembalikanBarang(
                                                        peminjaman.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-2 inline-flex items-center"
                                                :disabled="
                                                    isProcessingId ===
                                                    peminjaman.id
                                                "
                                            >
                                                <svg
                                                    v-if="
                                                        isProcessingId ===
                                                        peminjaman.id
                                                    "
                                                    class="animate-spin -ml-1 mr-1 h-4 w-4 text-indigo-600"
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
                                                Kembalikan
                                            </button>
                                            <button
                                                v-if="
                                                    isAdmin ||
                                                    (peminjaman.status !==
                                                        'Dipinjam' &&
                                                        peminjaman.pengguna_id ===
                                                            user?.id)
                                                "
                                                @click="
                                                    deletePeminjaman(
                                                        peminjaman.id
                                                    )
                                                "
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                :disabled="
                                                    isProcessingId ===
                                                    peminjaman.id
                                                "
                                            >
                                                <svg
                                                    v-if="
                                                        isProcessingId ===
                                                        peminjaman.id
                                                    "
                                                    class="animate-spin -ml-1 mr-1 h-4 w-4 text-red-600"
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
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Form -->
            <div
                v-if="showModal && !isAdmin"
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
                                Tambah Peminjaman Baru
                            </h3>
                            <div class="mt-4">
                                <form @submit="submitForm">
                                    <div class="grid grid-cols-1 gap-y-6">
                                        <div v-if="isAdmin">
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Peminjam</label
                                            >
                                            <select
                                                v-model="formData.pengguna_id"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            >
                                                <option value="">
                                                    Pilih Peminjam
                                                </option>
                                                <option
                                                    v-for="pengguna in penggunaList"
                                                    :key="pengguna.id"
                                                    :value="pengguna.id"
                                                >
                                                    {{ pengguna.nama }}
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Barang yang Dipinjam</label
                                            >
                                            <div
                                                v-for="(
                                                    item, index
                                                ) in formData.items"
                                                :key="index"
                                                class="mt-2 flex gap-2"
                                            >
                                                <select
                                                    v-model="item.barang_id"
                                                    class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    required
                                                >
                                                    <option value="">
                                                        Pilih Barang
                                                    </option>
                                                    <option
                                                        v-for="barang in barangList.filter(
                                                            (b) =>
                                                                b.status ===
                                                                    'Tersedia' &&
                                                                b.stok > 0
                                                        )"
                                                        :key="barang.id"
                                                        :value="barang.id"
                                                    >
                                                        {{ barang.nama }} ({{
                                                            barang.kode
                                                        }}) - Stok:
                                                        {{ barang.stok }}
                                                    </option>
                                                </select>
                                                <input
                                                    type="number"
                                                    v-model="item.jumlah"
                                                    min="1"
                                                    placeholder="Jumlah"
                                                    class="w-24 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    required
                                                />
                                                <button
                                                    type="button"
                                                    @click="removeItem(index)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Hapus
                                                </button>
                                            </div>
                                            <div
                                                v-if="
                                                    barangList.filter(
                                                        (b) =>
                                                            b.status ===
                                                                'Tersedia' &&
                                                            b.stok > 0
                                                    ).length === 0
                                                "
                                                class="mt-2 text-sm text-red-600"
                                            >
                                                Tidak ada barang tersedia untuk
                                                dipinjam saat ini.
                                            </div>
                                            <div
                                                v-if="barangList.length === 0"
                                                class="mt-2 text-sm text-red-600"
                                            >
                                                Gagal memuat data barang.
                                                Silakan refresh halaman.
                                            </div>
                                            <button
                                                type="button"
                                                @click="addItem"
                                                class="mt-2 text-sm text-indigo-600 hover:text-indigo-900"
                                                :disabled="
                                                    barangList.filter(
                                                        (b) =>
                                                            b.status ===
                                                                'Tersedia' &&
                                                            b.stok > 0
                                                    ).length === 0
                                                "
                                            >
                                                + Tambah Barang
                                            </button>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Tanggal Pinjam</label
                                            >
                                            <input
                                                type="date"
                                                v-model="
                                                    formData.tanggal_pinjam
                                                "
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Tanggal Kembali</label
                                            >
                                            <input
                                                type="date"
                                                v-model="
                                                    formData.tanggal_kembali
                                                "
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
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
                                            <svg
                                                v-if="isLoading"
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
                                            {{
                                                isLoading
                                                    ? "Menyimpan..."
                                                    : "Simpan"
                                            }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                            :disabled="isLoading"
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
import { Head, usePage } from "@inertiajs/vue3";
import MainLayout from "@/layouts/MainLayout.vue";
import { useToast } from "vue-toastification";

// Inisialisasi toast
const toast = useToast();

// Mendapatkan informasi user dan role
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === "admin");

const props = defineProps({
    peminjaman: {
        type: Array,
        default: () => [],
    },
    isAdmin: {
        type: Boolean,
        default: false,
    },
    filter_barang: {
        type: Object,
        default: null,
    },
    title: {
        type: String,
        default: "",
    },
});

const peminjamanList = shallowRef([]);
const penggunaList = shallowRef([]);
const barangList = shallowRef([]);
const showModal = ref(false);
const isLoading = ref(true);
const isProcessingId = ref(null);

// Menambahkan cancel token source
let cancelTokenSource = axios.CancelToken.source();

const formData = ref({
    pengguna_id: "",
    tanggal_pinjam: new Date().toISOString().substr(0, 10),
    tanggal_kembali: new Date(new Date().setDate(new Date().getDate() + 7))
        .toISOString()
        .substr(0, 10),
    items: [{ barang_id: "", jumlah: 1 }],
});

// Function untuk debounce
function debounce(fn, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    };
}

const formatTanggal = (tanggal) => {
    if (!tanggal) return "-";
    return new Date(tanggal).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const fetchData = async () => {
    isLoading.value = true;
    try {
        // Generate key cache yang berbeda untuk admin dan user
        const cacheKeyPrefix = isAdmin.value ? "admin_" : "user_";
        const userId = user.value?.id || "guest";

        console.log(
            `[Peminjaman] Info user: ID=${userId}, isAdmin=${isAdmin.value}`
        );

        // Bersihkan dulu semua cache lama terkait peminjaman
        const timestamp = new Date().getTime();

        // Ambil data barang terlebih dahulu (prioritas tertinggi)
        try {
            console.log(`[Peminjaman] Mengambil data barang`);
            const barangRes = await axios.get("/api/barang", {
                params: { timestamp },
            });

            console.log(
                `[Peminjaman] Respon data barang:`,
                typeof barangRes.data,
                barangRes.data
            );

            // Ekstrak data barang dari berbagai format response
            let dataBarang = [];

            if (Array.isArray(barangRes.data)) {
                // Jika langsung array
                dataBarang = barangRes.data;
                console.log(
                    `[Peminjaman] Data barang sudah dalam format array`
                );
            } else if (barangRes.data && typeof barangRes.data === "object") {
                // Jika object dengan property barang atau status
                if (
                    barangRes.data.barang &&
                    Array.isArray(barangRes.data.barang)
                ) {
                    dataBarang = barangRes.data.barang;
                    console.log(
                        `[Peminjaman] Data barang diambil dari property 'barang'`
                    );
                } else if (
                    barangRes.data.status === "success" &&
                    barangRes.data.barang
                ) {
                    dataBarang = barangRes.data.barang;
                    console.log(
                        `[Peminjaman] Data barang diambil dari property success.barang`
                    );
                } else {
                    // Coba ambil dari object itu sendiri jika punya struktur barang
                    const keys = Object.keys(barangRes.data);
                    if (
                        keys.length > 0 &&
                        typeof barangRes.data[keys[0]] === "object"
                    ) {
                        dataBarang = Object.values(barangRes.data);
                        console.log(
                            `[Peminjaman] Data barang diambil dari values object`
                        );
                    }
                }
            }

            if (dataBarang.length > 0) {
                barangList.value = dataBarang;
                console.log(
                    `[Peminjaman] Berhasil memuat ${barangList.value.length} barang`
                );

                // Cek dan log ketersediaan barang
                const tersedia = barangList.value.filter(
                    (b) => b.status === "Tersedia" && b.stok > 0
                ).length;
                console.log(
                    `[Peminjaman] Terdapat ${tersedia} barang tersedia untuk dipinjam`
                );

                // Simpan ke cache
                localStorage.setItem(
                    `${cacheKeyPrefix}barangData`,
                    JSON.stringify(dataBarang)
                );
            } else {
                console.error(
                    "[Peminjaman] Tidak dapat menemukan data barang dalam response:",
                    barangRes.data
                );
                toast.error("Format data barang tidak valid");

                // Coba gunakan cache barang jika ada
                const cachedBarangList = localStorage.getItem(
                    `${cacheKeyPrefix}barangData`
                );
                if (cachedBarangList) {
                    try {
                        barangList.value = JSON.parse(cachedBarangList);
                        console.log(
                            `[Peminjaman] Menggunakan cache untuk ${barangList.value.length} barang`
                        );
                    } catch (e) {
                        console.error(
                            "[Peminjaman] Error parsing cached barang data:",
                            e
                        );
                    }
                }
            }
        } catch (barangError) {
            console.error(
                "[Peminjaman] Error fetching barang data:",
                barangError
            );
            toast.error(
                "Gagal memuat data barang: " +
                    (barangError.response?.data?.message || "Terjadi kesalahan")
            );

            // Coba gunakan cache barang jika ada
            const cachedBarangList = localStorage.getItem(
                `${cacheKeyPrefix}barangData`
            );
            if (cachedBarangList) {
                try {
                    barangList.value = JSON.parse(cachedBarangList);
                    console.log(
                        `[Peminjaman] Menggunakan cache untuk ${barangList.value.length} barang`
                    );
                } catch (e) {
                    console.error(
                        "[Peminjaman] Error parsing cached barang data:",
                        e
                    );
                }
            }
        }

        // Admin perlu data pengguna, user tidak
        if (isAdmin.value) {
            try {
                console.log(
                    `[Peminjaman] Mengambil data pengguna (admin mode)`
                );
                const penggunaRes = await axios.get("/api/pengguna", {
                    params: { timestamp },
                });

                console.log(
                    `[Peminjaman] Respon data pengguna:`,
                    typeof penggunaRes.data,
                    penggunaRes.data
                );

                // Ekstrak data pengguna dari berbagai format response
                let dataPengguna = [];

                if (Array.isArray(penggunaRes.data)) {
                    // Jika langsung array
                    dataPengguna = penggunaRes.data;
                } else if (
                    penggunaRes.data &&
                    typeof penggunaRes.data === "object"
                ) {
                    // Jika object dengan property tertentu
                    if (
                        penggunaRes.data.data &&
                        Array.isArray(penggunaRes.data.data)
                    ) {
                        dataPengguna = penggunaRes.data.data;
                    } else if (
                        penggunaRes.data.pengguna &&
                        Array.isArray(penggunaRes.data.pengguna)
                    ) {
                        dataPengguna = penggunaRes.data.pengguna;
                    } else if (
                        penggunaRes.data.status === "success" &&
                        penggunaRes.data.data
                    ) {
                        dataPengguna = penggunaRes.data.data;
                    } else {
                        // Coba ambil dari object itu sendiri jika punya struktur pengguna
                        const keys = Object.keys(penggunaRes.data);
                        if (
                            keys.length > 0 &&
                            typeof penggunaRes.data[keys[0]] === "object"
                        ) {
                            dataPengguna = Object.values(penggunaRes.data);
                        }
                    }
                }

                if (dataPengguna.length > 0) {
                    penggunaList.value = dataPengguna;
                    console.log(
                        `[Peminjaman] Berhasil memuat ${penggunaList.value.length} pengguna`
                    );

                    // Simpan ke cache
                    localStorage.setItem(
                        `${cacheKeyPrefix}penggunaData`,
                        JSON.stringify(dataPengguna)
                    );
                } else {
                    console.error(
                        "[Peminjaman] Tidak dapat menemukan data pengguna dalam response"
                    );

                    // Coba gunakan cache pengguna jika ada
                    const cachedPenggunaList = localStorage.getItem(
                        `${cacheKeyPrefix}penggunaData`
                    );
                    if (cachedPenggunaList) {
                        try {
                            penggunaList.value = JSON.parse(cachedPenggunaList);
                            console.log(
                                `[Peminjaman] Menggunakan cache untuk ${penggunaList.value.length} pengguna`
                            );
                        } catch (e) {
                            console.error(
                                "[Peminjaman] Error parsing cached pengguna data:",
                                e
                            );
                        }
                    }
                }
            } catch (penggunaError) {
                console.error(
                    "[Peminjaman] Error fetching pengguna data:",
                    penggunaError
                );

                // Coba gunakan cache pengguna jika ada
                const cachedPenggunaList = localStorage.getItem(
                    `${cacheKeyPrefix}penggunaData`
                );
                if (cachedPenggunaList) {
                    try {
                        penggunaList.value = JSON.parse(cachedPenggunaList);
                        console.log(
                            `[Peminjaman] Menggunakan cache untuk ${penggunaList.value.length} pengguna`
                        );
                    } catch (e) {
                        console.error(
                            "[Peminjaman] Error parsing cached pengguna data:",
                            e
                        );
                    }
                }
            }
        }

        // Ambil data peminjaman berdasarkan role
        try {
            const apiUrl = isAdmin.value
                ? "/api/peminjaman"
                : `/api/peminjaman/user/${user.value?.id}`;

            console.log(
                `[Peminjaman] Mengambil data peminjaman dari: ${apiUrl}`
            );

            const peminjamanRes = await axios.get(apiUrl, {
                params: { timestamp },
            });

            console.log(
                `[Peminjaman] Respon data peminjaman:`,
                typeof peminjamanRes.data,
                peminjamanRes.data
            );

            // Ekstrak data peminjaman dari berbagai format response
            let dataPeminjaman = [];

            if (Array.isArray(peminjamanRes.data)) {
                // Jika langsung array
                dataPeminjaman = peminjamanRes.data;
                console.log(
                    `[Peminjaman] Data peminjaman sudah dalam format array`
                );
            } else if (
                peminjamanRes.data &&
                typeof peminjamanRes.data === "object"
            ) {
                // Jika object dengan property tertentu
                if (
                    peminjamanRes.data.data &&
                    Array.isArray(peminjamanRes.data.data)
                ) {
                    dataPeminjaman = peminjamanRes.data.data;
                    console.log(
                        `[Peminjaman] Data peminjaman diambil dari property 'data'`
                    );
                } else if (
                    peminjamanRes.data.peminjaman &&
                    Array.isArray(peminjamanRes.data.peminjaman)
                ) {
                    dataPeminjaman = peminjamanRes.data.peminjaman;
                    console.log(
                        `[Peminjaman] Data peminjaman diambil dari property 'peminjaman'`
                    );
                } else if (
                    peminjamanRes.data.status === "success" &&
                    peminjamanRes.data.data
                ) {
                    dataPeminjaman = peminjamanRes.data.data;
                    console.log(
                        `[Peminjaman] Data peminjaman diambil dari property 'success.data'`
                    );
                } else {
                    // Coba ambil dari object itu sendiri jika punya struktur peminjaman
                    const keys = Object.keys(peminjamanRes.data);
                    if (
                        keys.length > 0 &&
                        typeof peminjamanRes.data[keys[0]] === "object"
                    ) {
                        dataPeminjaman = Object.values(peminjamanRes.data);
                        console.log(
                            `[Peminjaman] Data peminjaman diambil dari values object`
                        );
                    }
                }
            }

            if (dataPeminjaman.length > 0) {
                peminjamanList.value = dataPeminjaman;
                console.log(
                    `[Peminjaman] Berhasil memuat ${peminjamanList.value.length} peminjaman`
                );

                // Simpan ke cache
                localStorage.setItem(
                    `${cacheKeyPrefix}peminjamanData_${userId}`,
                    JSON.stringify(dataPeminjaman)
                );
                localStorage.setItem(
                    `${cacheKeyPrefix}peminjamanDataTime_${userId}`,
                    timestamp.toString()
                );
            } else {
                console.error(
                    "[Peminjaman] Tidak dapat menemukan data peminjaman dalam response:",
                    peminjamanRes.data
                );
                toast.error(
                    "Format data peminjaman tidak valid, harap refresh halaman"
                );

                // Coba gunakan cache peminjaman jika ada
                const cachedPeminjaman = localStorage.getItem(
                    `${cacheKeyPrefix}peminjamanData_${userId}`
                );
                if (cachedPeminjaman) {
                    try {
                        peminjamanList.value = JSON.parse(cachedPeminjaman);
                        console.log(
                            `[Peminjaman] Menggunakan cache untuk ${peminjamanList.value.length} peminjaman`
                        );
                    } catch (e) {
                        console.error(
                            "[Peminjaman] Error parsing cached peminjaman data:",
                            e
                        );
                    }
                }
            }
        } catch (peminjamanError) {
            console.error(
                "[Peminjaman] Error fetching peminjaman data:",
                peminjamanError
            );

            if (peminjamanError.response?.status === 404) {
                console.log(
                    "[Peminjaman] API endpoint tidak ditemukan, kemungkinan route belum terdaftar"
                );
                toast.error(
                    "Route API peminjaman tidak ditemukan. Pastikan server berjalan dengan benar"
                );
            } else {
                toast.error(
                    "Gagal memuat data peminjaman: " +
                        (peminjamanError.response?.data?.message ||
                            "Terjadi kesalahan")
                );
            }

            // Coba gunakan cache peminjaman jika ada
            const cachedPeminjaman = localStorage.getItem(
                `${cacheKeyPrefix}peminjamanData_${userId}`
            );
            if (cachedPeminjaman) {
                try {
                    peminjamanList.value = JSON.parse(cachedPeminjaman);
                    console.log(
                        `[Peminjaman] Menggunakan cache untuk ${peminjamanList.value.length} peminjaman`
                    );
                } catch (e) {
                    console.error(
                        "[Peminjaman] Error parsing cached peminjaman data:",
                        e
                    );
                }
            }
        }

        console.log(`[Peminjaman] Proses load data selesai`);
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("[Peminjaman] Error fetching data:", error);
            toast.error(
                "Gagal memuat data: " +
                    (error.response?.data?.message ||
                        "Terjadi kesalahan server")
            );
        }
    } finally {
        isLoading.value = false;
    }
};

const addItem = () => {
    formData.value.items.push({ barang_id: "", jumlah: 1 });
};

const removeItem = (index) => {
    formData.value.items.splice(index, 1);
};

// Debounce submit function untuk mencegah double submits
const debouncedSubmit = debounce(async () => {
    isLoading.value = true;

    try {
        // Validasi form data sebelum mengirim ke server
        if (formData.value.items.length === 0) {
            toast.error("Tambahkan minimal satu barang untuk dipinjam");
            isLoading.value = false;
            return;
        }

        // Periksa jika ada barang yang belum dipilih
        const invalidItems = formData.value.items.filter(
            (item) => !item.barang_id || item.jumlah < 1
        );
        if (invalidItems.length > 0) {
            toast.error(
                "Pastikan semua barang dipilih dan jumlahnya minimal 1"
            );
            isLoading.value = false;
            return;
        }

        // Kita tidak perlu set pengguna_id lagi karena akan ditangani oleh controller
        // Hapus baris pengguna_id jika bukan admin dan biarkan controller yang menangani
        const dataToSubmit = { ...formData.value };

        if (!isAdmin.value) {
            // Jika bukan admin, kita tidak perlu mengirim pengguna_id
            // Controller akan menetapkan pengguna yang sesuai dengan user yang login
            delete dataToSubmit.pengguna_id;
        } else if (!formData.value.pengguna_id) {
            toast.error("Pilih pengguna untuk peminjaman ini");
            isLoading.value = false;
            return;
        }

        console.log("[Peminjaman] Mengirim data peminjaman:", dataToSubmit);

        const response = await axios.post("/api/peminjaman", dataToSubmit);

        console.log("[Peminjaman] Respon dari server:", response.data);

        // Invalidate cache setelah berhasil submit
        invalidateCache();

        // Tutup modal dulu sebelum memperbarui data
        closeModal();

        // Tampilkan pesan sukses
        toast.success("Peminjaman berhasil ditambahkan!");

        // Tunggu sebentar untuk memastikan data sudah masuk ke database
        setTimeout(async () => {
            try {
                // Ambil data peminjaman langsung dari API tanpa menggunakan cache
                const apiUrl = isAdmin.value
                    ? "/api/peminjaman"
                    : `/api/peminjaman/user/${user.value?.id}`;

                const timestamp = new Date().getTime();
                const freshPeminjamanRes = await axios.get(apiUrl, {
                    params: { timestamp },
                    headers: { "Cache-Control": "no-cache" },
                });

                // Perbarui data peminjaman
                if (Array.isArray(freshPeminjamanRes.data)) {
                    peminjamanList.value = freshPeminjamanRes.data;
                } else if (
                    freshPeminjamanRes.data &&
                    typeof freshPeminjamanRes.data === "object"
                ) {
                    if (
                        freshPeminjamanRes.data.data &&
                        Array.isArray(freshPeminjamanRes.data.data)
                    ) {
                        peminjamanList.value = freshPeminjamanRes.data.data;
                    } else if (
                        freshPeminjamanRes.data.peminjaman &&
                        Array.isArray(freshPeminjamanRes.data.peminjaman)
                    ) {
                        peminjamanList.value =
                            freshPeminjamanRes.data.peminjaman;
                    } else {
                        await fetchData(); // Fallback ke fetchData reguler
                    }
                } else {
                    await fetchData(); // Fallback ke fetchData reguler
                }
            } catch (err) {
                console.error(
                    "[Peminjaman] Error refreshing data after submit:",
                    err
                );
                await fetchData(); // Tetap coba fetchData jika ada error
            }
        }, 500);
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("[Peminjaman] Error submitting form:", error);

            if (error.response?.data?.errors) {
                // Tampilkan error validasi dari server jika ada
                const errorMessages = Object.values(
                    error.response.data.errors
                ).flat();
                errorMessages.forEach((message) => toast.error(message));
            } else {
                toast.error(
                    "Gagal menambahkan peminjaman: " +
                        (error.response?.data?.message || "Terjadi kesalahan")
                );
            }
        }
    } finally {
        isLoading.value = false;
    }
}, 300);

const submitForm = (e) => {
    e.preventDefault();
    debouncedSubmit();
};

const kembalikanBarang = async (id) => {
    if (isProcessingId.value) return; // Cegah multiple request bersamaan
    isProcessingId.value = id;

    try {
        await axios.put(
            `/api/peminjaman/${id}/return`,
            {},
            {
                cancelToken: cancelTokenSource.token,
            }
        );

        // Invalidate cache
        invalidateCache();

        await fetchData();
        toast.success("Barang berhasil dikembalikan!");
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error returning items:", error);
            toast.error(
                "Gagal mengembalikan barang: " +
                    (error.response?.data?.message || "Terjadi kesalahan")
            );
        }
    } finally {
        isProcessingId.value = null;
    }
};

const deletePeminjaman = async (id) => {
    if (isProcessingId.value) return; // Cegah multiple request bersamaan

    if (confirm("Apakah Anda yakin ingin menghapus data peminjaman ini?")) {
        isProcessingId.value = id;

        try {
            await axios.delete(`/api/peminjaman/${id}`, {
                cancelToken: cancelTokenSource.token,
            });

            // Invalidate cache
            invalidateCache();

            await fetchData();
            toast.success("Peminjaman berhasil dihapus!");
        } catch (error) {
            if (!axios.isCancel(error)) {
                console.error("Error deleting peminjaman:", error);
                toast.error(
                    "Gagal menghapus peminjaman: " +
                        (error.response?.data?.message || "Terjadi kesalahan")
                );
            }
        } finally {
            isProcessingId.value = null;
        }
    }
};

const closeModal = () => {
    showModal.value = false;
    formData.value = {
        pengguna_id: "",
        tanggal_pinjam: new Date().toISOString().substr(0, 10),
        tanggal_kembali: new Date(new Date().setDate(new Date().getDate() + 7))
            .toISOString()
            .substr(0, 10),
        items: [{ barang_id: "", jumlah: 1 }],
    };
};

const invalidateCache = () => {
    const cacheKeyPrefix = isAdmin.value ? "admin_" : "user_";
    const userId = user.value?.id || "guest";

    localStorage.removeItem(`${cacheKeyPrefix}peminjamanData_${userId}`);
    localStorage.removeItem(`${cacheKeyPrefix}peminjamanDataTime_${userId}`);

    // Juga invalidate cache untuk role lain jika action dilakukan oleh admin
    if (isAdmin.value) {
        // Mencari semua item yang mungkin ada di localStorage untuk user prefix
        const keysToRemove = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith("user_peminjamanData_")) {
                keysToRemove.push(key);
            }
            if (key && key.startsWith("user_peminjamanDataTime_")) {
                keysToRemove.push(key);
            }
        }
        keysToRemove.forEach((key) => localStorage.removeItem(key));
    }
};

const showPeminjamanForm = () => {
    // Reset form data terlebih dahulu
    formData.value = {
        pengguna_id: "",
        tanggal_pinjam: new Date().toISOString().substr(0, 10),
        tanggal_kembali: new Date(new Date().setDate(new Date().getDate() + 7))
            .toISOString()
            .substr(0, 10),
        items: [{ barang_id: "", jumlah: 1 }],
    };

    // Set pengguna_id ke ID pengguna saat ini jika bukan admin
    if (!isAdmin.value) {
        formData.value.pengguna_id = user.value?.id;
    }

    // Periksa apakah ada barang tersedia sebelum membuka modal
    const tersedia = barangList.value.filter(
        (b) => b.status === "Tersedia" && b.stok > 0
    ).length;
    if (tersedia === 0) {
        toast.warning("Tidak ada barang tersedia untuk dipinjam saat ini");
    }

    showModal.value = true;
};

onMounted(() => {
    // Reset cancel token pada mount
    cancelTokenSource = axios.CancelToken.source();
    // Delay loading untuk mengurangi beban awal
    nextTick(() => {
        fetchData();
    });
});

// Membatalkan semua permintaan API ketika komponen di-unmount
onBeforeUnmount(() => {
    cancelTokenSource.cancel("Component unmounted");
});
</script>
