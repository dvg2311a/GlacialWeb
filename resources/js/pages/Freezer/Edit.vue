<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
    freezer: Array,
    type: Array
});

const form = useForm({
    number_freezer: props.freezer?.number_freezer ?? '',
    status: props.freezer?.status ?? '',
    type_freezer_id: props.freezer.type_freezer_id ?? '',
});



const Swal = window.Swal;

function submit(){
    form.put(route('freezers.update', props.freezer.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El freezer ha sido actualizado exitosamente.',
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

    <Head title="Editar Tipo de Freezer" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-wrap">
                        <NavLink :href="route('freezers.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Editar Freezer</h1>
                        <p class="text-gray-600 w-full mt-4">En este apartado puedes editar este freezer.</p>
                    </div>
                </div>

                <!-- Formulario para editar el tipo de freezer -->
                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="space-y-6 flex justify-center flex-col items-center">
                        <div>
                            <label for="number_freezer" class="block text-sm font-medium text-gray-700">Número de
                                Freezer</label>
                            <input v-model="form.number_freezer" name="number_freezer" id="number_freezer" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.number_freezer" class="text-red-600 text-sm mt-1">{{
                                form.errors.number_freezer }}</div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="form.status" name="status" id="status" rows="3"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring
                                focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona un estado</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="dañado">Inactivo dañado</option>
                                <option value="bueno">Inactivo en buen estado</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                            <p v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status }}</p>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Freezer</label>
                            <select v-model="form.type_freezer_id" name="type_freezer_id" id="type_freezer_id" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring
                                focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" disabled>Selecciona un tipo de freezer</option>
                                <option v-for="type in props.type" :key="type.id" :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.type_freezer_id" class="text-red-600 text-sm mt-1">{{ form.errors.type_freezer_id }}</p>
                        </div>
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
