<script setup>
import { ref, onMounted } from "vue";
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from "@/utils/useCart";
import { finalPrice, formatPrice, productUrl, toast } from '@/utils/useShop';

const { addToCart } = useCart()
const on_sale = ref([])
const best_seller = ref([])

onMounted(async () => {
    [on_sale.value, best_seller.value] = await Promise.all([
        fetchProductsByType('on-sale'),
        fetchProductsByType('best-seller'),
    ])
})

async function fetchProductsByType(type) {
    try {
        const response = await fetch(`/products/${type}/fetch`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error(`Error fetching ${type} products:`, error);
        return [];
    }
}

const addProduct = (product) => {
    addToCart(product)
    toast('Продуктот е додаден во вашата кошничка', true)
}

const lists = [
    { title: 'Со попуст', eyebrow: 'Заштеди', items: on_sale },
    { title: 'Најпродавани', eyebrow: 'Омилени', items: best_seller },
]
</script>

<template>
    <section class="hf-section">
        <div class="hf-container hf-duo">
            <div v-for="list in lists" :key="list.title" class="hf-panel">
                <span class="hf-eyebrow">{{ list.eyebrow }}</span>
                <h2 class="hf-title" style="margin-bottom: 0.5rem">{{ list.title }}</h2>
                <div class="hf-rows">
                    <div v-for="product in list.items.value" :key="product.id" class="hf-row">
                        <a class="hf-row__img" :href="productUrl(product)">
                            <img :src="product.img" :alt="product.title" loading="lazy" />
                        </a>
                        <div>
                            <h4 class="hf-row__title"><a :href="productUrl(product)">{{ product.title }}</a></h4>
                            <div class="hf-price" :class="{ 'hf-price--sale': product.disscount }">
                                <strong>{{ formatPrice(finalPrice(product)) }}</strong>
                                <del v-if="product.disscount">{{ formatPrice(product.price) }}</del>
                            </div>
                        </div>
                        <button type="button" class="hf-card__add" title="Додади во кошничка" aria-label="Додади во кошничка" @click="addProduct(product)">
                            <Icon name="bag" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
