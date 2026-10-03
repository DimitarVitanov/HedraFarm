<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import Header from '@/Components/V2/Header.vue';
import Footer from '@/Components/V2/Footer.vue';
import Icon from '@/Components/V2/Icon.vue';
import { isPreviewing } from '@/utils/preview';

const showTop = ref(false);
const previewing = isPreviewing();

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

        <a v-if="previewing" href="?preview=v1" class="hf-preview" title="Врати се на тековниот дизајн">
            Преглед на нов дизајн <span>Излез <Icon name="x" /></span>
        </a>

        <Transition name="hf-fade">
            <button v-if="showTop" type="button" class="hf-totop" aria-label="Назад кон врвот" @click="scrollTop">
                <Icon name="arrow-up" />
            </button>
        </Transition>
    </div>
</template>
