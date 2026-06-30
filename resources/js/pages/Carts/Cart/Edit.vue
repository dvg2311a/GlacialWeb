<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
    cart: Object,
    type_cart: Array,
    status_cart: Array,
});

const form = useForm({
    plate_number: props.cart?.plate_number ?? '',
    description: props.cart?.description ?? '',
    proprietary_type: props.cart?.proprietary_type ?? '',
    type_cart_id: props.cart?.type_cart_id ?? '',
    status_cart_id: props.cart?.status_cart_id ?? '',
});



const Swal = window.Swal;

function submit(){
    form.put(route('carts.update', props.cart.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El carrito ha sido actualizado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
            });
        }
    });
}
</script>

<template>

    <Head title="Editar Carrito" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="ml-4 p-1  flex flex-wrap">
                        <NavLink :href="route('carts.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Carrito</h1>
                        <p class="dark:text-white w-full mt-4">En este apartado puedes editar este carrito.</p>
                    </div>
                </div>

                <!-- Formulario para editar el tipo de carrito -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit"  class="space-y-6 flex lg:flex justify-center flex-wrap lg:items-end lg:justify-normal gap-4">
                        <div>
                            <label for="plate_number" class="block text-sm font-medium text-gray-700">Número de
                                Placa</label>
                            <input v-model="form.plate_number" name="plate_number" id="plate_number" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.plate_number" class="text-red-600 text-sm mt-1">{{
                                form.errors.plate_number }}</div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input v-model="form.description" name="description" id="description" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{
                                form.errors.description }}</div>
                        </div>

                        <div class="">
                            <label for="proprietary_type" class="block text-sm font-medium text-gray-700">Tipo de
                                Propietario</label>
                            <select v-model="form.proprietary_type" name="proprietary_type" id="proprietary_type"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="Company">Compañía</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>

                        <div>
                            <label for="status_cart_id" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="form.status_cart_id" name="status_cart_id" id="status_cart_id" rows="3"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring
                                focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona un estado</option>
                                <option v-for="status in status_cart" :key="status.id" :value="status.id">
                                    {{ status.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.status_cart_id" class="text-red-600 text-sm mt-1">{{ form.errors.status_cart_id }}</p>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Carrito</label>
                            <select v-model="form.type_cart_id" name="type_cart_id" id="type_cart_id" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring
                                focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona un tipo de carrito</option>
                                <option v-for="type in props.type_cart" :key="type.id" :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.type_cart_id" class="text-red-600 text-sm mt-1">{{ form.errors.type_cart_id }}</p>
                        </div>

                        <div>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Actualizar Carrito
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
