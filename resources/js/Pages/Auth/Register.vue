<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    nama_lengkap: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo Branding A+W+P -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-widest">
                A<span class="text-indigo-600">+</span>W<span class="text-indigo-600">+</span>P
            </h1>
            <p class="text-sm text-gray-500 mt-2 font-medium tracking-widest">ABID WEB PROJECT'S</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="nama_lengkap" value="Nama Lengkap" />
                    <TextInput
                        id="nama_lengkap"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.nama_lengkap"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.nama_lengkap" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Konfirmasi Password" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <Link
                        :href="route('login')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Sudah punya akun?
                    </Link>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Daftar & Masuk
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>