<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
    status_product: Array,
});

const form = useForm({
    status: props.status_product?.status ?? '',
    description: props.status_product?.description ?? '',
});



const Swal = window.Swal;

function submit() {
    form.put(route('status_products.update', props.status_product.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El estado de producto ha sido actualizado exitosamente.',
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

    <Head title="Editar Estado de Producto" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 ml-4 flex flex-wrap">
                        <NavLink :href="route('status_products.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Estado de Producto</h1>
                        <p class="dark:text-white w-full mt-4">En este apartado puedes editar este estado de producto.</p>
                    </div>
                </div>

                <!-- Formulario para editar el estado de producto -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6 flex justify-center flex-col items-center">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <input v-model="form.status" name="status" id="status" type="text" required class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{
                                form.errors.status }}</div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input v-model="form.description" name="description" id="description" type="text" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{
                                form.errors.description }}</div>
                        </div>
                        <div v-if="form.errors.height" class="text-red-600 text-sm mt-1">{{
                            form.errors.height }}</div>
                        <div>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Actualizar Estado de Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
