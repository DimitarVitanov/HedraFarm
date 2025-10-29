<script setup>
import { ref, onMounted } from "vue";
import { useCart } from "@/utils/useCart";
const { addToCart } = useCart()
const products = ref([])
const loading = ref(false)
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
onMounted(async() => {
    products.value = await fetchPopularPorducts()
    console.log(products)
})

async function fetchPopularPorducts() {
    try {
        loading.value = true
    const response = await fetch('/products/popular/fetch');
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

const addPorduct = (product) => {
    addToCart(product)
    fireMessage('Продуктот е додаден во вашата кошничка', true)
}
</script>

<template>
   <!-- popular item -->
   <div class="product-area pb-100">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading-inline">
                            <h2 class="site-title">Популарни Производи</h2>
                            <a href="/store">Сите Производи <i class="fas fa-angle-double-right"></i></a>
                        </div>
                        <!--
                        <div class="item-tab">
                            <ul class="nav nav-pills mt-6 mb-20" id="item-tab" role="tablist">
                                <li v-for="(product,index) in products" class="nav-item" role="presentation">
                                    <button class="nav-link active" id="item-tab1" data-bs-toggle="pill"
                                        data-bs-target="#pill-item-tab1" type="button" role="tab"
                                        aria-controls="pill-item-tab1" aria-selected="true">{{product.category_name}}</button>
                                </li>
                            </ul>
                        </div>
                    -->

                    </div>
                </div>
                <div class="tab-content wow fadeInUp" data-wow-delay=".25s" id="item-tabContent">
                    <div class="tab-pane show active" id="pill-item-tab1" role="tabpanel" aria-labelledby="item-tab1"
                        tabindex="0">
                        <div class="row g-3 item-2">
                            <div v-for="(product,index) in products"  class="col-md-6 col-lg-4 col-xl-3">
                                <div class="product-item bg-white" >
                                    <div class="product-img">
                                        <a :href="'/products/' + product.id + '/view'"><img :src="product.img" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a :href="'/products/' + product.id + '/view'"
                                                    data-bs-placement="top" data-tooltip="tooltip"
                                                    title="Отвори"><i class="far fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a :href="'/products/' + product.id + '/view'">{{product.title}}</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>{{product.price}}ден</span>
                                            </div>
                                            <button @click="addPorduct(product)" type="button" class="product-cart-btn" data-bs-placement="left"
                                                data-tooltip="tooltip" title="Додади во кошничка">
                                                <i class="fa fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="product-banner wow fadeInRight" data-wow-delay=".25s">
                    <a href="/store">
                        <img src="/assets/img/banner/popular_product_banner.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- popular item end -->

</template>
