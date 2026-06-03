<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo Branding A+W+P -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-widest">
                A<span class="text-indigo-600">+</span>W<span class="text-indigo-600">+</span>P
            </h1>
            <p class="text-sm text-gray-500 mt-2 font-medium tracking-widest">ABID WEB PROJECT'S</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
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
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Lupa password?
                    </Link>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>
                
                <div class="mt-6 text-center text-sm text-gray-600">
                    Belum punya akun? <Link :href="route('register')" class="text-indigo-600 font-semibold hover:underline">Daftar sekarang</Link>
                </div>
            </form>
        </div>
    </div>
</template>