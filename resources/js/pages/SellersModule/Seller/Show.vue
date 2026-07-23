<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    seller: Object,
});

function valueOrDash(value) {
    return value ?? '-';
}
</script>

<template>

    <Head title="Detalle del Vendedor" />

    <AuthenticatedLayout>
        <div class="py-2 w-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="py-4 ml-4 flex items-center gap-4">
                        <NavLink :href="route('sellers.index')"
                            class="-translate-x-3 border-none rounded-md font-semibold tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            <ArrowLeft :size="32" color="gray" />
                        </NavLink>
                        <h1 class="text-2xl font-bold">Detalle del Vendedor</h1>
                    </div>
                </div>

                <div class="mt-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-2 lg:p-3 bg-white rounded-lg shadow-md">
                        <div class="p-4 grid lg:grid-cols-2 sm:grid-cols-2 bg-gray-700 rounded-md">

                            <div class="sm:col-span-1 flex lg:justify-center rounded-lg lg:w-7/14 h-full pb-4"   v-if="seller.picture">
                                <h2 class="text-sm font-medium text-gray-200">Foto</h2>
                                <img :src="seller.picture" alt="Foto del vendedor" draggable="false"
                                    class="mt-2 lg:h-56 h-32 w-32 lg:w-56 translate-y-5 rounded-[100%] object-cover border border-gray-200">
                            </div>
                            <div class="sm:col-span-1 lg:w-7/12 h-96 gap-4 flex flex-col">
                                <div class="mt-1 w-8/14 col-span-1 rounded-lg ">
                                    <h2 class="text-sm font-medium text-gray-400">Nombre</h2>
                                    <p class="mt-1 text-gray-200">{{ valueOrDash(seller.name) }} {{ valueOrDash(seller.surname) }}</p>
                                </div>

                                <div class="sm:col-span-1 rounded-lg lg:w-7/14">
                                    <h2 class="text-sm font-medium text-gray-400">Cédula</h2>
                                    <p class="mt-1 text-gray-200">{{ valueOrDash(seller.identity_card) }}</p>
                                </div>
                                <div>
                                    <h2 class="text-sm font-medium text-gray-400">Teléfono</h2>
                                    <p class="mt-1 text-gray-200">{{ valueOrDash(seller.phone) }}</p>
                                </div>

                                <div>
                                    <h2 class="text-sm font-medium text-gray-400">Sexo</h2>
                                    <p class="mt-1 text-gray-200">{{ valueOrDash(seller.sex) }}</p>
                                </div>

                                <div>
                                    <h2 class="text-sm font-medium text-gray-400">Carrito</h2>
                                    <p class="mt-1 text-gray-200">{{ seller.cart?.plate_number ?? '-' }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <h2 class="text-sm font-medium text-gray-400">Dirección</h2>
                                    <p class="mt-1 text-gray-200">{{ valueOrDash(seller.direction) }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
