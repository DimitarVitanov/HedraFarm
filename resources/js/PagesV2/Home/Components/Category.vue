<script setup>
import { onMounted } from 'vue';
import Icon from '@/Components/V2/Icon.vue';
import { useShop, categoryIcon } from '@/utils/useShop';

const { categories, loadCategories } = useShop();

onMounted(() => loadCategories());
</script>

<template>
    <section class="hf-section">
        <div class="hf-container">
            <div class="hf-section-head">
                <div>
                    <span class="hf-eyebrow">Пазарувај по</span>
                    <h2 class="hf-title">Категории</h2>
                </div>
                <a href="/store" class="hf-link">Сите производи <Icon name="arrow-right" /></a>
            </div>

            <div class="hf-catgrid">
                <template v-if="categories.length">
                    <a v-for="category in categories" :key="category.id" :href="'/store?category=' + category.id" class="hf-cat">
                        <span class="hf-cat__icon"><i :class="'fas ' + categoryIcon(category)"></i></span>
                        <strong>{{ category.translated }}</strong>
                    </a>
                </template>
                <template v-else>
                    <div v-for="n in 10" :key="n" class="hf-skel" style="height: 82px"></div>
                </template>
            </div>
        </div>
    </section>
</template>
