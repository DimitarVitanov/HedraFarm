<script setup>
import {onMounted, ref, computed } from 'vue';
import { useCart } from '@/utils/useCart';
const company = ref([]);
const showCategories = ref(false);
const categories = ref([])
const products = ref([])
const searchQuery = ref('')
const showSearchResults = ref(false)
const selectedCategory = ref('')
const { cart, totalPrice, removeFromCart } = useCart()

onMounted(async () => {
    company.value = await fetchCompanyInfo();
    categories.value = await fetchCategories();
    products.value = await fetchProducts();
});



async function fetchCompanyInfo() {
    try {
        const response = await fetch('/company/fetch');
        if (!response.ok) {
            throw new Error('An error occurred while fetching the data');
        }
        const data = await response.json();
        if (data.success) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.log(error);
        return [];
    }
}
async function fetchCategories(){
    try{
        const response = await fetch('/product-categories/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json()
        if(data.success){
            return data.data
        }
        return []
    } catch(error){
        console.log(error)
        return []
    }
}

const toggleCategories = () => {
  showCategories.value = !showCategories.value;
};

const showCategoriesOnHover = () => {
  showCategories.value = true;
};

const hideCategoriesOnLeave = () => {
  showCategories.value = false;
};

async function fetchProducts() {
    try {
        const response = await fetch('/products/fetch');
        if (!response.ok) {
            throw new Error('An error occurred while fetching products');
        }
        const data = await response.json();
        if (data.success) {
            return data.data;
        }
        return [];
    } catch (error) {
        console.log(error);
        return [];
    }
}

const filteredSearchResults = computed(() => {
    if (!searchQuery.value || searchQuery.value.length < 2) return [];
    
    let results = products.value;
    const query = searchQuery.value.toLowerCase();
    
    // Filter by category if selected
    if (selectedCategory.value) {
        results = results.filter(p => p.product_category_id === parseInt(selectedCategory.value));
    }
    
    // Filter by search query
    results = results.filter(p => 
        p.name.toLowerCase().includes(query) ||
        (p.short_description && p.short_description.toLowerCase().includes(query))
    );
    
    return results.slice(0, 8); // Limit to 8 results
});

const handleSearch = () => {
    if (searchQuery.value.length >= 2) {
        showSearchResults.value = true;
    } else {
        showSearchResults.value = false;
    }
};

const hideSearchResults = () => {
    setTimeout(() => {
        showSearchResults.value = false;
    }, 200);
};

const goToProduct = (productId) => {
    window.location.href = `/products/${productId}/view`;
};

const submitSearch = () => {
    if (searchQuery.value) {
        let url = `/store?search=${encodeURIComponent(searchQuery.value)}`;
        if (selectedCategory.value) {
            url += `&category=${selectedCategory.value}`;
        }
        window.location.href = url;
    }
};
</script>

<template>
        <!-- header area -->
        <header class="header">
            <!-- header top -->
            <div class="header-top">
                <div class="container">
                    <div class="header-top-wrapper">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 col-xl-5">
                                <div class="header-top-left">
                                    <ul class="header-top-list">
                                        <li><a :href="'mailto:' + company.email"><i class="far fa-envelopes"></i>
                                            {{company.email}}</a></li>
                                        <li><a href="tel:+21236547898"><i class="fa fa-headset"></i> {{company.phone}}</a></li>
                                        <li class="help"><a href="/contact"><i class="fa fa-comment"></i> Ви треба помош?</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 col-xl-7">
                                <div class="header-top-right">
                                    <ul class="header-top-list">
                                        <li><a href="#"><i class="far fa-timer"></i> </a></li>
                                        <!--
                                        <li>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="far fa-usd"></i> USD
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">USD</a>
                                                    <a class="dropdown-item" href="#">EUR</a>
                                                    <a class="dropdown-item" href="#">AUD</a>
                                                    <a class="dropdown-item" href="#">CUD</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="far fa-globe-americas"></i> EN
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#">EN</a>
                                                    <a class="dropdown-item" href="#">FR</a>
                                                    <a class="dropdown-item" href="#">DE</a>
                                                    <a class="dropdown-item" href="#">RU</a>
                                                </div>
                                            </div>
                                        </li>
                                    -->
                                        <li class="social">
                                            <div class="header-top-social">
                                                <span>Социјални мрежи: </span>
                                                <a href="#"><i class="fab fa-facebook"></i></a>
                                                <a href="https://www.instagram.com/hederafarmplus/"><i class="fab fa-instagram"></i></a>
                                                <a href="#"><i class="fab fa-linkedin d-none"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header top end -->

            <!-- header middle -->
            <div class="header-middle">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-5 col-lg-3 col-xl-3">
                            <div class="header-middle-logo">
                                <a class="navbar-brand" href="/">
                                    <img src="/assets/img/logo/logo.png" alt="logo" width="150" height="50">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-5 d-none d-lg-block">
                            <div class="header-middle-search position-relative">
                                <form @submit.prevent="submitSearch">
                                    <div class="search-content">
                                        <select class="select" v-model="selectedCategory">
                                            <option value="">Сите Категории</option>
                                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.translated }}</option>
                                        </select>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            placeholder="Пребарај производи..."
                                            v-model="searchQuery"
                                            @input="handleSearch"
                                            @focus="handleSearch"
                                            @blur="hideSearchResults"
                                        >
                                        <button type="submit" class="search-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <path d="m21 21-4.35-4.35"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                                <!-- Search Results Dropdown -->
                                <div v-if="showSearchResults && filteredSearchResults.length > 0" class="search-results-dropdown">
                                    <ul>
                                        <li v-for="product in filteredSearchResults" :key="product.id" @mousedown="goToProduct(product.id)">
                                            <div class="search-result-item">
                                                <img :src="product.main_image" :alt="product.name" width="50" height="50">
                                                <div class="search-result-info">
                                                    <h6>{{ product.name }}</h6>
                                                    <span class="price">{{ product.disscount ? (product.price - (product.price * product.disscount / 100)).toFixed(0) : product.price }} ден</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <a v-if="filteredSearchResults.length >= 8" href="#" @mousedown.prevent="submitSearch" class="view-all-results">
                                        Прикажи ги сите резултати
                                    </a>
                                </div>
                                <div v-else-if="showSearchResults && searchQuery.length >= 2" class="search-results-dropdown">
                                    <p class="no-results">Нема пронајдени производи</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-7 col-lg-3 col-xl-4">
                            <div class="header-middle-right">
                                <ul class="header-middle-list">
                                    <li class="d-none">
                                        <a href="#" class="list-item">
                                            <div class="list-item-icon">
                                                <i class="far fa-user-circle"></i>
                                            </div>
                                            <div class="list-item-info">
                                                <h6>Моја Сметка</h6>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-cart">
                                        <a href="/cart-preview" class="shop-cart list-item">
                                            <div class="list-item-icon">
                                                <i class="fa fa-shopping-bag"></i>
                                                <span>{{ cart && cart.items ? cart.items.reduce((total, item) => total + item.quantity, 0) : 0 }}</span>
                                            </div>
                                            <div class="list-item-info">
                                                <h6 class="pl-2">{{totalPrice}} ден</h6>
                                                <h5 class="pl-2">Кошничка</h5>
                                            </div>
                                        </a>
                                        <div class="dropdown-cart-menu">
                                            <div class="dropdown-cart-header">
                                                <span>{{ cart && cart.items ? cart.items.reduce((total, item) => total + item.quantity, 0) : 0 }} продукти</span>  <a href="/cart-preview">Преглед</a>
                                            </div>
                                            <ul class="dropdown-cart-list cart-list">
                                                <li v-for="(item,index) in cart.items" :key="index">
                                                    <div class="dropdown-cart-item">
                                                        <div class="cart-img">
                                                            <a href=""><img :src="item.img || item.main_image" alt="#"></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h4><a href="#">{{item.title ?? item.name}}</a></h4>
                                                            <p class="cart-qty">{{item.quantity}} X <span
                                                                    class="cart-amount">{{item.disscount ? (item.price - (item.price * item.disscount / 100)).toFixed(2) : item.price}} ден</span></p>
                                                        </div>
                                                        <a  @click="removeFromCart(item.id)" class="cart-remove" title="Remove this item"><i
                                                                class="far fa-times-circle"></i></a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="dropdown-cart-bottom">
                                                <div class="dropdown-cart-total">
                                                    <span>Тотал</span>
                                                    <span class="total-amount">{{totalPrice}}</span>
                                                </div>
                                                <a href="/cart-preview" class="theme-btn">Вашата Кошничка</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header middle end -->

            <!-- navbar -->
            <div class="main-navigation">
                <nav class="navbar navbar-expand-lg">
                    <div class="container position-relative">
                        <a class="navbar-brand" href="/">
                            <img src="/assets/img/logo/logo.png" class="logo-scrolled" alt="logo" width="150" height="50">
                        </a>
                        <div class="category-all" @mouseenter="showCategoriesOnHover" @mouseleave="hideCategoriesOnLeave">
                            <button class="category-btn" type="button">
                                <i class="fas fa-list-ul"></i><span>Категории</span>
                            </button>
                            <ul v-if="showCategories" class="main-category d-block">
                                <li v-for="(category, index) in categories" :key="category.id">
                                    <a :href="'/store?category=' + category.id">
                                        <i :class="'fas ' + (category.icon || 'fa-cube')" style="width: 24px; text-align: center; margin-right: 8px; color: #0cb8b6;"></i>
                                        <span>{{ category.translated }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="mobile-menu-right">
                            <div class="mobile-menu-btn">
                                <a href="/cart-preview" class="nav-right-link"><i
                                        class="fa fa-shopping-bag"></i><span>{{cart && cart.items ? cart.items.reduce((total, item) => total + item.quantity, 0) : 0}}</span></a>
                            </div>
                            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                                aria-label="Toggle navigation">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                            aria-labelledby="offcanvasNavbarLabel">
                            <div class="offcanvas-header">
                                <a href="/" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                    <img src="/assets/img/logo/logo.png" alt="logo" width="150" height="50">
                                </a>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav justify-content-end flex-grow-1">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link " href="/">Почетна</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link " href="/store">Онлајн Продавница</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="/about">За Нас</a></li>

                                    <li class="nav-item ">
                                        <a class="nav-link " href="/blogs" >Блог</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="/contact">Контакт</a></li>
                                </ul>
                                <!-- nav-right

                                <div class="nav-right">
                                    <a class="nav-right-link" href="#"><i class="fal fa-star"></i> Recently Viewed</a>
                                    <a class="nav-right-link" href="track-order.html"><i class="fal fa-truck-fast"></i> Track My Order</a>
                                </div>
                                 -->
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- navbar end -->

        </header>
        <!-- header area end -->


</template>


<style scoped>

.cart-list{
    max-height: 300px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #ccc transparent;
}
.cart-list::-webkit-scrollbar {
    width: 5px;
}

.cart-list::-webkit-scrollbar-track {
    background: transparent;
}

.cart-list::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}
.category-all {
    position: relative;
    z-index: 9999;
  }
  .category-all {
    position: relative;
    z-index: 100;
  }

  .main-category {
    position: absolute;
    top: 55px;
    left: 0;
    z-index: 9999;
    background: white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    padding: 10px;
    border-radius: 10px;
  }
</style>
