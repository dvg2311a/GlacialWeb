<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineProps({
    status_carts: Array,
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
            router.delete(route('status_carts.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'El status de carrito ha sido eliminado.',
                'success'
            );
        }
    });
}

</script>


<template>

    <Head title="Estado de Carritos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden sm:rounded-lg">
                    <div class="p-1">
                        <h1 class="text-2xl font-bold mb-4">Estado de Carritos</h1>
                        <p class=" :dark:text-white">En este apartado puedes
                            gestionar los status. Agregar, editar ver y eliminar cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('status_carts.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Estado de Carrito
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class="mt-6">
                    <table class="min-w-full divide-y divide-gray-200">
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
                            <tr v-for="item in status_carts" :key="item.id" class="dark:text-dark">
                                <td class="px-6 py-4 whitespace-nowrap text-blue">
                                    {{ item.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ item.description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('status_carts.edit', item.id)" title="Editar"
                                        class="text-indigo-600 hover:text-indigo-900 cursor-pointer">
                                        <SquarePen class="cursor-pointer dark:text-indigo-600 dark:hover:text-indigo-900"/>
                                    </NavLink>
                                    <button @click="confirmDelete(item.id)"
                                        class="text-red-600 hover:text-red-900 ml-2 " title="Eliminar">
                                        <Trash />

                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Edit modal -->
                <Modal :show="showEdit" @close="showEdit = false">
                    <StatusCartForm :statusCart="selectedStatusCart" mode="edit" @updated="onUpdated"
                        @close="showEdit = false" />
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
