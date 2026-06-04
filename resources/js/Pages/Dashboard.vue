<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object
});

const hasGoogleToken = !!props.auth.user.google_token;

// State untuk Pop-Up Modal
const showModal = ref(false);
const modalMessage = ref('');
const modalUrl = ref('');

// Form Drive
const driveForm = useForm({
    file: null,
});

const submitDrive = () => {
    driveForm.post(route('google.drive.upload'), {
        preserveScroll: true,
        onSuccess: (page) => {
            driveForm.reset();
            // Tampilkan Modal
            modalMessage.value = page.props.flash.success;
            modalUrl.value = page.props.flash.action_url;
            showModal.value = true;
        },
    });
};

// Form Calendar
const calendarForm = useForm({
    summary: '',
    start_datetime: '',
    end_datetime: '',
});

const submitCalendar = () => {
    calendarForm.post(route('google.calendar.create'), {
        preserveScroll: true,
        onSuccess: (page) => {
            calendarForm.reset();
            // Tampilkan Modal
            modalMessage.value = page.props.flash.success;
            modalUrl.value = page.props.flash.action_url;
            showModal.value = true;
        },
    });
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <Head title="Dashboard Integrasi" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-50">
            <div class="relative w-full max-w-md p-4">
                <div class="relative bg-white rounded-2xl shadow-xl p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 mb-2">Berhasil!</h3>
                    <p class="text-gray-600 mb-6 font-medium">{{ modalMessage }}</p>
                    <div class="flex flex-col space-y-3">
                        <a v-if="modalUrl" :href="modalUrl" target="_blank" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-indigo-700 transition">
                            Buka di Browser
                        </a>
                        <button @click="closeModal" class="w-full bg-gray-100 text-gray-700 font-bold py-3 px-4 rounded-xl hover:bg-gray-200 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <h2 class="text-2xl font-black mb-4">Integrasi Google Workspace</h2>
                <div v-if="!hasGoogleToken">
                    <p class="mb-4 text-gray-600 font-medium">Anda belum menghubungkan akun Google.</p>
                    <a :href="route('google.redirect')" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
                        Hubungkan Google Account
                    </a>
                </div>
                <div v-else>
                    <span class="inline-flex items-center text-green-700 font-bold bg-green-50 border border-green-200 px-6 py-3 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Akun Google Terhubung
                    </span>
                </div>
            </div>

            <div v-if="hasGoogleToken" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold border-b border-gray-100 pb-4 mb-6">Upload ke Google Drive</h3>
                    <form @submit.prevent="submitDrive">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih File</label>
                            <input type="file" @input="driveForm.file = $event.target.files[0]" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <button type="submit" :disabled="driveForm.processing" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition disabled:opacity-50">
                            {{ driveForm.processing ? 'Mengunggah...' : 'Upload File' }}
                        </button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold border-b border-gray-100 pb-4 mb-6">Buat Jadwal Calendar</h3>
                    <form @submit.prevent="submitCalendar">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kegiatan</label>
                            <input type="text" v-model="calendarForm.summary" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500" required placeholder="Contoh: Meeting Proyek A">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Mulai</label>
                            <input type="datetime-local" v-model="calendarForm.start_datetime" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Selesai</label>
                            <input type="datetime-local" v-model="calendarForm.end_datetime" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <button type="submit" :disabled="calendarForm.processing" class="w-full bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition disabled:opacity-50">
                            {{ calendarForm.processing ? 'Menyimpan...' : 'Simpan Jadwal' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>