<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { CalendarDays, Eye, Pencil, Plus, ReceiptText, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type PrivateSale = {
    id: number | string;
    grand_total?: number | string | null;
    seller?: { name?: string | null } | null;
    private_sale_detail?: unknown[];
    privateSaleDetail?: unknown[];
};

type PrivateSaleGroup = Record<string, PrivateSale[]>;

const props = defineProps<{
    private_sale_group: PrivateSaleGroup;
}>();

function formatDate(dateString: string) {
    return new Date(`${dateString}T00:00:00`).toLocaleDateString('es-NI', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function formatCurrency(value: number | string | null | undefined) {
    return Number(value || 0).toLocaleString('es-NI', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function detailCount(sale: PrivateSale) {
    return sale.private_sale_detail?.length || sale.privateSaleDetail?.length || 0;
}

function confirmDelete(id: number | string) {
    const Swal = (window as unknown as {
        Swal?: {
            fire: (options: Record<string, unknown>) => Promise<{ isConfirmed?: boolean }>;
        };
    }).Swal;

    if (!Swal) {
        return;
    }

    Swal.fire({
        title: '¿Eliminar esta venta?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#be123c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('private_sales.destroy', id), {
                onSuccess: () => {
                    Swal.fire({
                        title: '¡Eliminada!',
                        text: 'La venta particular fue eliminada.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        timer: 2500,
                    });
                },
            });
        }
    });
}
</script>

<template>
    <Head title="Ventas Particulares" />

    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Ventas particulares</h1>
                            <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-300">
                                Consulta las ventas particulares agrupadas por fecha y revisa el total registrado para cada operación.
                            </p>
                        </div>

                        <NavLink
                            :href="route('private_sales.create')"
                            class="flex items-center justify-center gap-2 rounded-md bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 "
                        >
                            <Plus :size="18" />
                            Nueva venta
                        </NavLink>
                    </div>
                </section>

                <div v-if="!Object.keys(private_sale_group || {}).length" class="mt-6 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm dark:border-slate-600 dark:bg-slate-900">
                    <ReceiptText class="mx-auto text-slate-400" :size="40" />
                    <h2 class="mt-4 text-lg font-semibold text-slate-800 dark:text-white">No hay ventas particulares registradas</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Crea la primera venta para verla aparecer en este listado.</p>
                </div>

                <section
                    v-for="(sales, date) in props.private_sale_group"
                    :key="date"
                    class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
                >
                    <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-blue-50 p-2 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                <CalendarDays :size="20" />
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold capitalize text-slate-800 dark:text-white">{{ formatDate(date) }}</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-300">{{ sales.length }} venta(s) registradas</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total del día</p>
                            <p class="text-xl font-bold text-blue-700 dark:text-blue-300">
                                C$ {{ formatCurrency(sales.reduce((total: number, sale: PrivateSale) => total + Number(sale.grand_total || 0), 0)) }}
                            </p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800/70">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Vendedor</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Venta</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Líneas de producto</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Total</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="sale in sales" :key="sale.id" class="transition hover:bg-blue-50/40 dark:hover:bg-slate-800/60">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-800 dark:text-white">{{ sale.seller?.name || 'Cliente particular' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-800 dark:text-white">#{{ sale.id }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-slate-300">{{ detailCount(sale) }} producto(s)</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-bold text-slate-800 dark:text-white">C$ {{ formatCurrency(sale.grand_total) }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <NavLink :href="route('private_sales.show', sale.id)" class="text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            <Eye  />
                                        </NavLink>
                                        <NavLink :href="route('private_sales.edit', sale.id)" class="ml-3 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                                            <Pencil :size="18" />
                                        </NavLink>
                                        <button type="button" class="ml-2 text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300" title="Eliminar" @click="confirmDelete(sale.id)">
                                            <Trash :size="18" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

