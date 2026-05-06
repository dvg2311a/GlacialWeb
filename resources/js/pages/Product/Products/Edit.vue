<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import NavLink from '@/Components/NavLink.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const props = defineProps({
    // Accept either `product` or `products` because backend is inconsistent
    product: Object,
    products: Object,
    type_products: Array,
    freezers: Array,
    status_products: Array,
    flavor_products: Array,
})

const product = props.product ?? props.products ?? {}
const type_products = props.type_products ?? []
const freezers = props.freezers ?? []
const status_products = props.status_products ?? []
const flavor_products = props.flavor_products ?? []

const form = useForm({
    name: product.name || '',
    description: product.description || '',
    shape: product.shape || '',
    wholesale_price: product.wholesale_price || '',
    retail_price: product.retail_price || '',
    purchase_price: product.purchase_price || '',
    bad_price: product.bad_price || '',
    expiration_date: product.expiration_date || '',
    freezer_id: product.freezer_id || '',
    type_product_id: product.type_product_id || '',
    status_product_id: product.status_product_id || '',
    flavor_id: product.flavor_id || '',
})

const Swal = window.Swal

function submit() {
    form.put(route('products.update', product.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El producto ha sido actualizado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 2000,
                timerProgressBar: true,
            })
        }
    })
}
</script>

<template>

    <Head title="Editar Producto" />
    <AuthenticatedLayout>
        <div class="w-full">
            <div class="w-full mx-auto lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 ml-4 flex flex-wrap">
                        <NavLink :href="route('products.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Producto</h1>
                        <p class="dark:text-white w-full mt-4">En este apartado puedes actualizar la información del
                            producto.</p>
                    </div>

                    <div class="mt-6 max-w-7x5 mx-auto sm:px-6 lg:px-8">
                        <form @submit.prevent="submit"
                            class="flex flex-wrap gap-5 justify-start items-center w-full p-6 rounded-lg shadow-md">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre del
                                    Producto</label>
                                <input v-model="form.name" type="text" name="name" id="name" required
                                    placeholder="Ej. Chocolate"
                                    class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label for="shape" class="block text-sm font-medium text-gray-700">Forma</label>
                                <input v-model="form.shape" type="text" name="shape" id="shape" required
                                    placeholder="Ej. Triangular..."
                                    class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.shape" class="text-red-600 text-sm mt-1">{{ form.errors.shape }}
                                </div>
                            </div>

                            <div class="w-11/12 flex flex-col items-start">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Descripción</label>
                                <input v-model="form.description" type="text" name="description" id="description"
                                    required
                                    class="w-10/12 mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{
                                    form.errors.description }}</div>
                            </div>

                            <div>
                                <label for="wholesale_price" class="block text-sm font-medium text-gray-700">Precio de
                                    Mayoreo</label>
                                <input v-model="form.wholesale_price" type="number" name="wholesale_price"
                                    id="wholesale_price" required
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.wholesale_price" class="text-red-600 text-sm mt-1">{{
                                    form.errors.wholesale_price }}</div>
                            </div>

                            <div>
                                <label for="retail_price" class="block text-sm font-medium text-gray-700">Precio de
                                    Detalle</label>
                                <input v-model="form.retail_price" type="number" name="retail_price" id="retail_price"
                                    required
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.retail_price" class="text-red-600 text-sm mt-1">{{
                                    form.errors.retail_price }}</div>
                            </div>

                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Precio de
                                    Compra</label>
                                <input v-model="form.purchase_price" type="number" name="purchase_price"
                                    id="purchase_price" required
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.purchase_price" class="text-red-600 text-sm mt-1">{{
                                    form.errors.purchase_price }}</div>
                            </div>

                            <div>
                                <label for="bad_price" class="block text-sm font-medium text-gray-700">Precio
                                    Malo</label>
                                <input v-model="form.bad_price" type="number" name="bad_price" id="bad_price" required
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.bad_price" class="text-red-600 text-sm mt-1">{{
                                    form.errors.bad_price }}</div>
                            </div>

                            <div>
                                <label for="expiration_date" class="block text-sm font-medium text-gray-700">Fecha de
                                    Vencimiento</label>
                                <input v-model="form.expiration_date" type="date" name="expiration_date"
                                    id="expiration_date" required
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <div v-if="form.errors.expiration_date" class="text-red-600 text-sm mt-1">{{
                                    form.errors.expiration_date }}</div>
                            </div>

                            <div>
                                <label for="freezer_id" class="block text-sm font-medium text-gray-700">Freezer</label>
                                <select v-model="form.freezer_id" name="freezer_id" id="freezer_id" rows="3"
                                    class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" disabled>Selecciona un freezer</option>
                                    <option v-for="freezer in freezers" :key="freezer.id" :value="freezer.id">
                                        {{ freezer.number_freezer }}
                                    </option>
                                </select>
                                <p v-if="form.errors.freezer_id" class="text-red-600 text-sm mt-1">{{
                                    form.errors.freezer_id }}</p>
                            </div>

                            <div>
                                <label for="type_product_id" class="block text-sm font-medium text-gray-700">Tipo de
                                    Producto</label>
                                <select v-model="form.type_product_id" name="type_product_id" id="type_product_id"
                                    required
                                    class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" selected>Selecciona un tipo de producto</option>
                                    <option v-for="tp in type_products" :key="tp.id" :value="tp.id">
                                        {{ tp.name }}
                                    </option>
                                </select>
                                <div v-if="form.type_product_id" class="text-red-600 text-sm mt-1">{{
                                    form.errors.type_product_id }}</div>
                            </div>

                            <div>
                                <label for="flavor_id" class="block text-sm font-medium text-gray-700">Sabor del
                                    Producto</label>
                                <select v-model="form.flavor_id" name="flavor_id" id="flavor_id" required
                                    class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" selected>Selecciona un sabor del producto</option>
                                    <option v-for="fp in flavor_products" :key="fp.id" :value="fp.id">
                                        {{ fp.name }}
                                    </option>
                                </select>
                                <div v-if="form.flavor_id" class="text-red-600 text-sm mt-1">{{ form.errors.flavor_id }}
                                </div>
                            </div>

                            <div>
                                <label for="status_product_id" class="block text-sm font-medium text-gray-700">Estado
                                    del
                                    Producto</label>
                                <select v-model="form.status_product_id" name="status_product_id" id="status_product_id"
                                    required
                                    class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" selected>Selecciona un estado del producto</option>
                                    <option v-for="sp in status_products" :key="sp.id" :value="sp.id">
                                        {{ sp.status }}
                                    </option>
                                </select>
                                <div v-if="form.status_product_id" class="text-red-600 text-sm mt-1">{{
                                    form.errors.status_product_id }}</div>
                            </div>

                            <div class="w-full flex flex-col items-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
