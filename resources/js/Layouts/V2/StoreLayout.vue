<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import Header from '@/Components/V2/Header.vue';
import Footer from '@/Components/V2/Footer.vue';
import Icon from '@/Components/V2/Icon.vue';

const showTop = ref(false);

const onScroll = () => {
    showTop.value = window.scrollY > 600;
};

const scrollTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }));
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
    <div class="hf">
        <Header />

        <main class="hf-main">
            <slot />
        </main>

        <Footer />

        <Transition name="hf-fade">
            <button v-if="showTop" type="button" class="hf-totop" aria-label="Назад кон врвот" @click="scrollTop">
                <Icon name="arrow-up" />
            </button>
        </Transition>
    </div>
</template>
