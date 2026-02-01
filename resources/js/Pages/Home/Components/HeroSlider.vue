<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
//Pagination
import { Autoplay, Navigation } from "swiper/modules"
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
                :modules="[Navigation, Autoplay]"
                :autoplay="{ delay: 5000, disableOnInteraction: true }"
                :navigation="true"
                :pagination="{ clickable: true }"
                class="hero-slider"
              >
                <SwiperSlide v-for="(slide, index) in sliders" :key="index">
                  <div class="hero-single">
                    <div class="container">
                      <div class="row align-items-center">
                        <div class="col-lg-6">
                          <div class="hero-content">
                            <h6 class="hero-sub-title" data-animation="fadeInUp">
                              {{ slide.subtitle }}
                            </h6>
                            <h1 class="hero-title" data-animation="fadeInRight">
                              {{ slide.title }}  <!-- <span>For Your</span> Family -->
                            </h1>
                            <p data-animation="fadeInLeft">
                              {{ slide.description }}
                            </p>
                            <div class="hero-btn" data-animation="fadeInUp">
                              <a href="/store" class="theme-btn">
                               Онлине Продавница <i class="fas fa-arrow-right"></i>
                              </a>
                              <!--
                              <a href="/products" class="theme-btn theme-btn2">
                                Дознај Повеќе <i class="fas fa-arrow-right"></i>
                              </a>
                            -->
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
</style>
