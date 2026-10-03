<script setup>
import { computed, ref } from 'vue';
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from '@/utils/useCart';
import { finalPrice, formatPrice, productUrl, toast } from '@/utils/useShop';

const props = defineProps({
    product: { type: Object, required: true },
});

const { addToCart } = useCart();
const added = ref(false);

// Listing endpoints return either name/main_image or title/img
const name = computed(() => props.product.name ?? props.product.title);
const image = computed(() => props.product.main_image ?? props.product.img);
const url = computed(() => productUrl(props.product));

const add = () => {
    addToCart(props.product);
    toast('Продуктот е додаден во вашата кошничка', true);
    added.value = true;
    setTimeout(() => (added.value = false), 1500);
};
</script>

<template>
    <article class="hf-card">
        <a class="hf-card__media" :href="url">
            <span v-if="product.disscount" class="hf-card__badge">-{{ product.disscount }}%</span>
            <span v-else-if="product.label" class="hf-card__badge hf-card__badge--label">{{ product.label }}</span>
            <img :src="image" :alt="name" loading="lazy" />
        </a>
        <div class="hf-card__body">
            <span v-if="product.category_name" class="hf-card__cat">{{ product.category_name }}</span>
            <h3 class="hf-card__title"><a :href="url">{{ name }}</a></h3>
            <div class="hf-card__foot">
                <div class="hf-price" :class="{ 'hf-price--sale': product.disscount }">
                    <del v-if="product.disscount">{{ formatPrice(product.price) }}</del>
                    <strong>{{ formatPrice(finalPrice(product)) }}</strong>
                </div>
                <button
                    type="button"
                    class="hf-card__add"
                    :class="{ 'is-added': added }"
                    title="Додади во кошничка"
                    aria-label="Додади во кошничка"
                    @click="add"
                >
                    <Icon :name="added ? 'check' : 'bag'" />
                </button>
            </div>
        </div>
    </article>
</template>
