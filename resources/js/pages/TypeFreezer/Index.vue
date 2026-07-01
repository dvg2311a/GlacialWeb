<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineProps({
    type_freezer: Array,
});

function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('type_freezers.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'El tipo de freezer ha sido eliminado.',
                'success'
            );
        }
    });
}

</script>


<template>

    <Head title="Type Freezers" />
    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden sm:rounded-lg">
                    <div class="p-1">
                        <h1 class="text-2xl font-bold mb-4">Tipos de Freezers</h1>
                        <p class=" :dark:text-white">En este apartado puedes
                            gestionar los tipos de freezers. Agregar, editar ver y eliminar cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 mr-4 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('type_freezers.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Tipo de Freezer
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class=" bg-white mt-6 -ml-4 overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <table class="min-w-[0px] lg:w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="type_freezer in type_freezer" :key="type_freezer.id" class="dark:text-dark">
                                <td class="px-6 py-4 whitespace-nowrap text-blue">
                                    {{ type_freezer.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ type_freezer.description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('type_freezers.edit', type_freezer.id)" title="Editar"
                                        class="text-indigo-600 hover:text-indigo-900 cursor-pointer">
                                        <SquarePen
                                            class="cursor-pointer dark:text-indigo-600 dark:hover:text-indigo-900" />
                                    </NavLink>
                                    <button @click="confirmDelete(type_freezer.id)"
                                        class="text-red-600 hover:text-red-900 ml-2 " title="Eliminar">
                                        <Trash />

                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
