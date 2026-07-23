<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    seller: Object,
    carts: Array,
});

const form = useForm({
    name: '',
    surname: '',
    direction: '',
    phone: '',
    sex: '',
    status: '',
    identity_card: '',
    picture: null,
    cart_id: '',
});

const Swal = window.Swal;

function onPictureChange(event) {
    form.picture = event.target.files[0] ?? null;
}

function submit() {
    form.post(route('sellers.store'), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El vendedor ha sido creado exitosamente.',
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

    <Head title="Crear Vendedor" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="ml-4 p-1 flex flex-wrap">
                        <NavLink :href="route('sellers.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold mt-1">Crear Vendedor</h1>
                        <p class="dark:text-white w-full mt-4">En este apartado puedes crear un nuevo vendedor.</p>
                    </div>
                </div>

                <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit" class="flex flex-wrap gap-5 justify-center items-start w-full p-6 rounded-lg shadow-lg bg-green-700">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" type="text" name="name" id="name" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input v-model="form.surname" type="text" name="surname" id="surname" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.surname" class="text-red-600 text-sm mt-1">{{ form.errors.surname }}</div>
                        </div>

                        <div>
                            <label for="identity_card" class="block text-sm font-medium text-gray-700">Cédula</label>
                            <input v-model="form.identity_card" type="text" name="identity_card" id="identity_card" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.identity_card" class="text-red-600 text-sm mt-1">{{ form.errors.identity_card }}</div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="form.phone" type="text" name="phone" id="phone"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</div>
                        </div>

                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-700">Sexo</label>
                            <select v-model="form.sex" name="sex" id="sex"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Selecciona una opción</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <div v-if="form.errors.sex" class="text-red-600 text-sm mt-1">{{ form.errors.sex }}</div>
                        </div>

                        <div>
                            <label for="cart_id" class="block text-sm font-medium text-gray-700">Carrito</label>
                            <select v-model="form.cart_id" name="cart_id" id="cart_id" required
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Selecciona un carrito</option>
                                <option v-for="item in carts" :key="item.id" :value="item.id">
                                    {{ item.plate_number }}
                                </option>
                            </select>
                            <div v-if="form.errors.cart_id" class="text-red-600 text-sm mt-1">{{ form.errors.cart_id }}</div>
                        </div>

                        <div>
                            <label for="direction" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <textarea v-model="form.direction" name="direction" id="direction" rows="3"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            <p v-if="form.errors.direction" class="text-red-600 text-sm mt-1">{{ form.errors.direction }}</p>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="form.status" name="status" id="status"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Selecciona un estado</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                            <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status }}</div>
                        </div>

                        <div class=" w-10/12">
                            <label for="picture" class="block text-sm font-medium text-gray-700">Foto</label>
                            <input @change="onPictureChange" type="file" name="picture" id="picture" accept="image/*"
                                class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <div v-if="form.errors.picture" class="text-red-600 text-sm mt-1">{{ form.errors.picture }}</div>
                        </div>

                        <div class="w-full flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none hover:text-white focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                                Crear Vendedor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
