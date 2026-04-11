<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
//Pagination
import { Autoplay, Navigation, Pagination } from "swiper/modules"
const sliders = ref([])
const loading = ref(false)
const slides = ref([
  {
    title: "Medicine & Health Care",
    subtitle: "Easy Health Care",
    description:
      "There are many variations of passages lorem ipsum available but the majority have suffered alteration in some form by injected humour.",
    imgSrc: "/assets/img/hero/01.png",
    price: "$250",
  },
  {
    title: "Medicine & Health Care",
    subtitle: "Easy Health Care",
    description:
      "There are many variations of passages lorem ipsum available but the majority have suffered alteration in some form by injected humour.",
    imgSrc: "/assets/img/hero/02.png",
    price: "$250",
  },
  {
    title: "Medicine & Health Care",
    subtitle: "Easy Health Care",
    description:
      "There are many variations of passages lorem ipsum available but the majority have suffered alteration in some form by injected humour.",
    imgSrc: "/assets/img/hero/03.png",
    price: "$250",
  },
]);
const doAnimations = (el) => {
  el.classList.add("animate__animated", "animate__fadeInUp");
  el.addEventListener("animationend", () => {
    el.classList.remove("animate__animated", "animate__fadeInUp");
  });
};

onMounted(async ()=>{
    sliders.value = await fetchSliders();
    nextTick(() => {
    document.querySelectorAll("[data-animation]").forEach((el) => {
      doAnimations(el);
    });
  });
})

async function fetchSliders() {
    try {
        loading.value = true
        const response = await fetch('/sliders/fetch')
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        if(data.success){
            loading.value = false
            return data.data
        }
        loading.value = false
        return []
    } catch (error) {
        console.error(error)
        loading.value  = false
        return []
    }
}
</script>


<template>
         <!-- hero section -->
         <section class="hero-section  hs-1">
            <div class="container">
              <Swiper
                :slides-per-view="1"
                :loop="true"
                :modules="[Navigation, Autoplay, Pagination]"
                :autoplay="{ delay: 5000, disableOnInteraction: true }"
                :navigation="true"
                :pagination="{ clickable: true }"
                class="hero-slider"
              >
                <SwiperSlide v-for="(slide, index) in sliders" :key="index">
                  <!-- Desktop Layout -->
                  <div class="hero-single d-none d-lg-flex">
                    <div class="container">
                      <div class="row align-items-center">
                        <div class="col-lg-6">
                          <div class="hero-content">
                            <h6 class="hero-sub-title" data-animation="fadeInUp">
                              {{ slide.subtitle }}
                            </h6>
                            <h1 class="hero-title" data-animation="fadeInRight">
                              {{ slide.title }}
                            </h1>
                            <p data-animation="fadeInLeft">
                              {{ slide.description }}
                            </p>
                            <div class="hero-btn" data-animation="fadeInUp">
                              <a href="/store" class="theme-btn">
                               Онлине Продавница <i class="fas fa-arrow-right"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="hero-right" data-animation="fadeInRight">
                            <div class="hero-img">
                              <div class="hero-img-price">
                                <span>Цена</span>
                                <span>{{ slide.price }} ден</span>
                              </div>
                              <img :src="slide.image" alt="Hero Image" width="500" height="400" loading="eager" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Mobile Layout -->
                  <div class="hero-mobile d-lg-none">
                    <div class="hero-mobile-img">
                      <img :src="slide.image" :alt="slide.title" loading="eager" />
                    </div>
                    <div class="hero-mobile-overlay">
                      <span class="hero-mobile-badge" v-if="slide.subtitle">{{ slide.subtitle }}</span>
                      <h2 class="hero-mobile-title">{{ slide.title }}</h2>
                      <p class="hero-mobile-desc" v-if="slide.description">{{ slide.description }}</p>
                      <div class="hero-mobile-bottom">
                        <span class="hero-mobile-price" v-if="slide.price">{{ slide.price }} ден</span>
                        <a href="/store" class="hero-mobile-btn">
                          Продавница <i class="fas fa-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </SwiperSlide>
              </Swiper>
            </div>
        </section>
        <!-- hero section-->
</template>

<style scoped>
.hero-section {
  min-height: 500px;
}
.hero-img img {
  width: 100%;
  height: auto;
  aspect-ratio: 500 / 400;
}

/* Mobile Hero Slider */
.hero-mobile {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  min-height: 340px;
  background: #f0fafa;
}

.hero-mobile-img {
  width: 100%;
  height: 340px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px 20px 80px;
  background: linear-gradient(135deg, #f0fafa 0%, #e6f7f7 50%, #f8f9fa 100%);
}

.hero-mobile-img img {
  max-height: 100%;
  max-width: 70%;
  object-fit: contain;
  filter: drop-shadow(0 8px 24px rgba(12, 184, 182, 0.15));
}

.hero-mobile-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 20px;
  background: linear-gradient(to top, rgba(255,255,255,0.97) 60%, rgba(255,255,255,0) 100%);
}

.hero-mobile-badge {
  display: inline-block;
  background: #0cb8b6;
  color: white;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 4px 12px;
  border-radius: 20px;
  margin-bottom: 6px;
}

.hero-mobile-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0 0 4px;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.hero-mobile-desc {
  font-size: 13px;
  color: #666;
  margin: 0 0 10px;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.hero-mobile-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.hero-mobile-price {
  font-size: 18px;
  font-weight: 800;
  color: #0cb8b6;
}

.hero-mobile-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #0cb8b6;
  color: white;
  padding: 8px 18px;
  border-radius: 25px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
  white-space: nowrap;
}

.hero-mobile-btn:hover {
  background: #0a9e9c;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(12, 184, 182, 0.3);
}

.hero-mobile-btn i {
  font-size: 11px;
}

/* Hide swiper navigation arrows on mobile */
@media (max-width: 991px) {
  .hero-section :deep(.swiper-button-next),
  .hero-section :deep(.swiper-button-prev) {
    display: none;
  }

  .hero-section :deep(.swiper-pagination) {
    bottom: 6px !important;
  }

  .hero-section :deep(.swiper-pagination-bullet) {
    width: 8px;
    height: 8px;
    background: #0cb8b6;
    opacity: 0.3;
  }

  .hero-section :deep(.swiper-pagination-bullet-active) {
    opacity: 1;
    width: 20px;
    border-radius: 10px;
  }

  .hero-section {
    min-height: auto;
  }
}
</style>
