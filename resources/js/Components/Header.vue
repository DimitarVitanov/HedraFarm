<script setup>
import {onMounted, ref } from 'vue';
import { useCart } from '@/utils/useCart';
const company = ref([]);
const showCategories = ref(false);
const categories = ref([])
const { cart, totalPrice, removeFromCart } = useCart()

onMounted(async () => {
    company.value = await fetchCompanyInfo();
    categories.value = await fetchCategories();
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
                        <div class="d-none  col-lg-6 col-xl-5">
                            <div class="header-middle-search">
                                <form action="#">
                                    <div class="search-content">
                                        <select class="select">
                                            <option value="">All Category</option>
                                            <option value="1">Medicine</option>
                                            <option value="2">Medical Equipments</option>
                                            <option value="3">Beauty Care</option>
                                            <option value="4">Baby & Mom Care</option>
                                            <option value="5">Healthcare</option>
                                            <option value="6">Food & Nutrition</option>
                                            <option value="7">Medical Supplies</option>
                                            <option value="8">Lab Test</option>
                                            <option value="9">Fitness</option>
                                            <option value="10">Vitamins & Supplement</option>
                                            <option value="11">Pet Care</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Search Here...">
                                        <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                                    </div>
                                </form>
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
