<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Trash } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type ProductOption = {
    id: number | string;
    name: string;
    wholesale_price?: number;
};

type SellerOption = {
    id: number | string;
    name: string;
    surname?: string;
};

type ProductRow = {
    id: number | string | '';
    quantity_out: number;
    quantity_return: number;
    quantity_sold: number;
    wholesale_price: number;
    line_total: number;
};

type SellerReportRow = {
    seller_id: number | string | '';
    morning_checkup: string;
    evening_checkup: string;
    products: ProductRow[];
};

type DailyReportForm = {
    report_date: string;
    seller_reports: SellerReportRow[];
};

const { products, sellers } = defineProps<{
    products: ProductOption[];
    sellers: SellerOption[];
}>();

const createProductRow = (): ProductRow => ({
    id: '',
    quantity_out: 0,
    quantity_return: 0,
    quantity_sold: 0,
    wholesale_price: 0,
    line_total: 0,
});

const createSellerReportRow = (): SellerReportRow => ({
    seller_id: '',
    morning_checkup: '',
    evening_checkup: '',
    products: [createProductRow()],
});

const form = useForm<DailyReportForm>({
    report_date: '',
    seller_reports: [],
});

const errorFor = (path: string) => form.errors[path as keyof typeof form.errors];

function syncProductRow(product: ProductRow) {
    const quantityOut = Number(product.quantity_out) || 0;
    const quantityReturn = Number(product.quantity_return) || 0;
    const wholesalePrice = Number(product.wholesale_price) || 0;

    product.quantity_sold = Math.max(quantityOut - quantityReturn, 0);
    product.line_total = product.quantity_sold * wholesalePrice;
}

function addSellerReport() {
    const sellerReport = createSellerReportRow();
    sellerReport.products.forEach(syncProductRow);
    form.seller_reports.push(sellerReport);
}

function removeSellerReport(index: number) {
    form.seller_reports.splice(index, 1);

    if (!form.seller_reports.length) {
        addSellerReport();
    }
}

function addProduct(sellerIndex: number) {
    const product = createProductRow();
    syncProductRow(product);
    form.seller_reports[sellerIndex].products.push(product);
}

function removeProduct(sellerIndex: number, productIndex: number) {
    const productsList = form.seller_reports[sellerIndex].products;
    productsList.splice(productIndex, 1);

    if (!productsList.length) {
        productsList.push(createProductRow());
    }
}

function sellerTotal(sellerReport: SellerReportRow): number {
    return sellerReport.products.reduce((total: number, product: ProductRow) => {
        return total + (Number(product.line_total) || 0);
    }, 0);
}

function sellerLabel(sellerId: SellerReportRow['seller_id']): string {
    const seller = (sellers || []).find((item: SellerOption) => item.id === sellerId);

    if (!seller) {
        return 'Selecciona un vendedor';
    }

    return `${seller.name}${seller.surname ? ` ${seller.surname}` : ''}`;
}

function submit() {
    const Swal = (window as unknown as { Swal: { fire: (...args: any[]) => void } }).Swal;

    form.post(route('seller_daily_reports.store'), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Registro creado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 2500,
            });
        },
    });
}

addSellerReport();
</script>

