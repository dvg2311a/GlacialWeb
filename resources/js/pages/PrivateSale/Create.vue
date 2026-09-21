<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, ChevronDown, Plus, Trash } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type ProductOption = {
    id: number | string;
    name: string;
};

type SellerOption = {
    id: number | string;
    name: string;
    surname?: string;
    type_seller?: string;
};

type ProductRow = {
    id: number | string | '';
    quantity: number;
    unit_price: number;
    line_total: number;
    type_price: 'Detalle' | 'Mayorista' | 'Malo' | '';
};

type PrivateSaleRow = {
    seller_id: number | string | '';
    products: ProductRow[];
};

type PrivateSaleForm = {
    date_sale: string;
    grand_total: number;
    seller_id: number | string | '';
    private_sales: PrivateSaleRow[];
};

const { product, seller } = defineProps<{
    private_sale?: Record<string, unknown>;
    private_sale_details?: Record<string, unknown>;
    product: ProductOption[];
    seller: SellerOption[];
}>();

const createProductRow = (): ProductRow => ({
    id: '',
    quantity: 1,
    unit_price: 0,
    line_total: 0,
    type_price: '',
});

const createSaleRow = (): PrivateSaleRow => ({
    seller_id: '',
    products: [createProductRow()],
});

const form = useForm<PrivateSaleForm>({
    date_sale: new Date().toISOString().slice(0, 10),
    grand_total: 0,
    seller_id: '',
    private_sales: [createSaleRow()],
});

const expandedSales = ref<Record<number, boolean>>({ 0: true });

const errorFor = (path: string) => form.errors[path as keyof typeof form.errors];

function syncProductRow(row: ProductRow) {
    row.quantity = Math.max(Number(row.quantity) || 0, 0);
    row.unit_price = Math.max(Number(row.unit_price) || 0, 0);
    row.line_total = row.quantity * row.unit_price;
    form.grand_total = totalSales();
}

function addSale() {
    form.private_sales.push(createSaleRow());
    expandedSales.value[form.private_sales.length - 1] = true;
}

function removeSale(index: number) {
    form.private_sales.splice(index, 1);

    expandedSales.value = Object.fromEntries(
        Object.entries(expandedSales.value)
            .filter(([key]) => Number(key) !== index)
            .map(([key, value]) => [Number(key) > index ? Number(key) - 1 : Number(key), value]),
    );

    if (!form.private_sales.length) {
        addSale();
    }

    form.grand_total = totalSales();
}

function toggleSale(index: number) {
    expandedSales.value[index] = !expandedSales.value[index];
}

function addProduct(saleIndex: number) {
    form.private_sales[saleIndex].products.push(createProductRow());
}

function removeProduct(saleIndex: number, productIndex: number) {
    const products = form.private_sales[saleIndex].products;
    products.splice(productIndex, 1);

    if (!products.length) {
        products.push(createProductRow());
    }

    form.grand_total = totalSales();
}

function saleTotal(sale: PrivateSaleRow) {
    return sale.products.reduce((total, row) => total + Number(row.line_total || 0), 0);
}

function totalSales() {
    return form.private_sales.reduce((total, sale) => total + saleTotal(sale), 0);
}

function sellerLabel(sellerId: PrivateSaleRow['seller_id']) {
    const selectedSeller = (seller || []).find((item) => String(item.id) === String(sellerId));

    return selectedSeller ? `${selectedSeller.name}${selectedSeller.surname ? ` ${selectedSeller.surname}` : ''}` : 'Selecciona un vendedor';
}

function sellerTypeLabel(sellerId: PrivateSaleRow['seller_id']) {
    const selectedSeller = (seller || []).find((item) => String(item.id) === String(sellerId));

    return selectedSeller?.type_seller || 'Tipo de vendedor no disponible';
}

