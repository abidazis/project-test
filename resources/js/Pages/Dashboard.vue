<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

// Mengambil data user dari props global (disediakan oleh middleware HandleInertiaRequests)
const props = defineProps({
    auth: Object
});

const form = useForm({});

const logout = () => {
    form.post(route('logout'));
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-gray-50 flex flex-col">
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-2xl font-black text-gray-900 tracking-tighter">
                            A<span class="text-indigo-600">+</span>W<span class="text-indigo-600">+</span>P
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">
                            Hi, {{ $page.props.auth.user.nama_lengkap }}
                        </span>
                        <button 
                            @click="logout"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">
                                Selamat Datang Kembali!
                            </h2>
                            <p class="mt-2 text-lg text-gray-600">
                                Senang melihat Anda kembali, <span class="font-semibold text-indigo-600">{{ $page.props.auth.user.nama_lengkap }}</span>.
                            </p>
                        </div>
                        <div class="mt-6 md:mt-0">
                            <div class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 text-sm font-medium">
                                <span class="flex h-2 w-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                                Akun Terverifikasi
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Login</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">12</div>
                        <div class="mt-4 flex items-center text-sm text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            <span>Meningkat 5%</span>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="text-sm font-medium text-gray-500 truncate">Email Akun</div>
                        <div class="mt-1 text-xl font-bold text-gray-900 truncate">
                            {{ $page.props.auth.user.email }}
                        </div>
                        <div class="mt-4 text-sm text-gray-400">
                            Status: <span class="text-indigo-600 font-medium">Aktif</span>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="text-sm font-medium text-gray-500 truncate">Sistem Saat Ini</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">Project Test</div>
                        <div class="mt-4 text-sm text-gray-400 font-medium italic">
                            A+W+P v1.0
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-2 border-dashed border-gray-200 rounded-2xl h-64 flex items-center justify-center">
                    <p class="text-gray-400 font-medium tracking-wide">Data tabel atau grafik akan muncul di sini</p>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 py-6 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ new Date().getFullYear() }} ABID WEB PROJECT'S. All rights reserved.
            </p>
        </footer>
    </div>
</template>