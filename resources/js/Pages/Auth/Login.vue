<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ canResetPassword: Boolean, status: String });

const form = useForm({ email: '', password: '', remember: false });

const submit = () => { form.post(route('login'), { onFinish: () => form.reset('password') }); };
</script>

<template>
    <Head title="Log in" />
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-slate-100 to-indigo-50 p-6">
        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl shadow-2xl rounded-[2rem] p-10 border border-white">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">
                    A<span class="text-indigo-600">W</span>P
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-semibold tracking-widest uppercase">Abid Web Project's</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 text-center bg-green-50 p-3 rounded-xl">{{ status }}</div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input type="email" v-model="form.email" required autofocus
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="nama@email.com" />
                    <p v-if="form.errors.email" class="text-red-500 text-xs mt-2 font-medium">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" v-model="form.password" required
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="••••••••" />
                    <p v-if="form.errors.password" class="text-red-500 text-xs mt-2 font-medium">{{ form.errors.password }}</p>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Lupa password?
                    </Link>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-[0.98]">
                    {{ form.processing ? 'Memproses...' : 'Sign In' }}
                </button>

                <p class="text-center text-sm text-slate-600 mt-6">
                    Belum punya akun? <Link :href="route('register')" class="font-bold text-indigo-600 hover:text-indigo-500">Daftar sekarang</Link>
                </p>
            </form>
        </div>
    </div>
</template>