function formatCurrency(value: number) {
    return Number(value || 0).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function submit() {
    form.grand_total = totalSales();

    // PrivateSaleRequest actualmente valida date_sale, grand_total y seller_id en la raíz,
    // mientras PrivateSaleController procesa private_sales[].type_price se captura en cada
    // línea porque el enum pertenece al detalle del producto; el backend actual lo lee desde
    // $request->type_price, por lo que esa asignación deberá alinearse posteriormente allí.
    form.post(route('private_sales.store'), {
        onSuccess: () => {
            const Swal = (window as unknown as { Swal?: { fire: (...args: unknown[]) => void } }).Swal;
            Swal?.fire({
                title: '¡Éxito!',
                text: 'La venta particular ha sido registrada.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 2500,
            });
        },
    });
}
</script>

<template>

    <Head title="Crear Venta Particular" />

    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section
                    class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-start gap-3">
                        <NavLink :href="route('private_sales.index')"
                            class="mt-1 rounded-md text-slate-500 transition hover:text-blue-700" title="Volver">
                            <ArrowLeft :size="28" />
                        </NavLink>
                        <div>

                            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Crear venta
                                particular</h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Registra una o varias ventas por
                                vendedor y calcula sus totales automáticamente.
                                Si no es un vendedor registrado por la administradora, entonces es un cliente
                                particular.
                                Si lleva productos al por mayor y no está registrado como vendedor, entonces consulte
                                con la administradora.</p>
                        </div>
                    </div>
                </section>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <section
                        class="rounded-xl border border-slate-200 flex justify-between items-center bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="max-w-xs">
                            <label for="date_sale"
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Fecha de
                                venta</label>
                            <input id="date_sale" v-model="form.date_sale" type="date" required
                                class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600" />
                            <InputError :message="form.errors.date_sale" />
                        </div>
                        <button type="button"
                            class="h-10 inline-flex items-center justify-center gap-2 rounded-md border border-blue-700 px-4 text-sm font-semibold text-gray-700 transition hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-blue-900/20"
                            @click="addSale">
                            <Plus :size="18" />
                            Añadir otro vendedor
                        </button>
                    </section>

                    <section v-for="(sale, saleIndex) in form.private_sales" :key="saleIndex"
                        class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div
                            class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                            <div>
                                <button type="button"
                                    class="flex min-w-0 flex-1 items-center justify-between gap-4 text-left"
                                    :aria-expanded="expandedSales[saleIndex]"
                                    :aria-controls="`private-sale-form-${saleIndex}`" @click="toggleSale(saleIndex)">
                                    <span class="min-w-0">
                                        <span
                                            class="block text-xs font-semibold uppercase tracking-wide text-blue-700">Venta
                                            {{ saleIndex + 1 }}</span>
                                        <span
                                            class="mt-1 block truncate text-xl font-semibold text-slate-800 dark:text-white">{{
                                            sellerLabel(sale.seller_id) }}</span>
                                        <span class="mt-1 block text-sm text-slate-500 dark:text-slate-300">Total: C$ {{
                                            formatCurrency(saleTotal(sale)) }}</span>
                                    </span>
                                    <ChevronDown :size="22" class="shrink-0 text-slate-500 transition-transform"
                                        :class="{ 'rotate-180': expandedSales[saleIndex] }" />
                                </button>
                            </div>
                            <button type="button"
                                class="inline-flex items-center  justify-center gap-2 rounded-md border border-rose-700 px-3 py-2 text-sm font-semibold text-rose-700 dark:text-rose-300 transition hover:bg-rose-700/20"
                                @click="removeSale(saleIndex)">
                                <Trash :size="16" />
                                Quitar venta
                            </button>
                        </div>

                        <div v-if="expandedSales[saleIndex]" :id="`private-sale-form-${saleIndex}`" class="mt-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                <div class="w-[250px] max-w-sm">
                                    <label :for="`seller-${saleIndex}`"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Vendedor</label>
                                    <select :id="`seller-${saleIndex}`" v-model="sale.seller_id"
                                        class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                                        <option value="">Seleccionar vendedor</option>
                                        <option v-for="item in seller || []" :key="item.id" :value="item.id">{{
                                            item.name }} {{ item.surname }}</option>
                                    </select>
                                    <InputError :message="errorFor(`private_sales.${saleIndex}.seller_id`)" />
                                </div>
                                <div class="type_seller min-w-[180px]">
                                    <span class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Tipo de vendedor</span>
                                    <span class="mt-2 block text-md text-slate-500 dark:text-slate-300">{{ sellerTypeLabel(sale.seller_id) }}</span>
                                </div>
                                <button type="button"
                                    class="inline-flex items-center justify-center gap-2 rounded-md border border-green-700 px-3 py-2 text-sm font-semibold text-green-700 dark:text-green-300 transition hover:bg-green-700/20"
                                    @click="addProduct(saleIndex)">
                                    <Plus :size="16" />
                                    Añadir producto
                                </button>
                            </div>

                            <div class="mt-5 overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                                <table class="min-w-[760px] w-full divide-y divide-slate-200 dark:divide-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-800/70">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Producto</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Cantidad</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Precio unitario</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Tipo de precio</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Subtotal</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <tr v-for="(row, productIndex) in sale.products" :key="productIndex">
                                            <td class="px-4 py-3">
                                                <select v-model="row.id" required
                                                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                                                    @change="syncProductRow(row)">
                                                    <option value="">Seleccionar producto</option>
                                                    <option v-for="item in product || []" :key="item.id"
                                                        :value="item.id">{{ item.name }}</option>
                                                </select>
                                                <InputError
                                                    :message="errorFor(`private_sales.${saleIndex}.products.${productIndex}.id`)" />
                                            </td>
                                            <td class="px-4 py-3">
                                                <input v-model.number="row.quantity" type="number" min="1" required
                                                    class="w-28 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                                                    @input="syncProductRow(row)" />
                                                <InputError
                                                    :message="errorFor(`private_sales.${saleIndex}.products.${productIndex}.quantity`)" />
                                            </td>
                                            <td class="px-4 py-3">
                                                <input list="unit_price" v-model.number="row.unit_price" type="number"
                                                    min="0" step="0.01" required
                                                    class="w-36 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                                                    @input="syncProductRow(row)" />
                                                <datalist id="unit_price">
                                                    <option value="10.00" />
                                                    <option value="13.00" />
                                                    <option value="15.00" />
                                                    <option value="16.00" />
                                                    <option value="21.00" />
                                                    <option value="40.00" />
                                                </datalist>
                                                <InputError
                                                    :message="errorFor(`private_sales.${saleIndex}.products.${productIndex}.unit_price`)" />
                                            </td>
                                            <td class="px-4 py-3">
                                                <select v-model="row.type_price" required
                                                    class="w-36 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                                                    <option value="">Seleccionar tipo</option>
                                                    <option value="Detalle">Detalle</option>
                                                    <option value="Mayorista">Mayorista</option>
                                                    <option value="Malo">Malo</option>
                                                </select>
                                                <InputError
                                                    :message="errorFor(`private_sales.${saleIndex}.products.${productIndex}.type_price`)" />
                                            </td>
                                            <td
                                                class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                C$ {{ formatCurrency(row.line_total) }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <button type="button"
                                                    class="rounded-md p-2 text-rose-600 transition hover:bg-rose-50 hover:text-rose-700"
                                                    title="Eliminar producto"
                                                    @click="removeProduct(saleIndex, productIndex)">
                                                    <Trash :size="18" />
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm dark:bg-slate-800">
                                    <span class="text-slate-500 dark:text-slate-300">Total de la venta:</span>
                                    <strong class="ml-2 text-lg text-gray-700 dark:text-gray-300">C$ {{
                                        formatCurrency(saleTotal(sale)) }}</strong>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="flex flex-col gap-4 sm:flex-row items-end sm:justify-end w-full ">

                        <div
                            class="flex items-center justify-between rounded-lg bg-slate-900 px-5 py-3 text-white shadow-sm sm:min-w-[280px]">
                            <span class="text-sm text-slate-300">Total general:</span>
                            <strong class="text-xl text-blue-300">C$ {{ formatCurrency(totalSales()) }}</strong>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center justify-center rounded-md bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60">
                            {{ form.processing ? 'Guardando...' : 'Guardar venta particular' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
