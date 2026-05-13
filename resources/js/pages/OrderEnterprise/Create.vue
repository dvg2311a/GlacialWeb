<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus, Trash } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
	products: Array,
});

const form = useForm({
	order_date: '',
	expected_date: '',
	order_status: 'Pending',
	products: [],
	total_tax: 0,
	subtotal_base: 0,
	grand_total: 0,
});

const Swal = window.Swal;

function addProduct() {
	form.products.push({
		id: '',
		boxes_ordered: 0,
		boxes_received: 0,
		purchase_price_per_box: 0,
		tax_percentage: 0,
	});
}

function removeProduct(index) {
	form.products.splice(index, 1);
	recalcTotals();
}

function recalcTotals() {
	let totalTaxGeneral = 0;
	let lineSubtotal = 0;
	let grandTotalLine = 0;

	form.products.forEach(p => {
		const purchase = Number(p.purchase_price_per_box) || 0;
		const taxPerc = Number(p.tax_percentage) || 0;
		const boxes = Number(p.boxes_received) || 0;

		const taxAmount = purchase * (taxPerc / 100);
		const totalPerBox = purchase + taxAmount;

		totalTaxGeneral += taxAmount * boxes;
		lineSubtotal += purchase * boxes;
		grandTotalLine += totalPerBox * boxes;
	});

	form.total_tax = totalTaxGeneral.toFixed(2);
	form.subtotal_base = lineSubtotal.toFixed(2);
	form.grand_total = grandTotalLine.toFixed(2);
}

function submit() {
	recalcTotals();
	form.post(route('orders_enterprises.store'), {
		onSuccess: () => {
			Swal.fire({
				title: '¡Éxito!',
				text: 'Pedido creado exitosamente.',
				icon: 'success',
				confirmButtonText: 'Aceptar',
				timer: 2500,
				timerProgressBar: true,
			});
		}
	});
}

// init with one row
addProduct();
</script>

<template>
	<Head title="Crear Orden de Empresa" />
	<AuthenticatedLayout>
		<div class="w-full">
			<div class="w-full mx-auto lg:px-8 ">
				<div class=" overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-1 ml-4 flex flex-wrap">
						<NavLink :href="route('orders_enterprises.index')"
							class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
							<ArrowLeft :size="32" color="gray" />
						</NavLink>
						<h1 class="text-2xl font-bold mt-1">Crear Pedido Empresa</h1>
						<p class="dark:text-white w-full mt-4">Registro de pedidos con cálculo de impuestos y totales.</p>
					</div>
				</div>

				<div class="mt-6 max-w-7x5 mx-auto sm:px-6 lg:px-8">
					<form @submit.prevent="submit" class="w-full p-6 rounded-lg shadow-md flex flex-col gap-6">

						<div v-if="Object.keys(form.errors || {}).length" class="bg-red-50 border border-red-200 p-3 rounded">
							<p class="text-red-700 font-medium">Errores de validación:</p>
							<ul class="text-sm text-red-600 list-disc pl-5">
								<li v-for="(msg, key) in form.errors" :key="key">{{ msg }}</li>
							</ul>
						</div>

						<div class="flex gap-6 items-end">
							<div>
								<label class="block text-sm font-medium text-gray-700">Fecha de Pedido</label>
								<input v-model="form.order_date" type="date" required
									class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm" />
								<InputError :message="form.errors.order_date" />
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700">Fecha Esperada</label>
								<input v-model="form.expected_date" type="date" required
									class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm" />
								<InputError :message="form.errors.expected_date" />
							</div>

							<div>
								<label class="block text-sm font-medium text-gray-700">Estado</label>
								<select v-model="form.order_status" class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm">
									<option value="Pending">Pending</option>
									<option value="Received">Received</option>
								</select>
								<InputError :message="form.errors.order_status" />
							</div>
						</div>

						<div class="border rounded p-4">
							<div class="flex items-center justify-between mb-3">
								<h2 class="font-semibold">Productos</h2>
								<button type="button" @click="addProduct" class="inline-flex items-center gap-2 px-3 py-1 bg-green-600 text-white rounded">
									<Plus :size="16" /> Añadir
								</button>
							</div>

							<div class="overflow-x-auto">
								<table class="min-w-full table-auto">
									<thead>
										<tr class="text-left bg-gray-100">
											<th class="px-2">Producto</th>
											<th class="px-2">Cajas Pedidas</th>
											<th class="px-2">Cajas Recibidas</th>
											<th class="px-2">Precio x Caja</th>
											<th class="px-2">% Impuesto</th>
											<th class="px-2">Acción</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(p, idx) in form.products" :key="idx" class="align-top">
											<td class="px-2 py-2">
												<select v-model="p.id" @change="recalcTotals()" class="rounded border-gray-300">
													<option value="" disabled>Selecciona</option>
													<option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.name }}</option>
												</select>
												<InputError :message="form.errors[`products.${idx}.id`]" />
											</td>
											<td class="px-2 py-2">
												<input v-model.number="p.boxes_ordered" type="number" min="0" @input="recalcTotals()" class="w-24 rounded border-gray-300" />
												<InputError :message="form.errors[`products.${idx}.boxes_ordered`]" />
											</td>
											<td class="px-2 py-2">
												<input v-model.number="p.boxes_received" type="number" min="0" @input="recalcTotals()" class="w-24 rounded border-gray-300" />
												<InputError :message="form.errors[`products.${idx}.boxes_received`]" />
											</td>
											<td class="px-2 py-2">
												<input v-model.number="p.purchase_price_per_box" type="number" step="0.01" min="0" @input="recalcTotals()" class="w-28 rounded border-gray-300" />
												<InputError :message="form.errors[`products.${idx}.purchase_price_per_box`]" />
											</td>
											<td class="px-2 py-2">
												<input v-model.number="p.tax_percentage" type="number" step="0.01" min="0" @input="recalcTotals()" class="w-20 rounded border-gray-300" />
												<InputError :message="form.errors[`products.${idx}.tax_percentage`]" />
											</td>
											<td class="px-2 py-2">
												<button type="button" @click="removeProduct(idx)" class="inline-flex items-center gap-2 px-2 py-1 bg-red-600 text-white rounded">
													<Trash :size="14" />
												</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="flex justify-end gap-6">
							<div class="text-right">
								<div class="text-sm text-gray-600">Subtotal Base</div>
								<div class="font-medium">{{ form.subtotal_base }}</div>
							</div>
							<div class="text-right">
								<div class="text-sm text-gray-600">Total Impuestos</div>
								<div class="font-medium">{{ form.total_tax }}</div>
							</div>
							<div class="text-right">
								<div class="text-sm text-gray-600">Gran Total</div>
								<div class="font-medium">{{ form.grand_total }}</div>
							</div>
						</div>

						<div class="flex justify-end">
							<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Crear Pedido</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>

