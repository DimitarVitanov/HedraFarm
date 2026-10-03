<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useShop } from '@/utils/useShop';

const { company, loadCompany } = useShop()

onMounted(loadCompany)

const stats = [
    { value: '50000', sign: '+', label: 'Продадени производи' },
    { value: '5000', sign: '+', label: 'Задоволни клиенти' },
    { value: '5', sign: '+', label: 'Вработени' },
]
</script>

<template>
    <Head>
        <title>За Нас - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Дознајте повеќе за Хедра Фарм - вашата доверлива аптека во Струмица со долгогодишно искуство. Аптека Струмица која се грижи за здравјето на сите генерации со квалитетни производи." />
        <meta name="keywords" content="за нас, Хедра Фарм, аптека Струмица, аптека во Струмица, здравствена грижа, искуство, професионална услуга, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="За Нас - Аптека Струмица | Хедра Фарм" />
        <meta property="og:description" content="Дознајте повеќе за Хедра Фарм - вашата доверлива аптека во Струмица со долгогодишно искуство." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk/about" />
        <meta property="og:image" content="/assets/img/about/hedra_about_1.png" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/about" />
    </Head>

    <StoreLayout>
        <PageHero title="За Нас" />

        <section class="hf-section">
            <div class="hf-container hf-about">
                <div class="hf-about__media">
                    <img src="/assets/img/about/hedra_about_1.webp" alt="Аптека Хедерафарм+" width="1440" height="960" />
                    <img src="/assets/img/about/hedra_about_2.webp" alt="Аптека Хедерафарм+ - внатрешност" width="1080" height="1080" />
                    <span class="hf-about__badge"><Icon name="shield" /> Докажано искуство</span>
                </div>
                <div>
                    <span class="hf-eyebrow">За нас</span>
                    <h2>Ние се грижиме за <span>сите</span> генерации</h2>
                    <div class="hf-prose" v-html="company.about"></div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.75rem">
                        <a href="/store" class="hf-btn">Онлајн Продавница <Icon name="arrow-right" /></a>
                        <a href="/contact" class="hf-btn hf-btn--outline">Контакт</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="hf-section hf-section--flush-top">
            <div class="hf-container hf-stats">
                <div v-for="stat in stats" :key="stat.label" class="hf-stat">
                    <strong>{{ Number(stat.value).toLocaleString('mk-MK') }}<span>{{ stat.sign }}</span></strong>
                    {{ stat.label }}
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
