<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="ha-error">
            {{ message }}
        </p>
    </div>
</template>
