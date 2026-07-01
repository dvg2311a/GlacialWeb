<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
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

const imageSources = [
    '/snowflake-1.svg'
];

const useImageShape = imageSources.length > 0;

const shape = useImageShape
    ? {
        type: 'image',
        options: {
            image: imageSources.map((src, i) => ({ src, name: `img${i + 1}`, width: 40, height: 40, replaceColor: false }))
        }
    }
    : { type: 'circle' };

const options = {
    background: { color: { value: 'transparent' } },
    fpsLimit: 60,
    detectRetina: true,
    ...(useImageShape ? { preload: imageSources.map((src, i) => ({ src, name: `img${i + 1}` })) } : {}),
    particles: {
        number: { value: 100, density: { enable: true, area: 1500 } },
        // color can be omitted for image shapes
        ...(useImageShape ? {} : { color: { value: '#ffffff' } }),
        shape,
        opacity: { value: 1 },
        // when using images use a fixed size so images are visible
        size: useImageShape ? { value: 20 } : { value: { min: 6, max: 16 } },
        move: { enable: true, speed: 2, outModes: { default: 'bounce' } },
    },
    interactivity: {
        events: {
            onHover: { enable: true, mode: 'repulse', parallax: { enable: true, force: 90, smooth: 30 } },
            onClick: { enable: true},
        },
        modes: { repulse: { distance: 120 } },
    },
};
</script>

<template>
    <div class="relative">
        <VueParticles id="tsparticles" :options="options" style="position:fixed; inset:0; z-index:1;" />

        <GuestLayout>

            <Head title="Inicio de sesión" />

            <form @submit.prevent="submit" class="relative z-50">
                <div>
                    <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-700 " />

                    <TextInput id="email" type="email" class="mt-1 block w-full " v-model="form.email" required
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-700" />

                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="current-password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Forgot your password?
                    </Link>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>
            </form>

        </GuestLayout>
    </div>
</template>
