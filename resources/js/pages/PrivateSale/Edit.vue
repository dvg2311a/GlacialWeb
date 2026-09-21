<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Trash } from 'lucide-vue-next';
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
};

type ProductRow = {
    id: number | string | '';
    quantity: number;
    unit_price: number;
    line_total: number;
    type_price: 'Detalle' | 'Mayorista' | 'Malo' | '';
};

type PrivateSaleForm = {
    date_sale: string;
    grand_total: number;
    seller_id: number | string | '';
    type_price: ProductRow['type_price'];
    private_sales: ProductRow[];
};

type ExistingDetail = {
    id?: number | string;
    product_id?: number | string;
    quantity?: number | string | null;
    unit_price?: number | string | null;
    line_total?: number | string | null;
    type_price?: ProductRow['type_price'];
};

type ExistingSale = {
    id?: number | string;
    date_sale?: string | null;
    grand_total?: number | string | null;
    seller_id?: number | string | null;
    seller?: SellerOption | null;
    private_sale_detail?: ExistingDetail[];
    privateSaleDetail?: ExistingDetail[];
};

const props = defineProps<{
    private_sale: ExistingSale;
    product: ProductOption[];
    seller: SellerOption[];
}>();

const details = props.private_sale.private_sale_detail || props.private_sale.privateSaleDetail || [];

function dateValue(value?: string | null) {
    return value?.match(/^\d{4}-\d{2}-\d{2}/)?.[0] || new Date().toISOString().slice(0, 10);
}

function createProductRow(detail?: ExistingDetail): ProductRow {
    const quantity = Number(detail?.quantity ?? 1);
    const unitPrice = Number(detail?.unit_price ?? 0);

    return {
        id: detail?.product_id ?? '',
        quantity,
        unit_price: unitPrice,
        line_total: Number(detail?.line_total ?? quantity * unitPrice),
        type_price: detail?.type_price || '',
    };
}

const initialProducts = details.length ? details.map(createProductRow) : [createProductRow()];

const form = useForm<PrivateSaleForm>({
    date_sale: dateValue(props.private_sale.date_sale),
    grand_total: Number(props.private_sale.grand_total || 0),
    seller_id: props.private_sale.seller_id || props.private_sale.seller?.id || '',
    type_price: initialProducts[0].type_price,
    private_sales: initialProducts,
});

const errorFor = (path: string) => form.errors[path as keyof typeof form.errors];

function syncProductRow(row: ProductRow) {
    row.quantity = Math.max(Number(row.quantity) || 0, 0);
    row.unit_price = Math.max(Number(row.unit_price) || 0, 0);
    row.line_total = row.quantity * row.unit_price;
    form.grand_total = totalSales();
}

function addProduct() {
    form.private_sales.push(createProductRow());
}

function removeProduct(index: number) {
    form.private_sales.splice(index, 1);

    if (!form.private_sales.length) {
        form.private_sales.push(createProductRow());
    }

    form.grand_total = totalSales();
}

function totalSales() {
    return form.private_sales.reduce((total, row) => total + Number(row.line_total || 0), 0);
}

function sellerLabel() {
    const selectedSeller = (props.seller || []).find((item) => String(item.id) === String(form.seller_id));

    return selectedSeller ? `${selectedSeller.name}${selectedSeller.surname ? ` ${selectedSeller.surname}` : ''}` : 'Cliente particular';
}

