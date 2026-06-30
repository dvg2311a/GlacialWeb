<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
	cart: Object,
});

function valueOrDash(value) {
	return value ?? '-';
}
</script>

<template>
	<Head title="Detalle del Carrito" />

	<AuthenticatedLayout>
		<div class="py-2 w-full">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-4 ml-4 flex items-center gap-4">
						<NavLink :href="route('carts.index')"
							class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
							<ArrowLeft :size="32" color="gray" />
						</NavLink>
						<h1 class="text-2xl font-bold">Detalle del Carrito</h1>
					</div>
				</div>

				<div class="mt-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
					<div class="p-6 bg-white rounded-lg shadow-md">
						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<div>
								<h2 class="text-sm font-medium text-gray-500">Número de placa</h2>
								<p class="mt-1 text-gray-800">{{ valueOrDash(cart.plate_number) }}</p>
							</div>

							<div>
								<h2 class="text-sm font-medium text-gray-500">Tipo de propietario</h2>
								<p class="mt-1 text-gray-800">{{ valueOrDash(cart.proprietary_type) }}</p>
							</div>

							<div class="sm:col-span-2">
								<h2 class="text-sm font-medium text-gray-500">Descripción</h2>
								<p class="mt-1 text-gray-800">{{ valueOrDash(cart.description) }}</p>
							</div>

							<div>
								<h2 class="text-sm font-medium text-gray-500">Tipo de carrito</h2>
								<p class="mt-1 text-gray-800">{{ cart.type_cart?.name ?? '-' }}</p>
							</div>

							<div>
								<h2 class="text-sm font-medium text-gray-500">Estado del carrito</h2>
								<p class="mt-1 text-gray-800">{{ cart.status_cart?.name ?? '-' }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
