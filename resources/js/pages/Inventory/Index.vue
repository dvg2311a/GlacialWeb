<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Eye, PackageSearch, Search } from 'lucide-vue-next';
import { reactive } from 'vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type InventoryRow = {
    id: number;
    stock: number;
    committed_quantity: number;
    in_transit_quantity: number;
    low_stock_threshold: number;
    available_quantity: number;
    is_low_stock: boolean;
    product?: { id: number; name: string } | null;
};

type Pagination<T> = {
    data: T[];
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    inventories: Pagination<InventoryRow>;
    summary: { stock: number; committed: number; in_transit: number; available: number; low: number; out: number };
    filters: { search?: string; status?: string };
}>();

const form = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
});

function applyFilters() {
    router.get(route('inventories.index'), form, { preserveState: true, replace: true });
}

function statusLabel(item: InventoryRow) {
    if (item.available_quantity <= 0) return 'Agotado';
    if (item.is_low_stock) return 'Bajo';
    return 'Disponible';
}

function statusClass(item: InventoryRow) {
    if (item.available_quantity <= 0) return 'bg-rose-100 text-rose-700';
    if (item.is_low_stock) return 'bg-amber-100 text-amber-700';
    return 'bg-emerald-100 text-emerald-700';
}
</script>

<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <div class="py-0 lg:py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <section class="rounded-xl border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <PackageSearch class="text-blue-700" :size="30" />
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Inventario</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Consulta existencias contables, comprometidas y disponibles.</p>
                        </div>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    <div v-for="card in [
                        ['Stock', summary.stock],
                        ['Comprometido', summary.committed],
                        ['Disponible', summary.available],
                        ['En ruta', summary.in_transit],
                        ['Stock bajo', summary.low],
                        ['Agotados', summary.out],
                    ]" :key="card[0]" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ card[0] }}</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ card[1] }}</p>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <form class="flex flex-col gap-3 md:flex-row" @submit.prevent="applyFilters">
                        <div class="relative flex-1">
                            <Search class="absolute left-3 top-2.5 text-slate-400" :size="18" />
                            <input v-model="form.search" type="search" placeholder="Buscar producto..."
                                class="w-full rounded-md border-slate-300 pl-10 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                        </div>
                        <select v-model="form.status" class="rounded-md border-slate-300 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <option value="">Todos los estados</option>
                            <option value="available">Disponible</option>
                            <option value="low">Stock bajo</option>
                            <option value="out">Agotado</option>
                        </select>
                        <button class="rounded-md bg-blue-700 px-4 py-2 font-semibold text-white hover:bg-blue-800">Filtrar</button>
                    </form>
                </section>

                <section class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800/70">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Producto</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Stock</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Comprometido</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Disponible</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">En ruta</th>
                                <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Estado</th>
                                <th class="px-5 py-3 text-right text-xs uppercase text-slate-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="item in inventories.data" :key="item.id">
                                <td class="px-5 py-4 font-semibold text-slate-800 dark:text-white">{{ item.product?.name || 'Producto eliminado' }}</td>
                                <td class="px-5 py-4 text-right dark:text-white">{{ item.stock }}</td>
                                <td class="px-5 py-4 text-right dark:text-white">{{ item.committed_quantity }}</td>
                                <td class="px-5 py-4 text-right dark:text-white font-semibold">{{ item.available_quantity }}</td>
                                <td class="px-5 py-4 text-right dark:text-white">{{ item.in_transit_quantity }}</td>
                                <td class="px-5 py-4"><span class="rounded-full px-2 py-1 text-xs font-semibold" :class="statusClass(item)">{{ statusLabel(item) }}</span></td>
                                <td class="px-5 py-4 text-right">
                                    <NavLink :href="route('inventories.show', item.id)" class="text-blue-700 hover:text-blue-900" title="Ver historial"><Eye :size="18" /></NavLink>
                                </td>
                            </tr>
                            <tr v-if="!inventories.data.length"><td colspan="7" class="px-5 py-10 text-center text-slate-500">No hay inventarios que coincidan con los filtros.</td></tr>
                        </tbody>
                    </table>
                </section>

                <nav v-if="inventories.links.length > 3" class="flex flex-wrap gap-2">
                    <a v-for="link in inventories.links" :key="link.label" :href="link.url || '#'" @click.prevent="link.url && router.visit(link.url)"
                        class="rounded-md border px-3 py-1 text-sm" :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
                </nav>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
