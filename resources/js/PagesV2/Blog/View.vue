<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import Icon from '@/Components/V2/Icon.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});
const blog = computed(() => props.data)
</script>

<template>
    <Head>
        <title>{{ blog.title }} - Хедра Фарм Блог</title>
        <meta name="description" :content="blog.short_description || 'Прочитајте ја оваа статија на Хедра Фарм блог.'" />
        <meta name="keywords" :content="'блог, ' + blog.title + ', Хедра Фарм, здравје'" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" :content="blog.title + ' - Хедра Фарм'" />
        <meta property="og:description" :content="blog.short_description || 'Прочитајте ја оваа статија на Хедра Фарм блог.'" />
        <meta property="og:type" content="article" />
        <meta property="og:image" :content="'/assets' + blog.image" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" :href="'https://hederafarmplus.mk/blogs/' + blog.id + '/read'" />
    </Head>

    <StoreLayout>
        <PageHero :crumb="blog.title" :trail="[{ label: 'Блог', href: '/blogs' }]" />

        <article class="hf-section">
            <div class="hf-container hf-container--narrow">
                <img class="hf-article__cover" :src="'/assets' + blog.image" :alt="blog.title" />
                <div class="hf-article__meta">
                    <span v-if="blog.date"><Icon name="calendar" /> {{ blog.date }}</span>
                    <span v-if="blog.user"><Icon name="user" /> {{ blog.user }}</span>
                </div>
                <h1 class="hf-article__title">{{ blog.title }}</h1>
                <div class="hf-prose" v-html="blog.content"></div>

                <a href="/blogs" class="hf-btn hf-btn--outline" style="margin-top: 2.5rem">
                    <Icon name="arrow-left" /> Назад кон блогот
                </a>
            </div>
        </article>
    </StoreLayout>
</template>
