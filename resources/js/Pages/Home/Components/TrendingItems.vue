<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { Autoplay, Navigation } from "swiper/modules";
import { useCart } from "@/utils/useCart";
const { addToCart } = useCart()

// Define product items
const loading = ref(false)
const products = ref([])
const fireMessage = (message, success = false, reload = false) => {
  Swal.fire({
    position: "top",
    toast: true,
    icon: success ? "success" : "error",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (reload) {
      location.reload();
    }
  });
};

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
        loading.value = false
        return data.data;
    } catch (error) {
        loading.value = false
        console.error('Error fetching trending products:', error);
        return [];
    }
}

function addProduct(product){
    addToCart(product)
    fireMessage('Продуктот е додаден во вашата кошничка', true)
}

</script>

<template>
  <div class="product-area pb-100">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="site-heading-inline">
            <h2 class="site-title">Тренд производи</h2>
          </div>
        </div>
      </div>

      <div v-if="!loading" class="product-wrap item-2">
        <Swiper
          :slides-per-view="1"
          :breakpoints="{
            600: { slidesPerView: 2 },
            1000: { slidesPerView: 3 },
            1200: { slidesPerView: 5 },
          }"
          :loop="true"
          :modules="[Navigation, Autoplay]"
          :autoplay="{ delay: 3000, disableOnInteraction: true }"
          :navigation="true"
          class="product-slider"
        >
          <SwiperSlide v-for="product in products" :key="product.id">
            <div class="product-item bg-white  mx-2">
              <div class="product-img">
                <span v-if="product.label" class="type">{{ product.label }}</span>
                <a :href="'/products/' + product.id + '/view'">
                  <img :src="product.img" alt="Product Image" />
                </a>
                <div class="product-action-wrap">
                  <div class="product-action">
                    <a :href="'/products/' + product.id + '/view'"><img :src="product.main_image" alt="">
                    <i class="far fa-eye"></i>
                    </a>
                  </div>
                </div>
              </div>

              <div class="product-content">
                <h3 class="product-title">
                  <a :href="'/products/' + product.id + '/view'">{{ product.title }}</a>
                </h3>
                <div class="product-rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>

                <div class="product-bottom">
                  <div class="product-price">
                    <del v-if="product.disscount">{{ product.price }} ден</del>
                    <span>{{product.disscount ? (product.price - (product.price * product.disscount / 100)).toFixed(2) : product.price}} ден</span>
                  </div>
                  <button type="button" @click="addProduct(product)" class="product-cart-btn" title="Додади во кошничка">
                    <i class="fa fa-shopping-bag"></i>
                  </button>
                </div>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </div>
    </div>
  </div>
</template>
