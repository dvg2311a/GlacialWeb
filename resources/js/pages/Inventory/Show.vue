<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    inventory: {
        id: number;
        stock: number;
        committed_quantity: number;
        in_transit_quantity: number;
        available_quantity: number;
        product?: { name: string } | null;
        transactions: {
            id: number;
            movement_type: string;
            quantity: number;
            stock_before: number;
            stock_after: number;
            committed_before: number;
            committed_after: number;
            reference_type: string;
            reference_id: number;
            notes?: string | null;
            created_at: string;
            user?: { name: string; surname?: string | null } | null;
        }[];
    };
}>();
</script>

<template>
    <Head :title="`Inventario: ${inventory.product?.name || 'Producto'}`" />
    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <NavLink :href="route('inventories.index')" class="text-slate-500"><ArrowLeft /></NavLink>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ inventory.product?.name || 'Producto' }}</h1>
                        <p class="text-sm text-slate-500">Historial de movimientos de inventario</p>
                    </div>
                </div>

                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="item in [
                        ['Stock', inventory.stock],
                        ['Comprometido', inventory.committed_quantity],
                        ['Disponible', inventory.available_quantity],
                        ['En ruta', inventory.in_transit_quantity],
                    ]" :key="item[0]" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs uppercase text-slate-500">{{ item[0] }}</p>
                        <p class="mt-2 text-2xl font-bold dark:text-white">{{ item[1] }}</p>
                    </div>
                </section>

                <section class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800/70">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Fecha</th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Movimiento</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Cantidad</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Stock</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Comprometido</th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Referencia</th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="transaction in inventory.transactions" :key="transaction.id">
                                <td class="px-5 py-4 text-sm">{{ new Date(transaction.created_at).toLocaleString('es-NI') }}</td>
                                <td class="px-5 py-4 text-sm font-semibold">{{ transaction.movement_type }}</td>
                                <td class="px-5 py-4 text-right text-sm">{{ transaction.quantity }}</td>
                                <td class="px-5 py-4 text-right text-sm">{{ transaction.stock_before }} → {{ transaction.stock_after }}</td>
                                <td class="px-5 py-4 text-right text-sm">{{ transaction.committed_before }} → {{ transaction.committed_after }}</td>
                                <td class="px-5 py-4 text-sm">{{ transaction.reference_type }} #{{ transaction.reference_id }}</td>
                                <td class="px-5 py-4 text-sm">{{ transaction.user ? `${transaction.user.name} ${transaction.user.surname || ''}` : 'Sistema' }}</td>
                            </tr>
                            <tr v-if="!inventory.transactions.length"><td colspan="7" class="px-5 py-10 text-center text-slate-500">No hay movimientos registrados.</td></tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
