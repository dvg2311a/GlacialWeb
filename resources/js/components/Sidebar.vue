<script setup>
import { ChartColumnStacked, Star, Fan, Snowflake, PencilRuler, BetweenVerticalStart, Apple, Info, PackageSearch, Group, ThermometerSnowflake, UserRoundCog, SunMoon, IceCreamCone, ListTodo, BaggageClaim, ShoppingCart } from 'lucide-vue-next';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { toggleTheme } from '@/theme';
</script>

<template>
    <aside class=" hidden -ml-72 sm:ml-0 lg:block w-70 fixed top-0 left-0 z-20 h-full">
        <div
            class="relative flex h-[calc(100vh-0rem)] w-full max-w-[16rem] flex-col rounded-b bg-clip-border p-4 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:shadow-none shadow-xl shadow-blue-gray-900/5">
            <div class="p-4 mb-2 flex items-center gap-4 rounded-lg">
                <h5
                    class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900 dark:text-white">
                    GlacialWeb</h5>

                <button type="button" @click="toggleTheme" class="block px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-200 transition
                    duration-300 ease-in-out focus:outline-none hover:rotate-180 active:rotate-180">
                    <SunMoon />
                </button>
            </div>
            <nav
                class="flex min-w-[240px] flex-col gap-1 p-2 font-sans text-base font-normal text-blue-gray-700 dark:text-gray-200">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                    class="flex items-center p-3 dark:text-white">
                    <ChartColumnStacked class="mr-3 w-5 h-5" />
                    <span>Inicio</span>
                </NavLink>

                <div v-if="$page.props.auth.roles.includes('Administrador')" class="mt-2 flex flex-col gap-1">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center rounded-md border border-transparent px-1 py-2 text-sm font-medium leading-4
                                text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-white dark:bg-transparent">

                                    <UserRoundCog class="mr-3 w-5 h-5" />

                                    Administración

                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="flex flex-col gap-4">
                                <NavLink :href="route('roles.index')" :active="route().current('roles.index')"
                                    class="flex items-center p-3 ml-3">
                                    <Star class="mr-3 w-5 h-5" />
                                    <span>Roles</span>
                                </NavLink>
                            </div>
                        </template>
                    </Dropdown>


                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center rounded-md border border-transparent px-1 py-2 text-sm font-medium leading-4
                                    text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-white dark:bg-transparent">

                                    <ThermometerSnowflake class="mr-3 w-5 h-5" />

                                    Freezers

                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="flex flex-col gap-4">
                                <NavLink :href="route('type_freezers.index')"
                                    :active="route().current('type_freezers.index')" class="flex items-center p-3 ml-3">
                                    <Fan class="mr-1" />
                                    <span>Tipos de Freezer</span>
                                </NavLink>

                                <NavLink :href="route('freezers.index')" :active="route().current('freezers.index')"
                                    class="flex items-center p-3 ml-3">
                                    <Snowflake class="mr-1" />
                                    <span>Freezers</span>
                                </NavLink>
                            </div>
                        </template>
                    </Dropdown>


                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center rounded-md border border-transparent  px-1 py-2 text-sm font-medium leading-4
                                    text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-white dark:bg-transparent">

                                    <Group class="mr-3 w-5 h-5" />

                                    Placas

                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="flex flex-col gap-4">
                                <NavLink :href="route('plate_dimensions.index')"
                                    :active="route().current('plate_dimensions.index')"
                                    class="flex items-center p-3 ml-2">
                                    <PencilRuler class="mr-1" />
                                    <span>Dimensiones de Placa</span>
                                </NavLink>

                                <NavLink :href="route('plates.index')" :active="route().current('plates.index')"
                                    class="flex items-center p-3 ml-2">
                                    <BetweenVerticalStart class="mr-1" />
                                    <span>Placas</span>
                                </NavLink>
                            </div>
                        </template>
                    </Dropdown>


                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center rounded-md border border-transparent px-1 py-2 text-sm font-medium leading-4
                                    text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-white dark:bg-transparent">

                                    <PackageSearch class="mr-3 w-5 h-5" />

                                    Productos

                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>

                            <div class="flex flex-col gap-4">

                                <NavLink :href="route('type_products.index')"
                                    :active="route().current('type_products.index')" class="flex items-center p-3 ml-2">
                                    <ChartColumnStacked class="mr-1" />
                                    <span>Tipos de Producto</span>
                                </NavLink>

                                <NavLink :href="route('flavor_products.index')"
                                    :active="route().current('flavor_products.index')"
                                    class="flex items-center p-3 ml-2">
                                    <Apple class="mr-1" />
                                    <span>Sabor de Producto</span>
                                </NavLink>

                                <NavLink :href="route('status_products.index')"
                                    :active="route().current('status_products.index')"
                                    class="flex items-center p-3 ml-2">
                                    <Info class="mr-1" />
                                    <span>Estado de Producto</span>
                                </NavLink>

                                <NavLink :href="route('products.index')"
                                    :active="route().current('products.index')"
                                    class="flex items-center p-3 ml-2">
                                    <IceCreamCone class="mr-1" />
                                    <span>Productos</span>
                                </NavLink>

                            </div>
                        </template>
                    </Dropdown>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center rounded-md border border-transparent px-1 py-2 text-sm font-medium leading-4
                                    text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:text-white dark:bg-transparent">

                                    <ShoppingCart class="mr-3 w-5 h-5" />

                                    Carritos

                                    <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <div class="flex flex-col gap-4">
                                <NavLink :href="route('type_carts.index')" :active="route().current('type_carts.index')"
                                    class="flex items-center p-3 ml-3">
                                    <BaggageClaim class="mr-3 w-5 h-5" />
                                    <span>Tipos de Carrito</span>
                                </NavLink>
                                <NavLink :href="route('status_carts.index')" :active="route().current('status_carts.index')"
                                    class="flex items-center p-3 ml-3">
                                    <Info class="mr-3 w-5 h-5" />
                                    <span>Estado de Carrito</span>
                                </NavLink>
                                <NavLink :href="route('carts.index')" :active="route().current('carts.index')"
                                    class="flex items-center p-3 ml-3">
                                    <Info class="mr-3 w-5 h-5" />
                                    <span>Carritos</span>
                                </NavLink>
                            </div>
                        </template>
                        </Dropdown>

                    <NavLink :href="route('orders_enterprises.index')"
                        :active="route().current('orders_enterprises.index')"
                        class="flex items-center p-3">
                        <ListTodo class="mr-1" />
                        <span>Pedidos</span>
                    </NavLink>

                    <div class="border-t border-blue-gray-100 my-2"></div>

                </div>
                <div class=" sm:ms-6 sm:flex sm:items-center">
                    <!-- Settings Dropdown -->
                    <div class="relative ms-3">
                        <Dropdown align="right" width="48">

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
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
            </nav>
        </div>
    </aside>
</template>
