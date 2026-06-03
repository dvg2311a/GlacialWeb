<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';



defineProps({
    freezer: Array,

});

const Swal = window.Swal;


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
            router.delete(route('freezers.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'El freezer ha sido eliminado.',
                'success'
            );
        }
    });
}

</script>

<template>

    <Head title="Type Freezers" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden sm:rounded-lg">
                    <div class="p-1 border-gray-200">
                        <h1 class="text-2xl font-bold mb-4">Freezers</h1>
                        <p class=":dark:text-white">En este apartado puedes gestionar los freezers.
                            Agregar, editar ver y eliminar cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('freezers.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Freezer
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class="mt-6 overflow-auto w-[365px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <table class="min-w-[0px] lg:w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Número de Frezer
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo de Freezer
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="freezer in freezer" :key="freezer.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ freezer.number_freezer }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ freezer.status }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ freezer.type_freezer.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('freezers.edit', freezer.id)"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-600"
                                        title="Editar">
                                        <SquarePen class="edit" />

                                    </NavLink>
                                    <button @click="confirmDelete(freezer.id)"
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
