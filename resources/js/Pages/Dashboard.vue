<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">


                        </div>
                    </div>
                </div>
        </div>
    </AuthenticatedLayout>
</template>
