<script setup>
import { ref, onMounted } from 'vue';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { Autoplay, Navigation, Pagination } from "swiper/modules"
import Icon from '@/Components/V2/Icon.vue';
import { formatPrice, FREE_SHIPPING_FROM } from '@/utils/useShop';

const sliders = ref([])
const loading = ref(true)
const brokenImages = ref([])

onMounted(async ()=>{
    sliders.value = await fetchSliders();
})

async function fetchSliders() {
    try {
        loading.value = true
        const response = await fetch('/sliders/fetch')
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        return data.success ? data.data : []
    } catch (error) {
        console.error(error)
        return []
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <section class="hf-hero">
        <div class="hf-container hf-hero__grid">
            <div class="hf-hero__slider">
                <div v-if="loading" class="hf-slide">
                    <div>
                        <div class="hf-skel" style="height: 28px; width: 180px; margin-bottom: 1rem"></div>
                        <div class="hf-skel" style="height: 96px; margin-bottom: 1rem"></div>
                        <div class="hf-skel" style="height: 48px; width: 200px"></div>
                    </div>
                    <div class="hf-skel" style="height: 300px; border-radius: 50%"></div>
                </div>
                <Swiper
                    v-else
                    :slides-per-view="1"
                    :loop="sliders.length > 1"
                    :modules="[Navigation, Autoplay, Pagination]"
                    :autoplay="{ delay: 5500, disableOnInteraction: true }"
                    :navigation="sliders.length > 1"
                    :pagination="{ clickable: true }"
                >
                    <SwiperSlide v-for="slide in sliders" :key="slide.id">
                        <div class="hf-slide">
                            <div>
                                <span v-if="slide.subtitle" class="hf-slide__tag">{{ slide.subtitle }}</span>
                                <h2 class="hf-slide__title">{{ slide.title }}</h2>
                                <p v-if="slide.description && slide.description !== slide.subtitle" class="hf-slide__desc">
                                    {{ slide.description }}
                                </p>
                                <div class="hf-slide__cta">
                                    <a href="/store" class="hf-btn hf-btn--lg">
                                        Онлајн Продавница <Icon name="arrow-right" />
                                    </a>
                                    <div v-if="slide.price" class="hf-slide__price">
                                        <small>Цена</small>
                                        <strong>{{ formatPrice(slide.price) }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="hf-slide__media">
                                <img
                                    v-if="!brokenImages.includes(slide.id)"
                                    :src="slide.image"
                                    :alt="slide.title"
                                    width="420"
                                    height="340"
                                    loading="eager"
                                    @error="brokenImages.push(slide.id)"
                                />
                                <span v-else class="hf-slide__fallback"><Icon name="cross" /></span>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>
            </div>

            <div class="hf-hero__side">
                <a href="/store" class="hf-tile hf-tile--dark">
                    <span class="hf-tile__icon"><Icon name="truck" /></span>
                    <h3>Бесплатна достава</h3>
                    <p>За сите нарачки над {{ formatPrice(FREE_SHIPPING_FROM) }}, низ цела Македонија.</p>
                    <span class="hf-link">Купи сега <Icon name="arrow-right" /></span>
                </a>
                <a href="/contact" class="hf-tile hf-tile--lime">
                    <span class="hf-tile__icon"><Icon name="headset" /></span>
                    <h3>Совет од фармацевт</h3>
                    <p>Имате прашање за некој производ? Контактирајте нè.</p>
                    <span class="hf-link">Контактирај нè <Icon name="arrow-right" /></span>
                </a>
            </div>
        </div>
    </section>
</template>
