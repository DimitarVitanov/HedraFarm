<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="ha-btn ha-btn--ghost"
    >
        <slot />
    </button>
</template>
