<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import { Autoplay, Navigation } from "swiper/modules";
import Icon from '@/Components/V2/Icon.vue';
import ProductCard from '@/Components/V2/ProductCard.vue';

const loading = ref(true)
const products = ref([])

onMounted(async()=>{
    products.value = await fetchTrendingProducts()
})

async function fetchTrendingProducts() {
    try {
        loading.value = true
        const response = await fetch('/products/trending/fetch');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error('Error fetching trending products:', error);
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
                    <span class="hf-eyebrow">Најбарани</span>
                    <h2 class="hf-title">Тренд производи</h2>
                </div>
                <a href="/store" class="hf-link">Види ги сите <Icon name="arrow-right" /></a>
            </div>

            <div v-if="loading" class="hf-grid hf-grid--5">
                <div v-for="n in 5" :key="n" class="hf-skel" style="height: 340px"></div>
            </div>
            <Swiper
                v-else
                class="hf-carousel"
                :slides-per-view="2"
                :space-between="12"
                :breakpoints="{
                    640: { slidesPerView: 3, spaceBetween: 16 },
                    992: { slidesPerView: 4, spaceBetween: 20 },
                    1200: { slidesPerView: 5, spaceBetween: 20 },
                }"
                :rewind="true"
                :modules="[Navigation, Autoplay]"
                :autoplay="{ delay: 3500, disableOnInteraction: true, pauseOnMouseEnter: true }"
                :navigation="true"
            >
                <SwiperSlide v-for="product in products" :key="product.id">
                    <ProductCard :product="product" />
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>