function formatCurrency(value: number) {
    return Number(value || 0).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function submit() {
    form.grand_total = totalSales();
    form.type_price = form.private_sales[0]?.type_price || '';

    form.put(route('private_sales.update', props.private_sale.id), {
        onSuccess: () => {
            const Swal = (window as unknown as { Swal?: { fire: (...args: unknown[]) => void } }).Swal;
            Swal?.fire({
                title: '¡Éxito!',
                text: 'La venta privada ha sido actualizada.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 2500,
            });
        },
    });
}
</script>

<template>
    <Head title="Editar Venta Privada" />

    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-start gap-3">
                        <NavLink :href="route('private_sales.index')" class="mt-1 rounded-md text-slate-500 transition hover:text-blue-700" title="Volver">
                            <ArrowLeft :size="28" />
                        </NavLink>
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Editar venta privada</h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Actualiza el vendedor, la fecha y los productos registrados en esta venta.</p>
                        </div>
                    </div>
                </section>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:flex-row sm:items-end sm:justify-between">
                        <div class="">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Venta #{{ props.private_sale.id }}</p>
                            <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-white">{{ sellerLabel() }}</p>
                        </div>
                        <div class="w-full max-w-sm">
                            <label for="date_sale" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Fecha de venta</label>
                            <input id="date_sale" v-model="form.date_sale" type="date" required class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600" />
                            <InputError :message="form.errors.date_sale" />
                        </div>
                    </section>

                    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div class="w-full max-w-sm">
                                <label for="seller" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Vendedor</label>
                                <select id="seller" v-model="form.seller_id" class="mt-2 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                                    <option value="">Cliente particular</option>
                                    <option v-for="item in seller || []" :key="item.id" :value="item.id">{{ item.name }} {{ item.surname }}</option>
                                </select>
                                <InputError :message="form.errors.seller_id" />
                            </div>
                            <button type="button" class="inline-flex items-center justify-center gap-2 rounded-md border border-green-700 px-3 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-700/20 dark:text-green-300" @click="addProduct">
                                <Plus :size="16" />
                                Añadir producto
                            </button>
                        </div>

                        <div class="mt-5 overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                            <table class="min-w-[760px] w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800/70">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Producto</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cantidad</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Precio unitario</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo de precio</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Subtotal</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr v-for="(row, productIndex) in form.private_sales" :key="productIndex">
                                        <td class="px-4 py-3">
                                            <select v-model="row.id" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600" @change="syncProductRow(row)">
                                                <option value="">Seleccionar producto</option>
                                                <option v-for="item in product || []" :key="item.id" :value="item.id">{{ item.name }}</option>
                                            </select>
                                            <InputError :message="errorFor(`private_sales.${productIndex}.id` as string)" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <input v-model.number="row.quantity" type="number" min="1" required class="w-28 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600" @input="syncProductRow(row)" />
                                            <InputError :message="errorFor(`private_sales.${productIndex}.quantity` as string)" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <input list="unit_price" v-model.number="row.unit_price" type="number" min="0" step="0.10" required class="w-36 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600" @input="syncProductRow(row)" />
                                            <datalist id="unit_price">
                                                            <option value="10.00" />
                                                            <option value="13.00" />
                                                            <option value="15.00" />
                                                            <option value="16.00" />
                                                            <option value="21.00" />
                                                            <option value="40.00" />
                                                        </datalist>
                                            <InputError :message="errorFor(`private_sales.${productIndex}.unit_price` as string)" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <select v-model="row.type_price" required class="w-36 rounded-md border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                                                <option value="">Seleccionar tipo</option>
                                                <option value="Detalle">Detalle</option>
                                                <option value="Mayorista">Mayorista</option>
                                                <option value="Malo">Malo</option>
                                            </select>
                                            <InputError :message="errorFor(`private_sales.${productIndex}.type_price` as string)" />
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">C$ {{ formatCurrency(row.line_total) }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" class="rounded-md p-2 text-rose-600 transition hover:bg-rose-50 hover:text-rose-700" title="Eliminar producto" @click="removeProduct(productIndex)">
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
                                <strong class="ml-2 text-lg text-gray-700 dark:text-gray-300">C$ {{ formatCurrency(totalSales()) }}</strong>
                            </div>
                        </div>
                    </section>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center rounded-md bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60">
                            {{ form.processing ? 'Guardando...' : 'Actualizar venta privada' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
