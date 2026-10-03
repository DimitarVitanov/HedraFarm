<script setup>
import Icon from '@/Components/V2/Icon.vue';

defineProps({
    title: { type: String, default: '' },
    // [{ label, href }] - intermediate breadcrumb links between "Дома" and the title
    trail: { type: Array, default: () => [] },
    crumb: { type: String, default: '' },
});
</script>

<template>
    <section class="hf-pagehero">
        <div class="hf-container">
            <nav class="hf-crumbs" aria-label="Breadcrumb">
                <a href="/">Дома</a>
                <template v-for="item in trail" :key="item.href">
                    <Icon name="chevron-right" />
                    <a :href="item.href">{{ item.label }}</a>
                </template>
                <Icon name="chevron-right" />
                <span>{{ crumb || title }}</span>
            </nav>
            <h1 v-if="title">{{ title }}</h1>
            <slot />
        </div>
    </section>
</template>
