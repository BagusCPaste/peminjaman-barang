<template>
    <Head title="Data Pengguna" />

    <MainLayout>
        <div v-if="isAdmin" class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Data Pengguna
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Daftar semua pengguna yang terdaftar dalam sistem
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button
                        @click="showModal = true"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                        :disabled="isLoading"
                    >
                        Tambah Pengguna
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div
                v-if="isLoading"
                class="flex justify-center items-center py-12"
            >
                <div class="animate-pulse text-lg text-gray-600">
                    Memuat data pengguna...
                </div>
            </div>

            <!-- Tabel Pengguna -->
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
                                            Nama
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Email
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Nomor Telepon
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Alamat
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                        >
                                            Role
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
                                        v-for="pengguna in penggunaList"
                                        :key="pengguna.id"
                                    >
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900"
                                        >
                                            {{ pengguna.nama }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ pengguna.email }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ pengguna.nomor_telepon }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            {{ pengguna.alamat }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                        >
                                            <span
                                                :class="[
                                                    pengguna.role === 'admin'
                                                        ? 'bg-purple-100 text-purple-800'
                                                        : 'bg-blue-100 text-blue-800',
                                                    'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                ]"
                                            >
                                                {{ pengguna.role }}
                                            </span>
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                        >
                                            <button
                                                @click="editPengguna(pengguna)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-2"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    deletePengguna(pengguna.id)
                                                "
                                                class="text-red-600 hover:text-red-900"
                                                :disabled="
                                                    pengguna.id === user?.id
                                                "
                                            >
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
                v-if="showModal"
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
                                        ? "Edit Pengguna"
                                        : "Tambah Pengguna Baru"
                                }}
                            </h3>
                            <div class="mt-4">
                                <form @submit.prevent="submitForm">
                                    <div class="grid grid-cols-1 gap-y-6">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Nama</label
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
                                                >Email</label
                                            >
                                            <input
                                                type="email"
                                                v-model="formData.email"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Nomor Telepon</label
                                            >
                                            <input
                                                type="tel"
                                                v-model="formData.nomor_telepon"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Alamat</label
                                            >
                                            <textarea
                                                v-model="formData.alamat"
                                                rows="3"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            ></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Role</label
                                            >
                                            <select
                                                v-model="formData.role"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required
                                            >
                                                <option value="user">
                                                    User
                                                </option>
                                                <option value="admin">
                                                    Admin
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
import { Head, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useToast } from "vue-toastification";

// Inisialisasi toast
const toast = useToast();

// Mendapatkan informasi user dan role
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === "admin");

// Redirect ke dashboard jika bukan admin
if (!isAdmin.value) {
    router.visit(route("dashboard"));
}

const penggunaList = shallowRef([]);
const showModal = ref(false);
const editMode = ref(false);
const isLoading = ref(true);

// Menambahkan cancel token source
let cancelTokenSource = axios.CancelToken.source();

const formData = ref({
    nama: "",
    email: "",
    nomor_telepon: "",
    alamat: "",
    role: "user", // Tambahkan field role
});

// Function untuk debounce
function debounce(fn, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    };
}

const fetchPengguna = async () => {
    isLoading.value = true;
    try {
        // Cek apakah data ada di localStorage dan masih fresh (< 5 menit)
        const cachedData = localStorage.getItem("penggunaData");
        const cachedTime = localStorage.getItem("penggunaDataTime");

        if (cachedData && cachedTime) {
            const now = new Date().getTime();
            const cacheAge = now - parseInt(cachedTime);

            // Gunakan cache jika kurang dari 5 menit
            if (cacheAge < 5 * 60 * 1000) {
                penggunaList.value = JSON.parse(cachedData);
                isLoading.value = false;
                return;
            }
        }

        // Jika tidak ada cache atau cache sudah kadaluarsa
        const response = await axios.get("/api/pengguna", {
            cancelToken: cancelTokenSource.token,
        });
        penggunaList.value = response.data;

        // Simpan data ke localStorage
        localStorage.setItem("penggunaData", JSON.stringify(response.data));
        localStorage.setItem(
            "penggunaDataTime",
            new Date().getTime().toString()
        );
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error fetching pengguna:", error);
        }
    } finally {
        isLoading.value = false;
    }
};

// Debounce submit function untuk mencegah double submits
const debouncedSubmit = debounce(async () => {
    isLoading.value = true;
    try {
        if (editMode.value) {
            await axios.put(
                `/api/pengguna/${formData.value.id}`,
                formData.value,
                {
                    cancelToken: cancelTokenSource.token,
                }
            );
            toast.success("Data pengguna berhasil diperbarui!");
        } else {
            await axios.post("/api/pengguna", formData.value, {
                cancelToken: cancelTokenSource.token,
            });
            toast.success("Pengguna baru berhasil ditambahkan!");
        }
        // Invalidate cache
        localStorage.removeItem("penggunaData");
        localStorage.removeItem("penggunaDataTime");

        await fetchPengguna();
        closeModal();
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error("Error submitting form:", error);
            toast.error("Gagal menyimpan data pengguna: " + (error.response?.data?.message || "Terjadi kesalahan"));
        }
    } finally {
        isLoading.value = false;
    }
}, 300);

const submitForm = (e) => {
    e.preventDefault();
    debouncedSubmit();
};

const editPengguna = (pengguna) => {
    editMode.value = true;
    formData.value = { ...pengguna };
    showModal.value = true;
};

const deletePengguna = async (id) => {
    // Jangan izinkan penghapusan pengguna sendiri
    if (id === user.value.id) {
        toast.error("Anda tidak dapat menghapus akun Anda sendiri");
        return;
    }

    if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
        isLoading.value = true;
        try {
            await axios.delete(`/api/pengguna/${id}`, {
                cancelToken: cancelTokenSource.token,
            });
            // Invalidate cache
            localStorage.removeItem("penggunaData");
            localStorage.removeItem("penggunaDataTime");

            await fetchPengguna();
            toast.success("Pengguna berhasil dihapus!");
        } catch (error) {
            if (!axios.isCancel(error)) {
                console.error("Error deleting pengguna:", error);
                toast.error("Gagal menghapus pengguna: " + (error.response?.data?.message || "Terjadi kesalahan"));
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
        nama: "",
        email: "",
        nomor_telepon: "",
        alamat: "",
        role: "user",
    };
};

onMounted(() => {
    // Reset cancel token pada mount
    cancelTokenSource = axios.CancelToken.source();
    // Delay loading untuk mengurangi beban awal
    nextTick(() => {
        fetchPengguna();
    });
});

// Membatalkan semua permintaan API ketika komponen di-unmount
onBeforeUnmount(() => {
    cancelTokenSource.cancel("Component unmounted");
});
</script>
