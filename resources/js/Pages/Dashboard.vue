<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object
});

const page = usePage();

// Mengecek apakah user sudah memiliki token Google
const hasGoogleToken = !!props.auth.user.google_token;

// Form Upload Drive
const driveForm = useForm({
    file: null,
});

const submitDrive = () => {
    driveForm.post(route('google.drive.upload'), {
        preserveScroll: true,
        onSuccess: () => {
            driveForm.reset();
            alert(page.props.flash.success || 'Upload Berhasil!');
        },
    });
};

// Form Calendar Event
const calendarForm = useForm({
    summary: '',
    start_datetime: '',
    end_datetime: '',
});

const submitCalendar = () => {
    calendarForm.post(route('google.calendar.create'), {
        preserveScroll: true,
        onSuccess: () => {
            calendarForm.reset();
            alert(page.props.flash.success || 'Jadwal Berhasil Dibuat!');
        },
    });
};
</script>

<template>
    <Head title="Dashboard Integrasi" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <h2 class="text-2xl font-bold mb-4">Integrasi Google Workspace</h2>
                <div v-if="!hasGoogleToken">
                    <p class="mb-4 text-gray-600">Anda belum menghubungkan akun Google.</p>
                    <a :href="route('google.redirect')" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                        Hubungkan Google Account
                    </a>
                </div>
                <div v-else>
                    <span class="text-green-600 font-bold bg-green-100 px-4 py-2 rounded">
                        ✓ Akun Google Terhubung
                    </span>
                </div>
            </div>

            <div v-if="hasGoogleToken" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">Upload ke Google Drive</h3>
                    <form @submit.prevent="submitDrive">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Pilih File</label>
                            <input 
                                type="file" 
                                @input="driveForm.file = $event.target.files[0]"
                                class="w-full border p-2 rounded" 
                                required
                            >
                        </div>
                        <button type="submit" :disabled="driveForm.processing" class="w-full bg-indigo-600 text-white py-2 rounded font-bold hover:bg-indigo-700">
                            {{ driveForm.processing ? 'Mengunggah...' : 'Upload File' }}
                        </button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-bold border-b pb-2 mb-4">Buat Jadwal Calendar</h3>
                    <form @submit.prevent="submitCalendar">
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Nama Kegiatan</label>
                            <input type="text" v-model="calendarForm.summary" class="w-full border p-2 rounded" required placeholder="Contoh: Meeting Proyek A">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Waktu Mulai</label>
                            <input type="datetime-local" v-model="calendarForm.start_datetime" class="w-full border p-2 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Waktu Selesai</label>
                            <input type="datetime-local" v-model="calendarForm.end_datetime" class="w-full border p-2 rounded" required>
                        </div>
                        <button type="submit" :disabled="calendarForm.processing" class="w-full bg-red-600 text-white py-2 rounded font-bold hover:bg-red-700">
                            {{ calendarForm.processing ? 'Menyimpan...' : 'Simpan Jadwal' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>