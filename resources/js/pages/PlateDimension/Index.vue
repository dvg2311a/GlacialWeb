<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';



defineProps({
    plate_dimensions: Array,

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
            router.delete(route('plate_dimensions.destroy', id));
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

    <Head title="Dimensiones de Placas" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 ml-4">
                        <h1 class="text-2xl font-bold mb-4">Dimensiones de Placas</h1>
                        <p class="dark:text-white">En este apartado puedes gestionar las dimensiones de las placas.
                            Agregar, editar ver y eliminar cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('plate_dimensions.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Dimensión
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class="mt-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alto
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ancho
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unidad de Medida
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Forma
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="plate_dimension in plate_dimensions" :key="plate_dimension.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate_dimension.height }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate_dimension.width }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate_dimension.unit_measure }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ plate_dimension.shape }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('plate_dimensions.edit', plate_dimension.id)"
                                        class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                        <SquarePen />

                                    </NavLink>
                                    <button @click="confirmDelete(plate_dimension.id)"
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
