<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


defineProps({
    roles: Array,
});
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Roles
            </h2>
        </template>

        <div class="py-12 ps-6 pe-6">

            <Link :href="route('roles.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Role
            </Link>
            <table class="min-w-full divide-y divide-gray-200 mt-5">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permisos
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" v-if="roles && roles.length">
                    <tr v-for="role in roles" :key="role.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ role.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <ul class="list-disc pl-5">
                                <li v-for="permission in role.permissions" :key="permission.id">
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td class="px-6 py-4" colspan="2">No hay roles disponibles.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
