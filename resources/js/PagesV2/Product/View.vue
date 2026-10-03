<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import ProductCard from '@/Components/V2/ProductCard.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from '@/utils/useCart';
import { finalPrice, formatPrice, toast, FREE_SHIPPING_FROM } from '@/utils/useShop';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
})

const { addToCart, updateQuantity, cart } = useCart()

const product = computed(() => props.data)
const related_products = ref([])
const quantity = ref(1)

onMounted(async () => {
    if (product.value.product_category_id) {
        const allRelated = await fetchRelated(product.value.product_category_id);
        related_products.value = allRelated.filter(p => p.id !== product.value.id).slice(0, 4);
    }
});

async function fetchRelated(id){
    try{
        const reponse = await fetch('/products/categories/' + id + '/fetch')
        if(!reponse.ok){
            throw new Error('An error occurred')
        }
        const data = await reponse.json()
        return data.success ? data.data : []
    }catch(error){
        console.log(error)
        return []
    }
}

const addProduct = () => {
    addToCart(product.value)
    const item = cart.items.find(i => i.id === product.value.id)
    if (item && quantity.value > 1) {
        updateQuantity(item.id, item.quantity + quantity.value - 1)
    }
    toast('Продуктот е додаден во вашата кошничка', true)
}

const buyNow = () => {
    addProduct()
    window.location.href = '/checkout'
}
</script>

<template>
    <Head>
        <title>{{ product.name }} - Хедра Фарм</title>
        <meta name="description" :content="product.short_description || product.name + ' - Купете онлајн на Хедра Фарм со бесплатна достава над 2000 денари.'" />
        <meta name="keywords" :content="product.name + ', купи онлајн, Хедра Фарм, аптека'" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" :content="product.name + ' - Хедра Фарм'" />
        <meta property="og:description" :content="product.short_description || product.name + ' - Купете онлајн на Хедра Фарм.'" />
        <meta property="og:type" content="product" />
        <meta property="og:image" :content="product.main_image" />
        <meta property="og:locale" content="mk_MK" />
        <meta property="product:price:amount" :content="product.price" />
        <meta property="product:price:currency" content="MKD" />
        <link rel="canonical" :href="'https://hederafarmplus.mk/products/' + product.id + '/view'" />
    </Head>

    <StoreLayout>
        <PageHero
            :crumb="product.name"
            :trail="[
                { label: 'Продавница', href: '/store' },
                ...(product.category ? [{ label: product.category.translated, href: '/store?category=' + product.product_category_id }] : []),
            ]"
        />

        <section class="hf-section">
            <div class="hf-container">
                <div class="hf-product">
                    <div class="hf-product__media">
                        <span v-if="product.disscount" class="hf-card__badge">-{{ product.disscount }}%</span>
                        <img :src="product.main_image" :alt="product.name" />
                    </div>

                    <div class="hf-product__info">
                        <a v-if="product.category" class="hf-chip" :href="'/store?category=' + product.product_category_id">
                            {{ product.category.translated }}
                        </a>
                        <h1 class="hf-product__title">{{ product.name }}</h1>

                        <div class="hf-product__price">
                            <strong :style="product.disscount ? 'color: var(--hf-sale)' : ''">{{ formatPrice(finalPrice(product)) }}</strong>
                            <del v-if="product.disscount">{{ formatPrice(product.price) }}</del>
                            <span v-if="product.disscount" class="hf-chip hf-chip--sale">Заштеда {{ product.disscount }}%</span>
                        </div>

                        <p v-if="product.short_description" class="hf-product__lead">{{ product.short_description }}</p>

                        <div class="hf-product__buy">
                            <div class="hf-qty hf-qty--lg">
                                <button type="button" aria-label="Намали" :disabled="quantity <= 1" @click="quantity--">
                                    <Icon name="minus" />
                                </button>
                                <span>{{ quantity }}</span>
                                <button type="button" aria-label="Зголеми" @click="quantity++">
                                    <Icon name="plus" />
                                </button>
                            </div>
                            <button type="button" class="hf-btn hf-btn--lg" @click="addProduct">
                                <Icon name="bag" /> Додади во кошничка
                            </button>
                            <button type="button" class="hf-btn hf-btn--lg hf-btn--dark" @click="buyNow">
                                Купи веднаш
                            </button>
                        </div>

                        <ul class="hf-perks">
                            <li><Icon name="truck" /> <div>Бесплатна достава <small>за нарачки над {{ formatPrice(FREE_SHIPPING_FROM) }}</small></div></li>
                            <li><Icon name="wallet" /> <div>Плаќање при достава <small>во готово</small></div></li>
                            <li><Icon name="refresh" /> <div>Враќање на производи <small>во рок од 15 дена</small></div></li>
                        </ul>
                    </div>
                </div>

                <div v-if="product.description" class="hf-panel" style="margin-top: 3rem">
                    <h2 class="hf-title" style="margin-bottom: 1.25rem">Опис на производот</h2>
                    <div class="hf-prose" v-html="product.description"></div>
                </div>
            </div>
        </section>

        <section v-if="related_products.length > 0" class="hf-section hf-section--flush-top">
            <div class="hf-container">
                <div class="hf-section-head">
                    <div>
                        <span class="hf-eyebrow">Слични производи</span>
                        <h2 class="hf-title">Од истата категорија</h2>
                    </div>
                    <a :href="'/store?category=' + product.product_category_id" class="hf-link">Види ги сите <Icon name="arrow-right" /></a>
                </div>
                <div class="hf-grid">
                    <ProductCard v-for="item in related_products" :key="item.id" :product="item" />
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
