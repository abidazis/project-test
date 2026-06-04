<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ nama_lengkap: '', email: '', password: '', password_confirmation: '' });

const submit = () => { form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') }); };
</script>

<template>
    <Head title="Register" />
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-slate-100 to-indigo-50 p-6">
        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl shadow-2xl rounded-[2rem] p-10 border border-white">
            
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">
                    A<span class="text-indigo-600">W</span>P
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-semibold tracking-widest uppercase">Buat Akun Baru</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" v-model="form.nama_lengkap" required autofocus
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" />
                    <p v-if="form.errors.nama_lengkap" class="text-red-500 text-xs mt-2">{{ form.errors.nama_lengkap }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input type="email" v-model="form.email" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" />
                    <p v-if="form.errors.email" class="text-red-500 text-xs mt-2">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" v-model="form.password" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" />
                    <p v-if="form.errors.password" class="text-red-500 text-xs mt-2">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" v-model="form.password_confirmation" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" />
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full mt-4 flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-[0.98]">
                    {{ form.processing ? 'Memproses...' : 'Daftar & Masuk' }}
                </button>

                <p class="text-center text-sm text-slate-600 mt-6">
                    Sudah punya akun? <Link :href="route('login')" class="font-bold text-indigo-600 hover:text-indigo-500">Sign in</Link>
                </p>
            </form>
        </div>
    </div>
</template>