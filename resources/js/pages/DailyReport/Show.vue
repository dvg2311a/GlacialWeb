<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { daily_report, daily_report_detail, product } = defineProps({
    daily_report: Object,
    daily_report_detail: Array,
    product: Array
});
</script>

<template>
    <Head title="Ver Reporte" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" ml-4 p-1 flex flex-wrap">
                        <NavLink :href="route('seller_daily_reports.index')" class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Detalle Reporte</h1>
                        <p class="dark:text-white w-full mt-4">Reporte del día {{ daily_report.report_date }}</p>
                    </div>
                </div>

                <div class="mt-6 bg-white p-4 rounded">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                        <div><strong>Vendedor:</strong> {{ daily_report.seller ? daily_report.seller.name : '—'
                         }}</div>
                        <div><strong>Fecha:</strong> {{ daily_report.report_date }}</div>
                        <div><strong>Total:</strong> {{ daily_report.grand_total ?? 0 }}</div>
                    </div>

                    <div class="overflow-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Producto</th>
                                    <th class="px-4 py-2 text-left">Llevó</th>
                                    <th class="px-4 py-2 text-left">Devolvió</th>
                                    <th class="px-4 py-2 text-left">Vendió</th>
                                    <th class="px-4 py-2 text-left">Precio</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="d in daily_report_detail" :key="d.id">
                                    <td class="px-4 py-2">{{ d.product ? d.product.name : '—' }}</td>
                                    <td class="px-4 py-2">{{ d.quantity_out }}</td>
                                    <td class="px-4 py-2">{{ d.quantity_return }}</td>
                                    <td class="px-4 py-2">{{ d.quantity_sold }}</td>
                                    <td class="px-4 py-2">{{ d.wholesale_price }}</td>
                                    <td class="px-4 py-2">{{ d.total_sales }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <p>Usuario que atendió: {{ daily_report.user_id ? daily_report.user.name : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
