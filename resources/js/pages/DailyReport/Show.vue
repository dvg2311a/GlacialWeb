<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type UserOption = {
    name: string;
    surname?: string | null;
};

type ReportSeller = {
    name: string;
};

type DailyReportItem = {
    id: number | string;
    product?: {
        name: string;
    } | null;
    quantity_out: number;
    quantity_return: number;
    quantity_sold: number;
    wholesale_price: number;
    total_sales: number;
};

type DailyReport = {
    report_date: Date;
    grand_total?: number | string | null;
    seller?: ReportSeller | null;
};

const props = defineProps < {
    daily_report: DailyReport;
    daily_report_detail: DailyReportItem[];
    creator_user: UserOption | null;
} > ();

const { daily_report, daily_report_detail, creator_user } = props;

function creatorUserLabel(user: UserOption | null) {
    if (!user) {
        return '—';
    }

    return `${user.name}${user.surname ? ` ${user.surname}` : ''}`;
}
</script>

<template>

    <Head title="Ver Reporte" />
    <AuthenticatedLayout>
        <div class="lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-1 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" ml-4 p-1 flex flex-wrap">
                        <NavLink :href="route('seller_daily_reports.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Detalle Reporte</h1>
                        <p class="dark:text-white w-full mt-4">Reporte del día {{ new Date(daily_report.report_date).toLocaleDateString('es-NI') }}</p>
                    </div>
                </div>

                <div class="mt-6 bg-white rounded pl-2 pt-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                        <div><strong>Vendedor:</strong> {{ daily_report.seller ? daily_report.seller.name && daily_report.seller.surname ? `${daily_report.seller.name} ${daily_report.seller.surname}` : daily_report.seller.name : '—'
                            }}</div>
                        <div><strong>Fecha de registro:</strong> {{ new Date(
                            daily_report.report_date).toLocaleDateString('es-NI') }}</div>
                        <div><strong>Total:</strong> {{ daily_report.grand_total ?? 0 }}</div>
                        <div class=" w-[360px]"><strong>Usuario que registró:</strong> {{ creatorUserLabel(creator_user) }}</div>
                    </div>

                    <div class="overflow-auto w-[360px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                        style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                        <table class="w-[360px] lg:w-full divide-y divide-gray-200">
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

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
