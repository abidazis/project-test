<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({ auth: Object });
const hasGoogleToken = !!props.auth.user.google_token;

// State Pop-Up
const showModal = ref(false);
const modalMessage = ref('');
const modalUrl = ref('');

// Forms
const driveForm = useForm({ file: null });
const calendarForm = useForm({ summary: '', start_datetime: '', end_datetime: '' });

const handleSuccess = (page) => {
    modalMessage.value = page.props.flash.success;
    modalUrl.value = page.props.flash.action_url;
    showModal.value = true;
};

const submitDrive = () => driveForm.post(route('google.drive.upload'), { preserveScroll: true, onSuccess: (page) => { driveForm.reset(); handleSuccess(page); } });
const submitCalendar = () => calendarForm.post(route('google.calendar.create'), { preserveScroll: true, onSuccess: (page) => { calendarForm.reset(); handleSuccess(page); } });
</script>

<template>
    <Head title="Workspace Dashboard" />

    <div class="min-h-screen bg-slate-50 relative font-sans text-slate-900">
        
        <nav class="bg-white/70 backdrop-blur-lg border-b border-slate-200 sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-20 items-center">
                <div class="text-2xl font-black tracking-tight">
                    A<span class="text-indigo-600">W</span>P
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-sm font-semibold text-slate-600 hidden md:block">
                        Hi, {{ $page.props.auth.user.nama_lengkap }}
                    </span>
                    <Link :href="route('logout')" method="post" as="button" class="text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-5 py-2.5 rounded-xl transition-all">
                        Logout
                    </Link>
                </div>
            </div>
        </nav>

        <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
                <div class="bg-white rounded-[2rem] shadow-2xl p-10 max-w-sm w-full text-center border border-white">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Berhasil!</h3>
                    <p class="text-slate-500 mb-8 font-medium">{{ modalMessage }}</p>
                    <div class="space-y-3">
                        <a v-if="modalUrl" :href="modalUrl" target="_blank" class="block w-full bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                            Buka di Browser
                        </a>
                        <button @click="showModal = false" class="block w-full bg-slate-100 text-slate-700 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-200 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <main class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 md:p-12 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 mb-2">Google Workspace</h2>
                    <p class="text-slate-500 font-medium">Kelola sinkronisasi Drive dan Calendar dalam satu pintu.</p>
                </div>
                <div>
                    <a v-if="!hasGoogleToken" :href="route('google.redirect')" class="inline-flex items-center bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Hubungkan Google
                    </a>
                    <div v-else class="inline-flex items-center bg-emerald-50 text-emerald-700 px-6 py-3 rounded-2xl font-bold border border-emerald-100">
                        <span class="relative flex h-3 w-3 mr-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span></span>
                        Tersinkronisasi
                    </div>
                </div>
            </div>

            <div v-if="hasGoogleToken" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200 p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-4 bg-blue-50 rounded-2xl text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold">Cloud Storage</h3>
                    </div>
                    <form @submit.prevent="submitDrive" class="space-y-6">
                        <div>
                            <input type="file" @input="driveForm.file = $event.target.files[0]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer border border-slate-200 rounded-xl" required>
                        </div>
                        <button type="submit" :disabled="driveForm.processing" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all disabled:opacity-50">
                            {{ driveForm.processing ? 'Menyinkronkan...' : 'Upload File' }}
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-200 p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold">Smart Calendar</h3>
                    </div>
                    <form @submit.prevent="submitCalendar" class="space-y-5">
                        <input type="text" v-model="calendarForm.summary" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" required placeholder="Nama meeting atau agenda">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Mulai</label>
                                <input type="datetime-local" v-model="calendarForm.start_datetime" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Selesai</label>
                                <input type="datetime-local" v-model="calendarForm.end_datetime" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition-all" required>
                            </div>
                        </div>

                        <button type="submit" :disabled="calendarForm.processing" class="w-full mt-2 bg-indigo-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-[0.98] transition-all disabled:opacity-50">
                            {{ calendarForm.processing ? 'Menjadwalkan...' : 'Simpan Jadwal' }}
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>