<script setup>
import { ref, onMounted } from "vue";
import { useCart } from "@/utils/useCart";

const { addToCart } = useCart()
const on_sale = ref([])
const best_seller = ref([])
const top_rated = ref([])
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

onMounted(async () => {
    on_sale.value = await fetchOnSaleProducts()
    best_seller.value = await fetchBestSellerProducts()
    top_rated.value = await fetchTopRatedProducts()
})

async function fetchOnSaleProducts() {
    try {
        loading.value = true
    const response = await fetch('/products/on-sale/fetch');
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

async function fetchBestSellerProducts() {
    try {
        loading.value = true
    const response = await fetch('/products/best-seller/fetch');
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

async function fetchTopRatedProducts() {
    try {
        loading.value = true
    const response = await fetch('/products/top-rated/fetch');
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

const addProduct = (product) => {
    addToCart(product)
    fireMessage('Продуктот е додаден во вашата кошничка', true)
}
</script>

<template>
     <!-- product list -->
     <div class="product-list pb-100">
        <div class="container wow fadeInUp" data-wow-delay=".25s">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="product-list-box">
                        <h2 class="product-list-title">Со Поуст</h2>
                        <div v-for="(product,index) in on_sale" class="product-list-item">
                            <div class="product-list-img">
                                <a :href="'/products/' + product.id + '/view' "><img :src="product.img" alt="On sale image"></a>
                            </div>
                            <div class="product-list-content">
                                <h4><a :href="'/products' + product.id + '/view'">{{product.title}}</a></h4>
                                <div class="product-list-rate">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="product-list-price">
                                    <del v-if="product.disscount">{{product.price}} ден</del><span>{{product.disscount ? (product.price - (product.price * product.disscount / 100)).toFixed(2) : product.price}} ден</span>
                                </div>
                            </div>
                            <button @click="addProduct(product)" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                title="Додади во кошничка"><i class="fa fa-shopping-bag"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="product-list-box">
                        <h2 class="product-list-title">Нај Продавани</h2>
                        <div v-for="(product,index) in best_seller" class="product-list-item">
                            <div class="product-list-img">
                                <a :href="'/products/' + product.id + '/view' "><img :src="product.img" alt="On sale image"></a>
                            </div>
                            <div class="product-list-content">
                                <h4><a :href="'/products' + product.id + '/view'">{{product.title}}</a></h4>
                                <div class="product-list-rate">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="product-list-price">
                                    <del v-if="product.disscount">{{product.price}} ден</del><span>{{product.disscount ? (product.price - (product.price * product.disscount / 100)).toFixed(2) : product.price}} ден</span>
                                </div>
                            </div>
                            <button @click="addProduct(product)" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                title="Додади во кошничка"><i class="fa fa-shopping-bag"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-4 d-none">
                    <div class="product-list-box">
                        <h2 class="product-list-title">Препорачани</h2>
                        <div v-for="(product,index) in top_rated" class="product-list-item">
                            <div class="product-list-img">
                                <a :href="'/products/' + product.id + '/view' "><img :src="product.img" alt="On sale image"></a>
                            </div>
                            <div class="product-list-content">
                                <h4><a :href="'/products' + product.id + '/view'">{{product.title}}</a></h4>
                                <div class="product-list-rate">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="product-list-price">
                                    <del v-if="product.disscount">{{product.price}} ден</del><span>{{product.disscount ? (product.price - (product.price * product.disscount / 100)).toFixed(2) : product.price}} ден</span>
                                </div>
                            </div>
                            <button @click="addProduct(product)" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                title="Додади во кошничка"><i class="fa fa-shopping-bag"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product list end -->

</template>
