<script setup>
import { Head } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import NavLink from '@/Components/NavLink.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


defineProps({
    product_show: Object,
    freezer: Array,
    type_product: Array,
    flavor: Array,
    status_product: Array,
})
</script>

<template>

    <Head title="Mostrar Producto" />
    <AuthenticatedLayout>
        <div class="w-full">
            <div class="w-full mx-auto lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 ml-4 flex items-center gap-4">
                        <NavLink :href="route('products.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold">Detalle del Producto</h1>
                    </div>
                </div>

                <div class="mt-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Nombre</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.name }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <h2 class="text-sm font-medium text-gray-500">Descripción</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.description }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Forma</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.shape }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Precio Mayoreo</h2>
                                <p class="mt-1 text-gray-800"> C$ {{ product_show.wholesale_price }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Precio Minorista</h2>
                                <p class="mt-1 text-gray-800"> C$ {{ product_show.retail_price }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Precio de Compra</h2>
                                <p class="mt-1 text-gray-800"> C$ {{ product_show.purchase_price }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Precio Malo</h2>
                                <p class="mt-1 text-gray-800"> C$ {{ product_show.bad_price }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Freezer</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.freezer?.number_freezer }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Tipo de Producto</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.type_product?.name }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Sabor de Producto</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.flavor?.name }}</p>
                            </div>
                            <div>
                                <h2 class="text-sm font-medium text-gray-500">Estado de Producto</h2>
                                <p class="mt-1 text-gray-800">{{ product_show.status_product?.status }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
