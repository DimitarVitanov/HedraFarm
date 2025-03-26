<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { Autoplay, Navigation } from "swiper/modules";

// Define product items
const products = ref([
  {
    id: 1,
    img: "/assets/img/product/01.png",
    label: "New",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: null,
  },
  {
    id: 2,
    img: "/assets/img/product/02.png",
    label: "Hot",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: null,
  },
  {
    id: 3,
    img: "/assets/img/product/03.png",
    label: "Out Of Stock",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: null,
  },
  {
    id: 4,
    img: "/assets/img/product/04.png",
    label: "10% Off",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: "$190.00",
  },
  {
    id: 5,
    img: "/assets/img/product/05.png",
    label: "",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: null,
  },
  {
    id: 6,
    img: "/assets/img/product/06.png",
    label: "",
    title: "Surgical Face Mask",
    price: "$250.00",
    discountPrice: null,
  },
]);

onMounted(() => {
  console.log("Product Slider Initialized");
});
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

      <div class="product-wrap item-2">
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
            <div class="product-item">
              <div class="product-img">
                <span v-if="product.label" class="type">{{ product.label }}</span>
                <a href="shop-single.html">
                  <img :src="product.img" alt="Product Image" />
                </a>
                <div class="product-action-wrap">
                  <div class="product-action">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" title="Quick View">
                      <i class="far fa-eye"></i>
                    </a>
                    <a href="#" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                    <a href="#" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                  </div>
                </div>
              </div>

              <div class="product-content">
                <h3 class="product-title">
                  <a href="shop-single.html">{{ product.title }}</a>
                </h3>
                <div class="product-rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                </div>

                <div class="product-bottom">
                  <div class="product-price">
                    <del v-if="product.discountPrice">{{ product.price }}</del>
                    <span>{{ product.discountPrice || product.price }}</span>
                  </div>
                  <button type="button" class="product-cart-btn" title="Add To Cart">
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
