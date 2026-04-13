<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
    plate_dimension: Array,
});

const form = useForm({
    height: props.plate_dimension?.height ?? '',
    width: props.plate_dimension?.width ?? '',
    unit_measure: props.plate_dimension?.unit_measure ?? '',
    shape: props.plate_dimension?.shape ?? '',
});



const Swal = window.Swal;

function submit() {
    form.put(route('plate_dimensions.update', props.plate_dimension.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'La dimensión de la placa ha sido actualizada exitosamente.',
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

    <Head title="Editar Dimensión de Placa" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-wrap">
                        <NavLink :href="route('plate_dimensions.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Dimensión de Placa</h1>
                        <p class="text-gray-600 w-full mt-4">En este apartado puedes editar esta dimensión de placa.</p>
                    </div>
                </div>

                <!-- Formulario para editar el tipo de freezer -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6 flex justify-center flex-col items-center">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700">Alto</label>
                            <input v-model="form.height" name="height" id="height" type="decimal" required class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.height" class="text-red-600 text-sm mt-1">{{
                                form.errors.height }}</div>
                        </div>

                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700">Ancho</label>
                            <input v-model="form.width" name="width" id="width" type="decimal" required class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.width" class="text-red-600 text-sm mt-1">{{
                                form.errors.width }}</div>
                        </div>

                        <div>
                            <label for="unit_measure" class="block text-sm font-medium text-gray-700">Unidad de
                                Medida</label>
                            <select v-model="form.unit_measure" name="unit_measure" id="unit_measure" required class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring
                                focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona una unidad de medida</option>
                                <option value="CM">Centímetros</option>
                                <option value="INCH">Pulgadas</option>
                            </select>
                            <p v-if="form.errors.unit_measure" class="text-red-600 text-sm mt-1">{{
                                form.errors.unit_measure }}</p>
                        </div>

                        <div>
                            <label for="shape" class="block text-sm font-medium text-gray-700">Forma</label>
                            <select v-model="form.shape" name="shape" id="shape" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona una forma</option>
                                <option value="Rectangular">Rectangular</option>
                                <option value="Triangular">Triangular</option>
                            </select>
                            <div v-if="form.errors.shape" class="text-red-600 text-sm mt-1">{{
                                form.errors.shape }}</div>
                        </div>
                        <div v-if="form.errors.number_freezer" class="text-red-600 text-sm mt-1">{{
                            form.errors.number_freezer }}</div>
                        <div>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Actualizar Freezer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
