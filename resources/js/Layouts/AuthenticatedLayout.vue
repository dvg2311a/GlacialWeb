<script setup>
import { ChartColumnStacked, Star, Fan, Snowflake, PencilRuler, BetweenVerticalStart, Apple, Info, PackageSearch, Group, ThermometerSnowflake, UserRoundCog, IceCreamCone, ListTodo, BaggageClaim, ShoppingCart, LogOut, SunMoon, NotebookText, NotepadText, UsersRound } from 'lucide-vue-next';
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { toggleTheme } from '@/theme';

defineProps({
    title: String,
    permissions: Array,
    fullWidth: Boolean,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="px-4 py-4 lg:hidden">
            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>


        <div class="relative z-[1000]">
            <nav
                class="absolute left-0 top-0 z-50 w-52 rounded-b bg-white transition-all duration-200 ease-in-out dark:border-gray-700 dark:bg-gray-800">
                <!-- Responsive Navigation Menu -->
                <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <div class="flex">

                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                <ChartColumnStacked class="mr-2 w-4 h-4 inline" />
                                <span>Dashboard</span>
                            </ResponsiveNavLink>
                            <button type="button" @click="toggleTheme" class="block px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-200 transition
                            duration-300 ease-in-out focus:outline-none hover:rotate-180 active:rotate-180">
                                <SunMoon class="w-4 h-4" />
                            </button>
                        </div>

                        <ResponsiveNavLink :href="route('seller_daily_reports.index')"
                            :active="route().current('seller_daily_reports.index')" class="text-xs">
                            <NotebookText class="mr-2 w-4 h-4 inline" />
                            <span>Ventas Diarias</span>
                        </ResponsiveNavLink>

                        <ResponsiveNavLink :href="route('private_sales.index')"
                            :active="route().current('private_sales.index')" class="text-xs">
                            <NotepadText class="mr-2 w-4 h-4 inline" />
                            <span>Ventas Particulares</span>
                        </ResponsiveNavLink>

                        <ResponsiveNavLink :href="route('orders_enterprises.index')"
                            :active="route().current('orders_enterprises.index')" class="text-xs">
                            <ListTodo class="mr-2 w-4 h-4 inline" />
                            <span>Pedidos</span>
                        </ResponsiveNavLink>

                        <div class="border-t border-gray-200 my-2"></div>


                        <template v-if="$page.props.auth.roles.includes('Administrador')">
                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <UserRoundCog class="mr-2 w-4 h-4" />Administración
                                        </span>
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="flex flex-col gap-1 pl-3 translate-x-10">
                                        <ResponsiveNavLink :href="route('roles.index')"
                                            :active="route().current('roles.index')">
                                            <Star class="mr-2 w-4 h-4 inline" />
                                            <span>Roles</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('sellers.index')"
                                            :active="route().current('sellers.index')">
                                            <UsersRound class="mr-2 w-4 h-4 inline" />
                                            <span>Vendedores</span>
                                        </ResponsiveNavLink>
                                    </div>
                                </template>
                            </Dropdown>

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <ThermometerSnowflake class="mr-2 w-4 h-4" />Freezers
                                        </span>
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="flex flex-col gap-1 pl-3">
                                        <ResponsiveNavLink :href="route('type_freezers.index')"
                                            :active="route().current('type_freezers.index')">
                                            <Fan class="mr-2 w-4 h-4 inline" />
                                            <span>Tipos de Freezer</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('freezers.index')"
                                            :active="route().current('freezers.index')">
                                            <Snowflake class="mr-2 w-4 h-4 inline" />
                                            <span>Freezers</span>
                                        </ResponsiveNavLink>
                                    </div>
                                </template>
                            </Dropdown>

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <Group class="mr-2 w-4 h-4" />Placas
                                        </span>
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="flex flex-col gap-1 pl-3">
                                        <ResponsiveNavLink :href="route('plate_dimensions.index')"
                                            :active="route().current('plate_dimensions.index')">
                                            <PencilRuler class="mr-2 w-4 h-4 inline" />
                                            <span>Dimensiones de Placa</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('plates.index')"
                                            :active="route().current('plates.index')">
                                            <BetweenVerticalStart class="mr-2 w-4 h-4 inline" />
                                            <span>Placas</span>
                                        </ResponsiveNavLink>
                                    </div>
                                </template>
                            </Dropdown>

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <PackageSearch class="mr-2 w-4 h-4" />Productos
                                        </span>
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="flex flex-col gap-1 pl-3">
                                        <ResponsiveNavLink :href="route('type_products.index')"
                                            :active="route().current('type_products.index')">
                                            <ChartColumnStacked class="mr-2 w-4 h-4 inline" />
                                            <span>Tipos de Producto</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('flavor_products.index')"
                                            :active="route().current('flavor_products.index')">
                                            <Apple class="mr-2 w-4 h-4 inline" />
                                            <span>Sabor de Producto</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('status_products.index')"
                                            :active="route().current('status_products.index')">
                                            <Info class="mr-2 w-4 h-4 inline" />
                                            <span>Estado de Producto</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('products.index')"
                                            :active="route().current('products.index')">
                                            <IceCreamCone class="mr-2 w-4 h-4 inline" />
                                            <span>Productos</span>
                                        </ResponsiveNavLink>
                                    </div>
                                </template>
                            </Dropdown>

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <ShoppingCart class="mr-2 w-4 h-4" />Carritos
                                        </span>
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="flex flex-col gap-1 pl-3">
                                        <ResponsiveNavLink :href="route('type_carts.index')"
                                            :active="route().current('type_carts.index')">
                                            <BaggageClaim class="mr-2 w-4 h-4 inline" />
                                            <span>Tipos de Carrito</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('status_carts.index')"
                                            :active="route().current('status_carts.index')">
                                            <Info class="mr-2 w-4 h-4 inline" />
                                            <span>Estado de Carrito</span>
                                        </ResponsiveNavLink>
                                        <ResponsiveNavLink :href="route('carts.index')"
                                            :active="route().current('carts.index')">
                                            <ShoppingCart class="mr-2 w-4 h-4 inline" />
                                            <span>Carritos</span>
                                        </ResponsiveNavLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </template>


                        <div class="border-t border-gray-200 my-2"></div>

                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Perfil
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Cerrar sesión
                                    </DropdownLink>
                                </template>

                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md border border-transparent dark:bg-transparent px-3 py-2 text-sm font-medium leading-4 text-gray-500 dark:text-gray-200 transition duration-150 ease-in-out hover:text-gray-700 dark:hover:text-white focus:outline-none">
                                            {{ $page.props.auth.user.name }}

                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Page Content -->
        <div :class="fullWidth ? 'w-full px-4 py-6 sm:px-6 lg:px-8' : 'mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'">
            <div class="flex gap-6 ">

                <Sidebar />

                <!-- Main content -->
                <main class="flex-1 lg:ml-60">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
