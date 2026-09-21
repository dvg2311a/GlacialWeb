<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowLeft, ReceiptText } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type Seller = {
    name?: string;
    surname?: string | null;
};

type Product = {
    name?: string;
};

type PrivateSaleDetail = {
    id?: number | string;
    product_id?: number | string;
    product?: Product | null;
    quantity?: number | string | null;
    unit_price?: number | string | null;
    type_price?: string | null;
    line_total?: number | string | null;
};

type PrivateSale = {
    id?: number | string;
    date_sale?: string | null;
    grand_total?: number | string | null;
    seller?: Seller | null;
    private_sale_detail?: PrivateSaleDetail[];
    privateSaleDetail?: PrivateSaleDetail[];
};

const props = defineProps<{
    private_sale?: PrivateSale;
    private_sale_detail?: PrivateSaleDetail[];
}>();

const sale = props.private_sale || {};
const details = props.private_sale_detail || sale.private_sale_detail || sale.privateSaleDetail || [];

function sellerLabel(seller?: Seller | null) {
    if (!seller) {
        return 'Cliente Particular';
    }

    return `${seller.name || ''}${seller.surname ? ` ${seller.surname}` : ''}`.trim() || '—';
}

function formatDate(value?: string | null) {
    if (!value) {
        return '—';
    }

    const datePart = value.match(/^\d{4}-\d{2}-\d{2}/)?.[0];

    if (!datePart) {
        return '—';
    }

    return new Date(`${datePart}T00:00:00`).toLocaleDateString('es-NI', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function formatCurrency(value?: number | string | null) {
    return Number(value || 0).toLocaleString('es-NI', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function valueOrDash(value?: number | string | null) {
    return value === null || value === undefined || value === '' ? '—' : value;
}
</script>

<template>
    <Head :title="`Venta particular #${sale.id || ''}`" />

    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-start gap-3">
                        <NavLink :href="route('private_sales.index')" class="mt-1 rounded-md text-slate-500 transition hover:text-blue-700" title="Volver">
                            <ArrowLeft :size="28" />
                        </NavLink>
                        <div>
                            <div class="mb-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.18em] text-blue-700">
                                <ReceiptText :size="18" />
                                Detalle de venta
                            </div>
                            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Venta particular #{{ valueOrDash(sale.id) }}</h1>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-300">Consulta la información de la venta y sus productos.</p>
                        </div>
                    </div>
                </section>

                <section class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Vendedor</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-white">{{ sellerLabel(sale.seller) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de venta</dt>
                            <dd class="mt-1 text-sm text-slate-800 dark:text-white">{{ formatDate(sale.date_sale) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Productos</dt>
                            <dd class="mt-1 text-sm text-slate-800 dark:text-white">{{ details.length }} línea(s)</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total general</dt>
                            <dd class="mt-1 text-lg font-bold text-blue-700 dark:text-blue-300">C$ {{ formatCurrency(sale.grand_total) }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Productos vendidos</h2>
                    </div>

                    <div v-if="!details.length" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-300">
                        Esta venta no tiene detalles de productos disponibles.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800/70">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Producto</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Cantidad</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Precio unitario</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo de precio</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="detail in details" :key="detail.id || `${detail.product_id}-${detail.type_price}`">
                                    <td class="px-5 py-4 text-sm font-semibold text-slate-800 dark:text-white">
                                        {{ detail.product?.name || `Producto #${valueOrDash(detail.product_id)}` }}
                                    </td>
                                    <td class="px-5 py-4 text-right text-sm text-slate-600 dark:text-slate-300">{{ valueOrDash(detail.quantity) }}</td>
                                    <td class="px-5 py-4 text-right text-sm text-slate-600 dark:text-slate-300">C$ {{ formatCurrency(detail.unit_price) }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-slate-300">{{ valueOrDash(detail.type_price) }}</td>
                                    <td class="px-5 py-4 text-right text-sm font-bold text-slate-800 dark:text-white">C$ {{ formatCurrency(detail.line_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<!-- El método show() del backend está vacío actualmente; esta vista espera private_sale
     y sus relaciones private_sale_detail/product cuando el controlador las entregue. -->
