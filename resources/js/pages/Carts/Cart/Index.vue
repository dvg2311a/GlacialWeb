<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash, Eye } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps(
    {
        'carts': Array,
        'status_cart': Array,
        'type_cart': Array,
        'seller': Array,
    }
)

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
            router.delete(route('carts.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'El carrito ha sido eliminado.',
                'success'
            );
        }
    });
}
</script>

<template>

    <Head title="Carritos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden sm:rounded-lg">
                    <div class="p-1">
                        <h1 class="text-2xl font-bold mb-4">Carritos</h1>
                        <p class=" :dark:text-white">En este apartado puedes
                            gestionar los carritos. Agregar, editar ver y eliminar cada registro.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('carts.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Carrito
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
                                    Numero de placa
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo de carro
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in carts" :key="item.id" class="dark:text-dark">
                                <td class="px-6 py-4 whitespace-nowrap text-blue">
                                    {{ item.plate_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ item.description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ item.type_cart.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('carts.show', item.id)" title="Ver"
                                        class="text-green-600 hover:text-green-900 cursor-pointer">
                                        <Eye
                                            class="cursor-pointer dark:text-green-600 dark:hover:text-green-900" />
                                    </NavLink>
                                    <NavLink :href="route('carts.edit', item.id)" title="Editar"
                                        class="text-indigo-600 hover:text-indigo-900 cursor-pointer">
                                        <SquarePen
                                            class="cursor-pointer dark:text-indigo-600 dark:hover:text-indigo-900" />
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>
