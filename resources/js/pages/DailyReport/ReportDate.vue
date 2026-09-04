<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowLeft, CalendarDays, Eye, ReceiptText, SquarePen, Users } from 'lucide-vue-next';
import { computed } from 'vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

type Seller = {
	id: number | string;
	name: string;
	surname?: string | null;
};

type User = {
	name: string;
	surname?: string | null;
};

type DailyReportDetail = {
	user?: User | null;
};

type DailyReport = {
	id: number | string;
	grand_total?: number | string | null;
	seller?: Seller | null;
	seller_daily_report_detail?: DailyReportDetail[];
};

const props = defineProps<{
	report_date: string;
	total_sale: number | string | null;
	reports: DailyReport[];
}>();

const formattedDate = computed(() => {
	const date = new Date(`${props.report_date}T00:00:00`);

	return date.toLocaleDateString('es-NI', {
		day: '2-digit',
		month: 'long',
		year: 'numeric',
	});
});

const formattedTotal = computed(() => currency(Number(props.total_sale) || 0));

function currency(value: number) {
	return new Intl.NumberFormat('es-NI', {
		style: 'currency',
		currency: 'NIO',
		minimumFractionDigits: 2,
	}).format(value);
}

function sellerLabel(seller?: Seller | null) {
	if (!seller) {
		return 'Sin vendedor asignado';
	}

	return `${seller.name}${seller.surname ? ` ${seller.surname}` : ''}`;
}

function userLabel(details?: DailyReportDetail[]) {
	const user = details?.find((detail) => detail.user)?.user;

	if (!user) {
		return 'Sin usuario asignado';
	}

	return `${user.name}${user.surname ? ` ${user.surname}` : ''}`;
}

const attendedUsers = computed(() => {
	const users = props.reports
		.map((report) => userLabel(report.seller_daily_report_detail))
		.filter((user) => user !== 'Sin usuario asignado');

	return [...new Set(users)].join(', ') || 'Sin usuario asignado';
});
</script>

<template>
	<Head :title="`Reporte del ${formattedDate}`" />

	<AuthenticatedLayout>
		<div class="py-6 lg:py-12">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<div class="mb-8 flex items-start gap-3">
					<NavLink
						:href="route('seller_daily_reports.index')"
						class="mt-1 rounded-md p-1 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
						title="Volver a reportes"
					>
						<ArrowLeft :size="28" />
					</NavLink>

					<div>
						<h1 class="text-2xl font-bold mt-1">
							Resumen diario
						</h1>
						<!-- <p class="text-2xl font-bold tracking-tight text-gray-100 sm:text-3xl">
							Reporte del {{ formattedDate }}
						</p> -->
						<p class="mt-2 text-sm text-gray-300">
							Consulta las ventas registradas por cada vendedor durante este día.
						</p>
					</div>
				</div>

				<div class="mb-6 grid gap-4 sm:grid-cols-4">
					<div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
						<div class="flex items-center gap-3 text-gray-500">
							<CalendarDays :size="20" class="text-blue-600" />
							<span class="text-sm font-medium">Fecha del reporte</span>
						</div>
						<p class="mt-3 text-lg font-semibold capitalize text-gray-900">{{ formattedDate }}</p>
					</div>

					<div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
						<div class="flex items-center gap-3 text-gray-500">
							<Users :size="20" class="text-emerald-600" />
							<span class="text-sm font-medium">Vendedores</span>
						</div>
						<p class="mt-3 text-2xl font-bold text-gray-900">{{ reports.length }}</p>
					</div>

					<div class="rounded-lg border border-gray-200 bg-blue-700 p-5 shadow-sm">
						<div class="flex items-center gap-3 text-blue-100">
							<ReceiptText :size="20" />
							<span class="text-sm font-medium">Total vendido</span>
						</div>
						<p class="mt-3 text-2xl font-bold text-white">{{ formattedTotal }}</p>
					</div>

					<div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
						<div class="flex items-center gap-3 text-gray-900">
							<Users :size="20" />
							<span class="text-sm font-medium text-gray-900">Usuario que atendió</span>
						</div>
						<p class="mt-3 text-lg font-bold text-gray-900">{{ attendedUsers }}</p>
					</div>
				</div>

				<section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
					<div class="border-b border-gray-200 px-5 py-4 sm:px-6">
						<h2 class="font-semibold text-gray-900">Ventas por vendedor</h2>
						<p class="mt-1 text-sm text-gray-500">{{ reports.length }} reporte(s) registrado(s)</p>
					</div>

					<div v-if="reports.length" class="overflow-auto w-[428px] lg:w-full lg:p-0 lg:overflow-hidden pr-4 pb-4 scrollbar-thin scrollbar-thumb-gray-400"
                        style="-webkit-overflow-scrolling: touch; touch-action: pan-x; overscroll-behavior-x: contain;">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Vendedor</th>
									<th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Reporte</th>
									<th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Total vendido</th>
									<th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Acciones</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-200 bg-white">
								<tr v-for="report in reports" :key="report.id" class="transition hover:bg-gray-50">
									<td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ sellerLabel(report.seller) }}</td>
									<td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">#{{ report.id }}</td>
									<td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-900">{{ currency(Number(report.grand_total) || 0) }}</td>
									<td class="whitespace-nowrap px-5 py-4 text-right">
										<div class="flex justify-end gap-3">
											<NavLink
												:href="route('seller_daily_reports.show', { seller_daily_report: report.id })"
												class="text-green-600 transition hover:text-green-800"
												title="Ver reporte"
											>
												<Eye :size="20" />
											</NavLink>
											<NavLink
												:href="route('seller_daily_reports.edit', { seller_daily_report: report.id })"
												class="text-indigo-600 transition hover:text-indigo-800"
												title="Editar reporte"
											>
												<SquarePen :size="20" />
											</NavLink>
										</div>
									</td>
								</tr>
							</tbody>
						</table>

					</div>

					<div v-else class="px-6 py-14 text-center">
						<CalendarDays :size="36" class="mx-auto text-gray-300" />
						<h3 class="mt-4 font-semibold text-gray-900">No hay reportes para esta fecha</h3>
						<p class="mt-1 text-sm text-gray-500">Selecciona otro día desde el listado de reportes.</p>
					</div>
				</section>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
