<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Plus, Eye, SquarePen, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type DailyReportItem = {
    id: number;
    report_date: string;
    grand_total?: number;
    seller?: {
        name: string;
    };
};

defineProps<{ daily_report: DailyReportItem[] }>();

const Swal = (window as unknown as { Swal: { fire: (...args: unknown[]) => Promise<{ isConfirmed?: boolean }> } }).Swal;

function confirmDelete(id: number) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result: { isConfirmed?: boolean }) => {
        if (result.isConfirmed) {
            router.delete(route('seller_daily_reports.destroy', { seller_daily_report: id }), {
                onSuccess: () => {
                    Swal.fire(
                        '¡Eliminado!',
                        'El registro ha sido eliminado.',
                        'success'
                    );
                },
            });
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
                        <h1 class="text-2xl font-bold mb-4">Reportes Diarios</h1>
                        <p class=":dark:text-white">Listado de reportes diarios de vendedores.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-end">
                    <NavLink :href="route('seller_daily_reports.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 focus:text-white active:bg-blue-600 disabled:opacity-25 transition">
                        Nuevo Reporte
                        <Plus :size="18" class="ml-2" />
                    </NavLink>
                </div>

                <div class=" bg-white mt-6 -ml-4 overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <table class="min-w-[0px] lg:w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendedor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="report in daily_report" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ report.report_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ report.seller ? report.seller.name : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ report.grand_total ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <NavLink :href="route('seller_daily_reports.show', { seller_daily_report: report.id })" class="text-gray-600 hover:text-gray-900" title="Ver">
                                        <Eye />
                                    </NavLink>
                                    <NavLink :href="route('seller_daily_reports.edit', { seller_daily_report: report.id })" class="text-indigo-600 hover:text-indigo-900 ml-2" title="Editar">
                                        <SquarePen />
                                    </NavLink>
                                    
                                    <button @click="confirmDelete(report.id)" class="text-red-600 hover:text-red-900 ml-2" title="Eliminar">
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
