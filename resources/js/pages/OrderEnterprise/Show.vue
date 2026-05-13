<script setup>
import { Head } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import NavLink from '@/Components/NavLink.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
	order: Object,
	purchase: Array,
    product: Array,
})




function formatDate(dateStr) {
    if (!dateStr) return '-'
    const date = new Date(dateStr)
    return date.toLocaleDateString()
}

function formatCurrency(value) {
	if (value === null || value === undefined) {
		return '-'
	}

	const num = Number(value)
	if (Number.isNaN(num)) {
		return value
	}

	return num.toFixed(2)
}

function lineSubtotal(detail) {
	const price = Number(detail.purchase_price_per_box) || 0
	const boxes = Number(detail.boxes_received) || 0

	return price * boxes
}

function lineTax(detail) {
	const price = Number(detail.purchase_price_per_box) || 0
	const taxPerc = Number(detail.tax_percentage) || 0
	const boxes = Number(detail.boxes_received) || 0

	return price * (taxPerc / 100) * boxes
}

function lineTotal(detail) {

	return lineSubtotal(detail) + lineTax(detail)
}

function getProductName(detail) {
	// If detail contains a nested product object with a name
	if (detail && detail.product && typeof detail.product === 'object') {
		if (detail.product.name) return detail.product.name
		if (detail.product.product_name) return detail.product.product_name
	}

	// If detail.product is an id (number or string)
	let id = null
	if (detail && (typeof detail.product === 'number' || typeof detail.product === 'string')) {
		id = detail.product
	}

	// Other common id fields
	if (id == null && detail) {
		id = detail.product_id ?? detail.productId ?? null
	}

	// If detail has product_name field, prefer it
	if (detail && detail.product_name) return detail.product_name

	// Try to resolve from `product` prop which may be an array, object map, or single product
	const prodProp = typeof product !== 'undefined' ? product : null
	if (id != null && prodProp) {
		// If prop is array
		if (Array.isArray(prodProp)) {
			const found = prodProp.find(p => p && (p.id === id || String(p.id) === String(id)))
			if (found) return found.name || found.product_name || '-'
		} else if (typeof prodProp === 'object') {
			// If prop is a map of id->product
			if (prodProp[id]) {return prodProp[id].name || prodProp[id].product_name || '-'}
			// If prop is a single product object
			if (prodProp.id && String(prodProp.id) === String(id)) return prodProp.name || prodProp.product_name || '-'
		}
	}

	return '-'
}
</script>

<template>

	<Head title="Detalle Pedido Empresa" />
	<AuthenticatedLayout>
		<div class="py-2 w-full mr-4 -translate-x-20">

			<div class="w-min-full mx-auto -sm:px-6 -lg-px-8">
				<div class="overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-4 ml-4 flex items-center gap-4">
						<NavLink :href="route('orders_enterprises.index')"
							class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft class="w-5 h-5" />
						</NavLink>
						<h1 class="text-2xl font-bold">Detalle del Pedido Empresa</h1>
					</div>
				</div>

				<!-- debug removed -->


				<div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
					<div class="p-6 bg-white rounded-lg shadow-md">
						<div class="flex gap-4 justify-between px-4">
							<div class="">
								<h2 class="text-sm font-medium text-gray-500">Fecha de Pedido</h2>
								<p class="mt-1 text-gray-800">{{ formatDate(order.order_date) }}</p>
							</div>
							<div>
								<h2 class="text-sm font-medium text-gray-500">Fecha Esperada</h2>
								<p class="mt-1 text-gray-800">{{ formatDate(order.expected_date) }}</p>
							</div>
							<div>
								<h2 class="text-sm font-medium text-gray-500">Estado</h2>
								<p class="mt-1 text-gray-800">{{ order.order_status }}</p>
							</div>
						</div>

						<div class="mt-6 w-full overflow-x-auto">
							<table class="min-w-[1000px] table-auto w-full divide-y divide-gray-200">
								<thead>
									<tr class="text-left bg-gray-100">
										<th class="px-2 py-2 whitespace-nowrap">Producto</th>
										<th class="px-2 py-2 whitespace-nowrap">Cajas Pedidas</th>
										<th class="px-2 py-2 whitespace-nowrap">Cajas Recibidas</th>
										<th class="px-2 py-2 whitespace-nowrap">Precio x Caja</th>
										<th class="px-2 py-2 whitespace-nowrap">% Impuesto</th>
										<th class="px-2 py-2 whitespace-nowrap">Subtotal</th>
										<th class="px-2 py-2 whitespace-nowrap">Impuesto Línea</th>
										<th class="px-2 py-2 whitespace-nowrap">Total Línea</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(d, idx) in (purchase || [])" :key="idx" class="align-top bg-white">
										<td class="px-2 py-2 whitespace-nowrap">
											{{ (d.product && d.product.name) || ((product || []).find(p => String(p.id) === String((d.product && d.product.id) || d.product_id || d.productId)) || {}).name || 'no-match' }}
										</td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ d.boxes_ordered }}</td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ d.boxes_received }}</td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ formatCurrency(d.purchase_price_per_box) }}</td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ (d.tax_percentage != null ? d.tax_percentage : 0) }}%  </td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ formatCurrency(lineSubtotal(d)) }}</td>
										<td class="px-2 py-2 whitespace-nowrap text-center">{{ formatCurrency(lineTax(d)) }}</td>
										<td class="px-2 py-2 whitespace-nowrap">{{ formatCurrency(lineTotal(d)) }}</td>
										<!-- Debug lookup: show raw product id and name found in `product` prop -->
									</tr>
								</tbody>
							</table>
						</div>

						<div class="mt-6 flex justify-end gap-6">
							<div class="text-right">
								<div class="text-sm text-gray-600">Subtotal Base</div>
								<div class="font-medium text-gray-900">{{ formatCurrency(order.subtotal_base) }}</div>
							</div>
							<div class="text-right">
								<div class="text-sm text-gray-600">Total Impuestos</div>
								<div class="font-medium text-gray-900">{{ formatCurrency(order.total_tax) }}</div>
							</div>
							<div class="text-right">
								<div class="text-sm text-gray-600">Gran Total</div>
								<div class="font-medium text-gray-900">{{ formatCurrency(order.grand_total) }}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>

</template>

