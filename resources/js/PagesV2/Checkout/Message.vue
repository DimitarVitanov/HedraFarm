<script setup>
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import Icon from '@/Components/V2/Icon.vue';
</script>

<template>
    <Head>
        <title>Успешна Нарачка - Хедра Фарм</title>
        <meta name="description" content="Вашата нарачка е успешно примена. Ви благодариме за довербата!" />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <StoreLayout>
        <section class="hf-section">
            <div class="hf-container">
                <div class="hf-panel hf-done">
                    <div class="hf-done__icon"><Icon name="check" /></div>
                    <h2>Ви благодариме за вашата нарачка!</h2>
                    <p>На внесениот email ќе добиете повеќе информации за вашата нарачка.</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center">
                        <a href="/store" class="hf-btn">Продолжи да купуваш <Icon name="arrow-right" /></a>
                        <a href="/" class="hf-btn hf-btn--outline">Почетна</a>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
