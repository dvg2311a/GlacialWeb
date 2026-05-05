<script setup>
import { ref } from 'vue';

import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Sidebar from '@/Components/Sidebar.vue';

defineProps({
    title: String,
    permissions: Array,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">

                <!-- Responsive Navigation Menu -->
                <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <template v-if="$page.props.auth.roles.includes('Administrador')">
                            <ResponsiveNavLink :href="route('roles.index')" :active="route().current('roles.index')">
                                Roles
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('type_freezers.index')" :active="route().current('type_freezers.index')">
                                Tipos de Freezer
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('freezers.index')" :active="route().current('freezers.index')">
                                Freezers
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('plate_dimensions.index')" :active="route().current('plate_dimensions.index')">
                                Dimensiones de Placa
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('plates.index')" :active="route().current('plates.index')">
                                Placas
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('type_products.index')" :active="route().current('type_products.index')">
                                Tipos de Producto
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('flavor_products.index')" :active="route().current('flavor_products.index')">
                                Sabor de Producto
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('status_products.index')" :active="route().current('status_products.index')">
                                Estado de Producto
                            </ResponsiveNavLink>
                        </template>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex gap-6 ">

                    <Sidebar />

                    <!-- Main content -->
                    <main class="flex-1 ml-80">
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
