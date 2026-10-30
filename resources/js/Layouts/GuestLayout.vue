<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="ha-guest">
        <Link href="/" class="ha-guest__logo">
            <img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="210" height="52" />
        </Link>

        <div class="ha-guest__card">
            <slot />
        </div>
    </div>
</template>
