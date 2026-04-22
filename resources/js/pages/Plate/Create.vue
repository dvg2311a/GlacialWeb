<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineProps({
    plate: Array,
    plate_dimensions: Array,
    freezers: Array,
});
const form = useForm({
    code_plate: '',
    plate_dimension_id: '',
    freezer_id: '',
});

const Swal = window.Swal;

function submit() {
    form.post(route('plates.store'), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'La placa ha sido creada exitosamente.',
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

    <Head title="Crear Placa" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-wrap">
                        <NavLink :href="route('plates.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Crear Placa</h1>
                        <p class="text-gray-600 w-full mt-4">En este apartado puedes crear un nueva placa.</p>
                    </div>
                </div>

                <!-- Formulario para crear un nuevo tipo de freezer -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6 flex justify-center flex-col items-center">
                        <div>
                            <label for="code_plate" class="block text-sm font-medium text-gray-700">Código de
                                Placa</label>
                            <input v-model="form.code_plate" type="text" name="code_plate" id="code_plate" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.code_plate" class="text-red-600 text-sm mt-1">{{
                                form.errors.code_plate }}</div>
                        </div>

                        <div>
                            <label for="plate_dimension_id" class="block text-sm font-medium text-gray-700">Categoría de
                                Dimensión</label>
                            <select v-model="form.plate_dimension_id" name="plate_dimension_id" id="plate_dimension_id" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" selected>Selecciona una categoría de dimensión</option>
                                <option v-for="pt in plate_dimensions" :key="pt.id" :value="pt.id">
                                    {{ pt.shape }}
                                </option>
                            </select>
                            <div v-if="form.plate_dimension_id" class="text-red-600 text-sm mt-1">{{
                                form.errors.plate_dimension_id }}</div>

                        </div>
                        <div>
                            <label for="freezer_id" class="block text-sm font-medium text-gray-700">Freezer</label>
                            <select v-model="form.freezer_id" name="freezer_id" id="freezer_id" rows="3"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona un freezer</option>
                                <option v-for="freezer in freezers" :key="freezer.id" :value="freezer.id">
                                    {{ freezer.number_freezer }}
                                </option>
                            </select>
                            <p v-if="form.errors.freezer_id" class="text-red-600 text-sm mt-1">{{ form.errors.freezer_id }}
                            </p>
                        </div>
                        <div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Crear Placa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
