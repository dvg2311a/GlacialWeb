<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';



defineProps({
    plates: Array,
    plate_dimension: Array,
    freezer: Array

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
            router.delete(route('plates.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'La dimensión de la placa ha sido eliminada.',
                'success'
            );
        }
    });
}

</script>

<template>

    <Head title="Placas" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-4">Placas</h1>
                        <p class="text-gray-600">En este apartado puedes gestionar las placas.
                            Agregar, editar ver, eliminar y asignar al freezer y su dimensión correspondiente a
                            cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('plates.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Placa
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class="mt-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Codigo de placa
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Forma
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Freezer
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="plate in plates" :key="plate.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate.code_plate }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate.plate_dimension.shape }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate.freezer.number_freezer }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('plates.edit', plate.id)"
                                        class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                        <SquarePen />

                                    </NavLink>
                                    <button @click="confirmDelete(plate.id)"
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