<template>

    <Head title="Crear Reporte Diario" />
    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex flex-wrap items-start gap-3">
                        <NavLink :href="route('seller_daily_reports.index')"
                            class="mt-1 rounded-md text-slate-500 transition hover:text-cyan-700">
                            <ArrowLeft :size="32" />
                        </NavLink>
                        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Crear reporte diario</h1>
                        <p class="mt-2 w-full text-sm text-slate-500 dark:text-slate-300">Registrar ventas diarias por vendedores y sus productos.
                        </p>
                    </div>
                </div>

                <div class="mt-6 w-[360px] lg:w-full lg:max-w-7xl mx-auto sm:px-6 lg:px-8 scrollbar-thin scrollbar-thumb"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-1 w-[480px] lg:w-full ">
                            <div class="w-[150px] lg:w-[250px]">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Fecha de reporte</label>
                                <input v-model="form.report_date" type="date"
                                    class="mt-2 block w-36 rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600 lg:w-full" />
                                <InputError :message="form.errors.report_date" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                            <h2 class="font-semibold text-slate-800 dark:text-white">Vendedores</h2>
                            <button type="button" @click="addSellerReport"
                                class="inline-flex items-center gap-2 rounded-md border border-blue-700 px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-blue-900/20">
                                <Plus :size="16" /> Añadir vendedor
                            </button>
                        </div>

                        <details v-for="(sellerReport, sellerIndex) in form.seller_reports" :key="sellerIndex" open
                            class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                            <summary
                                class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-md outline-none">
                                <div class="min-w-0 flex-1 ">
                                    <div class="text-lg font-semibold text-slate-800 dark:text-white">
                                        {{ sellerLabel(sellerReport.seller_id) }}
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                                        {{ sellerReport.products.length }} producto(s) registrados · Total: {{
                                            sellerTotal(sellerReport).toFixed(2) }}
                                        <!-- Producto que lleva - Producto que devuelve | Producto que vende * Precio de venta  -->
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <!-- <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        Desplegable
                                    </span> -->
                                    <button type="button" @click.stop="removeSellerReport(sellerIndex)"
                                        class="inline-flex items-center justify-center gap-2 rounded-md border border-rose-700 px-3 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-700/20 dark:text-rose-300">
                                        <Trash :size="16" />
                                    </button>
                                </div>
                            </summary>

                            <div class="mt-4 space-y-4 border-t border-slate-200 pt-4 dark:border-slate-700 lg:w-full ">
                                <div
                                    class="min-w-[240px] flex gap-4 lg:w-[100%] flex-row lg:justify-around lg:gap-4 flex-wrap ">
                                    <div class="">

                                        <label

                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-200 lg:w-full">Vendedor</label>
                                        <select v-model="sellerReport.seller_id"
                                            class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600 lg:w-72">
                                            <option value="">Seleccionar vendedor</option>
                                            <option v-for="seller in (sellers || [])" :key="seller.id"
                                                :value="seller.id">{{
                                                    seller.name }} {{ seller.surname }}</option>
                                        </select>
                                        <InputError :message="errorFor(`seller_reports.${sellerIndex}.seller_id`)" />
                                    </div>
                                    <!-- </div> -->

                                    <!-- <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 max-w-2xl"> -->
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Asistencia matutina</label>
                                        <input v-model="sellerReport.morning_checkup" type="time" step="1"
                                            class="mt-2 block rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600 lg:w-full" />
                                        <InputError
                                            :message="errorFor(`seller_reports.${sellerIndex}.morning_checkup`)" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Asistencia
                                            Vespertina</label>
                                        <input v-model="sellerReport.evening_checkup" type="time" step="1"
                                            class="mt-2 block rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600 lg:w-full" />
                                        <InputError
                                            :message="errorFor(`seller_reports.${sellerIndex}.evening_checkup`)" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <h3 class="font-medium text-slate-800 dark:text-white">Productos del vendedor</h3>
                                    <button type="button" @click="addProduct(sellerIndex)"
                                        class="inline-flex items-center justify-center gap-2 rounded-md border border-green-700 px-3 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-700/20 dark:text-green-300">
                                        <Plus :size="16" /> Añadir producto
                                    </button>
                                </div>

                                <div class="overflow-auto rounded-lg border border-slate-200 dark:border-slate-700">
                                    <table class="w-full divide-y divide-slate-200 dark:divide-slate-700">
                                        <thead class="bg-slate-50 dark:bg-slate-800/70">
                                            <tr>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Producto</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Llevó</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Devolvió</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Vendió</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Precio</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Total</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product, productIndex) in sellerReport.products"
                                                :key="productIndex">
                                                <td class="px-4 py-2">
                                                    <select v-model="product.id" @change="syncProductRow(product)"
                                                        class="rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600">
                                                        <option value="">Seleccionar producto</option>
                                                        <option v-for="prod in (products || [])" :key="prod.id"
                                                            :value="prod.id">{{ prod.name }}</option>
                                                    </select>
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.id`)" />
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="product.quantity_out"
                                                        @input="syncProductRow(product)" type="number" min="0"
                                                        class="w-24 rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600" />
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.quantity_out`)" />
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="product.quantity_return"
                                                        @input="syncProductRow(product)" type="number" min="0"
                                                        class="w-24 rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600" />
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.quantity_return`)" />
                                                </td>
                                                <td class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                                    {{ product.quantity_sold }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="product.wholesale_price"
                                                        @input="syncProductRow(product)" type="number" step="0.01"
                                                        list="wholesale_price_list" min="0" class="w-28 rounded-md border-slate-300 shadow-sm focus:border-cyan-600 focus:ring-cyan-600" id="wholesale_pric" />
                                                        <datalist id="wholesale_price_list">
                                                            <option value="10.00" />
                                                            <option value="13.00" />
                                                            <option value="15.00" />
                                                            <option value="16.00" />
                                                            <option value="21.00" />
                                                            <option value="40.00" />
                                                        </datalist>
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.wholesale_price`)" />
                                                </td>
                                                <td class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                                    {{ product.line_total.toFixed(2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <button type="button"
                                                        @click="removeProduct(sellerIndex, productIndex)"
                                                        class="rounded-md p-2 text-rose-600 transition hover:bg-rose-50 hover:text-rose-700">
                                                        <Trash :size="14" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex justify-end">
                                    <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm dark:bg-slate-800">
                                        <span class="text-slate-500 dark:text-slate-300">Total del vendedor:</span>
                                        <strong class="ml-2 text-lg text-gray-700 dark:text-gray-300">
                                            {{ sellerTotal(sellerReport).toFixed(2) }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </details>

                        <div class="flex items-center justify-end gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                            <button type="submit"
                                class="inline-flex items-center rounded-md bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">Crear
                                Venta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
