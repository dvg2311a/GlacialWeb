<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>

<template>
    <Head title="Panel de control" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 dark:text-white">
                        ¡Has ingresado a GlacialWeb! Gestiona tu agencia, controla tus productos y manten tus reportes al día.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
