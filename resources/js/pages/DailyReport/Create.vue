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
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="ml-4 p-1 flex flex-wrap">
                        <NavLink :href="route('seller_daily_reports.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1 dark:text-white">Crear Reporte</h1>
                        <p class="dark:text-white w-full mt-4">Registrar ventas diarias por vendedores y sus productos.
                        </p>
                    </div>
                </div>

                <div class="mt-6 w-[360px] lg:w-full lg:max-w-7xl mx-auto sm:px-6 lg:px-8 scrollbar-thin scrollbar-thumb"
                    style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-1 w-[480px] lg:w-full ">
                            <div class="w-[150px] lg:w-[250px]">
                                <label class="block text-sm font-medium text-gray-700">Fecha de reporte</label>
                                <input v-model="form.report_date" type="date"
                                    class="mt-1 block lg:w-full w-36 rounded-md border-gray-300 shadow-sm" />
                                <InputError :message="form.errors.report_date" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <h2 class="font-semibold">Vendedores</h2>
                            <button type="button" @click="addSellerReport"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-500">
                                <Plus :size="16" /> Añadir vendedor
                            </button>
                        </div>

                        <details v-for="(sellerReport, sellerIndex) in form.seller_reports" :key="sellerIndex" open
                            class="group rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                            <summary
                                class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-md outline-none">
                                <div class="min-w-0 flex-1 ">
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ sellerLabel(sellerReport.seller_id) }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
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
                                        class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-white shadow-sm hover:bg-red-500">
                                        <Trash :size="16" />
                                    </button>
                                </div>
                            </summary>

                            <div class="mt-4 space-y-4 border-t border-gray-100 pt-4 lg:w-full ">
                                <div
                                    class="min-w-[240px] flex gap-4 lg:w-[100%] flex-row lg:justify-around lg:gap-4 flex-wrap ">
                                    <div class="">

                                        <label

                                            class="block text-sm font-medium text-gray-700 lg:w-full">Vendedor</label>
                                        <select v-model="sellerReport.seller_id"
                                            class="mt-1 block w-full lg:w-72 rounded-md border-gray-300 shadow-sm">
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
                                        <label class="block text-sm font-medium text-gray-700">Asistencia matutina</label>
                                        <input v-model="sellerReport.morning_checkup" type="time" step="1"
                                            class="mt-1 block lg:w-full rounded-md border-gray-300 shadow-sm" />
                                        <InputError
                                            :message="errorFor(`seller_reports.${sellerIndex}.morning_checkup`)" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Asistencia
                                            Vespertina</label>
                                        <input v-model="sellerReport.evening_checkup" type="time" step="1"
                                            class="mt-1 block lg:w-full rounded-md border-gray-300 shadow-sm" />
                                        <InputError
                                            :message="errorFor(`seller_reports.${sellerIndex}.evening_checkup`)" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <h3 class="font-medium">Productos del vendedor</h3>
                                    <button type="button" @click="addProduct(sellerIndex)"
                                        class="inline-flex items-center gap-2 w-52 px-3 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-500  ">
                                        <Plus :size="16" /> Añadir producto
                                    </button>
                                </div>

                                <div class="overflow-auto rounded-md border border-gray-200">
                                    <table class="w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Producto</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Llevó</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Devolvió</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Vendió</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Precio</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Total</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product, productIndex) in sellerReport.products"
                                                :key="productIndex">
                                                <td class="px-4 py-2">
                                                    <select v-model="product.id" @change="syncProductRow(product)"
                                                        class=" rounded border-gray-300 shadow-sm">
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
                                                        class="w-24 rounded border-gray-300 shadow-sm" />
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.quantity_out`)" />
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="product.quantity_return"
                                                        @input="syncProductRow(product)" type="number" min="0"
                                                        class="w-24 rounded border-gray-300 shadow-sm" />
                                                    <InputError
                                                        :message="errorFor(`seller_reports.${sellerIndex}.products.${productIndex}.quantity_return`)" />
                                                </td>
                                                <td class="px-4 py-2 text-sm font-medium text-gray-700">
                                                    {{ product.quantity_sold }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="product.wholesale_price"
                                                        @input="syncProductRow(product)" type="number" step="0.01"
                                                        list="wholesale_price_list" min="0" class="w-28 rounded border-gray-300 shadow-sm" id="wholesale_pric" />
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
                                                <td class="px-4 py-2 text-sm font-medium text-gray-700">
                                                    {{ product.line_total.toFixed(2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <button type="button"
                                                        @click="removeProduct(sellerIndex, productIndex)"
                                                        class="inline-flex items-center gap-2 px-2 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-500">
                                                        <Trash :size="14" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex justify-end">
                                    <div class="rounded-md bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700">
                                        Total del vendedor: {{ sellerTotal(sellerReport).toFixed(2) }}
                                    </div>
                                </div>
                            </div>
                        </details>

                        <div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">Crear
                                Reporte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
