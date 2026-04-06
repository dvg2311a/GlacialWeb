<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    type_freezer: Array,
});
const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('type_freezers.store'));
};
</script>

<template>

    <Head title="Crear Tipo de Freezer" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-wrap">
                        <NavLink :href="route('type_freezers.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Crear Tipo de Freezer</h1>
                        <p class="text-gray-600 w-full mt-4">En este apartado puedes crear un nuevo tipo de freezer.</p>
                    </div>
                </div>

                <!-- Formulario para crear un nuevo tipo de freezer -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6 flex justify-center flex-col items-center">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" type="text" name="name" id="name" required
                            class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea v-model="form.description" name="description" id="description" rows="3"
                            class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Crear Tipo de Freezer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
