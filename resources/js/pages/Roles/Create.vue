<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ permissions: Array });

const form = useForm({
    name: '',
    permissions: [],
});
</script>
<template>

    <Head title="Create Role" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Rol
            </h2>
        </template>

        <div class="py-12 ps-6 pe-6">
            <div class=" sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="form.post(route('roles.store'))">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nombre del Rol
                                </label>
                                <input  type="text" name="name" id="name" v-model="form.name"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            </div>

                            <div class="mt-4 grid gap-4 grid-cols-6">

                                <span>Permisos</span>
                                <label v-for="perm in props.permissions" :key="perm.id" class="flex text-sm font-medium text-gray-700">

                                    <input type="checkbox" name="permissions[]"
                                    :value="perm.id" v-model="form.permissions" class="mr-2 cursor-pointer hover:bg-gray-200 rounded flex" />
                                    {{ perm.name }}

                                </label>
                                <p v-if="form.errors.permissions" class="text-sm text-red-600">
                                    {{ form.errors.permissions }}
                                </p>
                            </div>

                            <div class="flex items-center mt-4">
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Crear
                                </button>
                                <Link :href="route('roles.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Cancelar
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
