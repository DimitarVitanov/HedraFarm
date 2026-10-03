<script setup>
import { ref, onMounted } from "vue";
import Icon from '@/Components/V2/Icon.vue';
import ProductCard from '@/Components/V2/ProductCard.vue';

const products = ref([])
const loading = ref(true)

onMounted(async() => {
    products.value = await fetchPopularPorducts()
})

async function fetchPopularPorducts() {
    try {
        loading.value = true
        const response = await fetch('/products/popular/fetch');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error('Error fetching popular products:', error);
        return [];
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <section class="hf-section">
        <div class="hf-container">
            <div class="hf-section-head">
                <div>
                    <span class="hf-eyebrow">Избор на купувачите</span>
                    <h2 class="hf-title">Популарни производи</h2>
                </div>
                <a href="/store" class="hf-link">Сите производи <Icon name="arrow-right" /></a>
            </div>

            <div class="hf-split">
                <div class="hf-grid">
                    <template v-if="loading">
                        <div v-for="n in 4" :key="n" class="hf-skel" style="height: 340px"></div>
                    </template>
                    <ProductCard v-for="product in products" v-else :key="product.id" :product="product" />
                </div>
                <a href="/products/706/view" class="hf-banner">
                    <img src="/assets/img/banner/super-immunace.webp" alt="Immunace таблети x30 - 870 денари" width="600" height="980" loading="lazy" />
                </a>
            </div>
        </div>
    </section>
</template>
