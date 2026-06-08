<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center dark:bg-gray-900 pt-6 sm:justify-center sm:pt-0"
    >
        <div>

                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />

        </div>

        <div
            class="w-80 mt-32 sm:w-full sm:mt-6 p-6 overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
