<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Plus, Eye, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    reports_group: {
        type: Object,
        required: true
    }
});

// const formatDate = (date) => {
//     return new Date(date + 'T00:00:00').toLocaleDateString('es-NI', {

//         year: 'numeric',
//         month: 'long',
//         day: '2-digit'
//     });
// }

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
            router.delete(route('seller_daily_reports.destroy', id));
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

    <Head title="Reporte Diario" />
    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden sm:rounded-lg">
                    <div class="p-1 border-gray-200">
                        <h1 class="text-2xl font-bold mb-4">Ventas Diarias</h1>
                        <p class="-translate-x-1" style="z-index: -1000;">Este apartado contiene el listado de ventas diarias de los
                            vendedores.
                            Puedes agregar nuevas ventas o editar las existentes.
                            Para eliminar alguna venta, consulta con el adminsitrador</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('seller_daily_reports.create')"
                        class="-translate-x-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">

                        Nuevo Reporte
                        <Plus :size="18" class="ml-2" />
                    </NavLink>
                </div>

                <div v-for="(reports, date) in reports_group" :key="date" class=" bg-white rounded-lg mt-6 -ml-4 ">

                    <h3 class="text-lg font-semibold text-gray-500 m-4 pt-4">{{ date }}</h3>

                    <div class="overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                        style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">

                        <table class="min-w-[0px] lg:w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vendedor</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                                <tr v-for="report in reports" :key="report.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ report.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ report.seller.name }} {{
                                        report.seller.surname }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ report.grand_total }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <NavLink
                                            :href="route('seller_daily_reports.show', { seller_daily_report: report.id })"
                                            class="text-green-600 hover:text-green-900" title="Ver">
                                            <Eye />
                                        </NavLink>
                                        <NavLink
                                            :href="route('seller_daily_reports.edit', { seller_daily_report: report.id })"
                                            class="text-indigo-600 hover:text-indigo-900 ml-2" title="Editar">
                                            <SquarePen />
                                        </NavLink>
                                        <button @click="confirmDelete(report.id)"
                                            class="text-red-600 hover:text-red-900 ml-2" title="Eliminar">
                                            <Trash />
                                        </button>
                                    </td>
                                    <!-- {{ report.created_at }} -->
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
