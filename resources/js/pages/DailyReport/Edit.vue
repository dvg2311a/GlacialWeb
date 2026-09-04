<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import InputError from '@/Components/InputError.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { daily_report, daily_report_details, products, sellers } = defineProps({
    daily_report: Object,
    daily_report_details: Array,
    products: Array,
    sellers: Array,
});

const form = useForm({
    report_date: '',
    seller_id: '',
    morning_checkup: '',
    evening_checkup: '',
    seller_daily_reports: [],
});

function createProductRow() {
    return {
        id: '',
        name: '',
        quantity_out: 0,
        quantity_return: 0,
        quantity_sold: 0,
        wholesale_price: 0,
        line_total: 0,
    };
}

function addProduct() {
    const row = createProductRow();
    syncReportRow(row);
    form.seller_daily_reports.push(row);
}

function removeProduct(index) {
    form.seller_daily_reports.splice(index, 1);
    if (!form.seller_daily_reports.length) {
        addProduct();
    }
}

function syncReportRow(row) {
    const quantityOut = Number(row.quantity_out) || 0;
    const quantityReturn = Number(row.quantity_return) || 0;
    const wholesale = Number(row.wholesale_price) || 0;

    row.quantity_sold = Math.max(quantityOut - quantityReturn, 0);
    row.line_total = Number((row.quantity_sold * wholesale).toFixed(2));
}

// populate form with existing data
if (daily_report) {
    form.report_date = daily_report.report_date || '';
    form.seller_id = daily_report.seller_id || '';
    form.morning_checkup = daily_report.morning_checkup || '';
    form.evening_checkup = daily_report.evening_checkup || '';
}

if (daily_report_details && daily_report_details.length) {
    form.seller_daily_reports = daily_report_details.map(d => {
        const row = {
            id: d.product_id,
            name: d.product ? d.product.name : '',
            wholesale_price: d.wholesale_price ?? 0,
            quantity_out: d.quantity_out ?? 0,
            quantity_return: d.quantity_return ?? 0,
            quantity_sold: 0,
            line_total: 0,
        };
        syncReportRow(row);
        return row;
    });
} else if (products && products.length) {
    // initialize with a single empty product row
    form.seller_daily_reports = [createProductRow()];
}


function submit() {

    const swal = window.Swal;

    form.put(route('seller_daily_reports.update', daily_report.id), {

        onSuccess: () => {
            if (swal && swal.fire) {
                swal.fire({ title: '¡Éxito!', text: 'Registro actualizado.', icon: 'success', timer: 2000 });
            } else {
                alert('Registro actualizado');
            }
        },
        onError: (errors) => {
            console.error('Update errors', errors);
        },

    });
}
</script>

<template>

    <Head title="Editar Reporte" />
    <AuthenticatedLayout>
        <div class="lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" ml-4 p-1 flex flex-wrap">
                        <NavLink :href="route('seller_daily_reports.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Reporte</h1>
                        <p class="dark:text-white w-full mt-4">Modificar registro del reporte diario.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <div class="w-[260px]">
                                <label class="block text-sm font-medium text-gray-700">Vendedor</label>
                                <select v-model="form.seller_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm W-[320px]">
                                    <option value="">Seleccionar vendedor</option>
                                    <option v-for="s in (sellers || [])" :key="s.id" :value="s.id">{{ s.name }} {{
                                        s.surname }}</option>
                                </select>
                                <div v-if="form.errors.seller_id" class="text-red-600 text-sm mt-1">{{
                                    form.errors.seller_id }}</div>
                            </div>

                            <div class="w-[180px]">
                                <label class="block text-sm font-medium text-gray-700">Fecha de reporte</label>
                                <input v-model="form.report_date" type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                <InputError :message="form.errors.report_date" />
                            </div>

                            <div class="w-[180px]">
                                <label class="block text-sm font-medium text-gray-700">Revisión Matutina</label>
                                <input v-model="form.morning_checkup" type="time" step="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                <InputError :message="form.errors.morning_checkup" />
                            </div>

                            <div class="w-[180px]">
                                <label class="block text-sm font-medium text-gray-700">Revisión Vespertina</label>
                                <input v-model="form.evening_checkup" type="time" step="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                <InputError :message="form.errors.evening_checkup" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-4 w-[390px] lg:w-full">
                                <h2 class="font-semibold mb-2">Productos</h2>
                                <button type="button" @click="addProduct"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-500">
                                    Añadir producto
                                </button>
                            </div>
                            <div class="overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                                style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Producto</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Llevó</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Devolvió</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Vendió</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Precio</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Total</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(p, idx) in form.seller_daily_reports" :key="idx">
                                            <td class="px-4 py-2">
                                                <select v-model="form.seller_daily_reports[idx].id"
                                                    @change="(e) => { const prod = (products || []).find(x => x.id == form.seller_daily_reports[idx].id); if (prod) { form.seller_daily_reports[idx].name = prod.name; form.seller_daily_reports[idx].wholesale_price = prod.wholesale_price ?? 0 } syncReportRow(form.seller_daily_reports[idx]) }"
                                                    class=" rounded border-gray-300 shadow-sm">
                                                    <option value="">Seleccionar producto</option>
                                                    <option v-for="prod in (products || [])" :key="prod.id"
                                                        :value="prod.id">{{ prod.name }}</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-2"><input
                                                    v-model.number="form.seller_daily_reports[idx].quantity_out"
                                                    @input="syncReportRow(form.seller_daily_reports[idx])" type="number"
                                                    min="0" class="w-24 rounded border" /></td>
                                            <td class="px-4 py-2"><input
                                                    v-model.number="form.seller_daily_reports[idx].quantity_return"
                                                    @input="syncReportRow(form.seller_daily_reports[idx])" type="number"
                                                    min="0" class="w-24 rounded border" /></td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-700">{{ p.quantity_sold
                                                ?? 0 }}</td>
                                            <td class="px-4 py-2"><input
                                                    v-model.number="form.seller_daily_reports[idx].wholesale_price"
                                                    @input="syncReportRow(form.seller_daily_reports[idx])" type="number"
                                                    step="0.01" class="w-28 rounded border" /></td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-700">{{ (p.line_total ??
                                                0).toFixed(2) }}</td>
                                            <td class="px-4 py-2">
                                                <button type="button" @click="removeProduct(idx)"
                                                    class="inline-flex items-center gap-2 px-2 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-500">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 mr-7 flex justify-end">
                                <div class="rounded-md bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700">
                                    Total general: {{form.seller_daily_reports.reduce((s, r) => s +
                                        (Number(r.line_total) || 0), 0).toFixed(2)}}
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 disabled:opacity-50">Actualizar
                                Reporte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
