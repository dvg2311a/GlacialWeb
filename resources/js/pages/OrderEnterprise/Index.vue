<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, Eye, Trash, SquarePen } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineProps({
    order: Array,
});


function formatDate(dateStr) {
    if (!dateStr) return '-'
    const date = new Date(dateStr)
    return date.toLocaleDateString()
}

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
            router.delete(route('orders_enterprises.destroy', id));
            Swal.fire(
                '¡Eliminado!',
                'El pedido ha sido eliminado.',
                'success'
            );
        }
    });
}

</script>

<template>

    <Head title="Pedidos Empresa" />
    <AuthenticatedLayout :fullWidth="true">
        <div class="py-0 lg:py-12 w-full">
            <div class="w-min-full mx-auto -sm:px-6 -lg-px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="lg:p-1 lg:ml-4">
                        <h1 class="text-2xl font-bold mb-4">Pedidos Empresa</h1>
                        <p class="dark:text-white">Gestión de pedidos de empresa. Ver detalles, eliminar o crear nuevos
                            pedidos.</p>
                        <p class="dark:text-white"><b>Total de pedidos realizados a la fecha:</b> {{ order.length }}</p>
                    </div>

                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('orders_enterprises.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Agregar Pedido
                        <Plus :size="20" />
                    </NavLink>
                </div>

                <div class=" bg-white mt-6 -ml-4 lg:ml-2 overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <table class="min-w-[0px] lg:w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Pedido</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Esperada</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total de Cajas Recibidas</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Impuestos</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gran Total</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="o in order" :key="o.id">
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ formatDate(o.order_date) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ formatDate(o.expected_date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{
                                    o.purchase_order_detail.reduce((total, detail) => total + detail.boxes_received, 0)
                                    }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ o.order_status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ o.subtotal_base }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ o.total_tax }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ o.grand_total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center  ">
                                    <NavLink :href="route('orders_enterprises.show', { orders_enterprise: o.id })"
                                        class="text-green-600 hover:text-green-900" title="Ver">
                                        <Eye />
                                    </NavLink>
                                    <NavLink :href="route('orders_enterprises.edit', { orders_enterprise: o.id })"
                                        class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                        <SquarePen />
                                    </NavLink>
                                    <button @click="confirmDelete(o.id)" class="text-red-600 hover:text-red-900 ml-2"
                                        title="Eliminar">
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
