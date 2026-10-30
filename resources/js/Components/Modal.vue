<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'ha-modal--sm',
        md: 'ha-modal--md',
        lg: 'ha-modal--lg',
        xl: 'ha-modal--xl',
        '2xl': 'ha-modal--2xl',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition name="ha-modal-fade">
            <div v-show="show" class="ha-modal-wrap modal-component" scroll-region data-lenis-prevent>
                <transition name="ha-modal-fade">
                    <div v-show="show" class="ha-modal-backdrop" @click="close" />
                </transition>

                <transition name="ha-modal-pop">
                    <div v-show="show" class="ha-modal" :class="maxWidthClass" role="dialog" aria-modal="true">
                        <slot v-if="show" />
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
<style>
.modal-component{
    z-index: 1000;
}
</style>
