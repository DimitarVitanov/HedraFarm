<script setup>
import Icon from '@/Components/V2/Icon.vue';

defineProps({
    blog: { type: Object, required: true },
});
</script>

<template>
    <article class="hf-post">
        <a class="hf-post__media" :href="'/blogs/' + blog.id + '/read'">
            <img :src="'/assets' + blog.image" :alt="blog.title" loading="lazy" />
        </a>
        <div class="hf-post__body">
            <span class="hf-post__date"><Icon name="calendar" /> {{ blog.date }}</span>
            <h3 class="hf-post__title"><a :href="'/blogs/' + blog.id + '/read'">{{ blog.title }}</a></h3>
            <div class="hf-post__excerpt" v-html="blog.short_description"></div>
            <a class="hf-link" :href="'/blogs/' + blog.id + '/read'">Прочитај повеќе <Icon name="arrow-right" /></a>
        </div>
    </article>
</template>
