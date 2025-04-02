<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
//Pagination
import { Autoplay, Navigation } from "swiper/modules"

const categories = ref([
  { name: "Симптоми и состојби", items: 30, imgSrc: "/assets/img/icon/medicine.svg", link:'store?category=1' },
  { name: "Витамини и додатоци во исхрана", items: 14, imgSrc: "/assets/img/icon/supplements.svg", link:'store?category=2' },
  { name: "Гелови и креми", items: 14, imgSrc: "/assets/img/icon/beauty-care.svg", link:'store?category=3'},
  { name: "Препарати за деца", items: 8, imgSrc: "/assets/img/icon/baby-mom-care.svg", link:'store?category=4' },
  { name: "Храна за деца", items: 24, imgSrc: "/assets/img/icon/food-nutrition.svg", link:'store?category=6' },
  { name: "Опрема за бебиња", items: 12, imgSrc: "/assets/img/icon/medical-equipements.svg", link:'store?category=8' },
  { name: "Козметика", items: 11, imgSrc: "/assets/img/icon/beauty-care.svg", link:'store?category=8' },
  { name: "Орално здравје", items: 15, imgSrc: "/assets/img/icon/health-care.svg", link:'store?category=9' },
  { name: "Медицински уреди", items: 12, imgSrc: "/assets/img/icon/medical-equipements.svg", link:'store?category=10' },

]);


const doAnimations = (el) => {
  el.classList.add("animate__animated", "animate__fadeInUp");
  el.addEventListener("animationend", () => {
    el.classList.remove("animate__animated", "animate__fadeInUp");
  });
};
onMounted(()=>{
    nextTick(() => {
    document.querySelectorAll("[data-animation]").forEach((el) => {
      doAnimations(el);
    });
  });
})
</script>

<template>
          <!-- category area -->
          <div class="category-area ">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading-inline">
                            <h2 class="site-title">Категории</h2>
                        </div>
                    </div>
                </div>

                <section class="category-slider-section">
                    <div class="container">
                      <Swiper
                        :loop="true"
                        :space-between="20"
                        :modules="[Navigation, Autoplay]"
                        :autoplay="{ delay: 3000, disableOnInteraction: false }"
                        :pagination="{ clickable: true }"
                        :navigation="true"
                        :breakpoints="{
                          0: { slidesPerView: 2 },
                          600: { slidesPerView: 3 },
                          1000: { slidesPerView: 4 },
                          1200: { slidesPerView: 6 },
                          1400: { slidesPerView: 6 }
                        }"
                        class="category-slider"
                      >
                        <SwiperSlide v-for="(category, index) in categories" :key="index">
                          <div class="category-item">
                            <a :href="'/' + category.link">
                              <div class="category-info">
                                <div class="icon">
                                  <img :src="category.imgSrc" :alt="category.name" />
                                </div>
                                <div class="pt-5">
                                  <h5>{{ category.name }}</h5>
                                </div>
                              </div>
                            </a>
                          </div>
                        </SwiperSlide>
                      </Swiper>
                    </div>
                  </section>
            </div>
        </div>
        <!-- category area end-->
</template>
<style scoped>
.category-info{
    height: 250px !important;
  }
</style>
