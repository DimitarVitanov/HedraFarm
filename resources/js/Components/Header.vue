<script setup>
import {onMounted, ref } from 'vue';

const company = ref([]);
const showCategories = ref(false);
const categories = ref([])


onMounted(async () => {
    company.value = await fetchCompanyInfo();
    categories.value = await fetchCategories();
    console.log(categories.value)
});


const getCategoryImage = (category) => {
    switch(category){
        case 'symptomps_conditions':
            return '/assets/img/icon/medicine.svg'
        case 'vitamins_dietry_supplements':
            return '/assets/img/icon/supplements.svg'
        case 'gels_creams':
            return '/assets/img/icon/beauty-care.svg'
        case 'children_remedies':
            return '/assets/img/icon/baby-mom-care.svg'
        case 'homeopathic_remedies':
            return '/assets/img/icon/medicine.svg'
        case 'baby_food':
            return '/assets/img/icon/food-nutrition.svg'
        case 'baby_equipment':
            return '/assets/img/icon/medical-equipements.svg'
        case 'cosmetics':
            return '/assets/img/icon/beauty-care.svg'
        case 'oral_health':
            return '/assets/img/icon/health-care.svg'
        case 'meedical_devices':
            return '/assets/img/icon/medical-equipements.svg'
    }
};
const getCategoryName = (name) => {
    switch(name){
        case 'symptomps_conditions':
            return 'Симптоми и состојби'
        case 'vitamins_dietry_supplements':
            return 'Витамини и додатоци во исхрана'
        case 'gels_creams':
            return 'Гелови и креми'
        case 'children_remedies':
            return 'Препарати за деца'
        case 'homeopathic_remedies':
            return 'Хомеопатски препарати'
        case 'baby_food':
            return 'Храна за деца'
        case 'baby_equipment':
            return 'Опрема за деца'
        case 'cosmetics':
            return 'Козметика'
        case 'oral_health':
            return 'Орално здравје'
        case 'meedical_devices':
            return 'Медицински уреди'

    }
};

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
                                        <li class="help"><a href="#"><i class="fa fa-comment"></i> Ви треба помош?</a></li>
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
                                                <a href="#"><i class="fab fa-instagram"></i></a>
                                                <a href="#"><i class="fab fa-linkedin"></i></a>
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
                                <a class="navbar-brand" href="index.html">
                                    <img src="/assets/img/logo/logo.png" alt="logo">
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
                                    <li>
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
                                        <a href="#" class="shop-cart list-item">
                                            <div class="list-item-icon">
                                                <i class="fa fa-shopping-bag"></i><span>5</span>
                                            </div>
                                            <div class="list-item-info">
                                                <h6>350.00ден</h6>
                                                <h5>Кошничка</h5>
                                            </div>
                                        </a>
                                        <div class="dropdown-cart-menu">
                                            <div class="dropdown-cart-header">
                                                <span>03 Items</span>
                                                <a href="#">View Cart</a>
                                            </div>
                                            <ul class="dropdown-cart-list">
                                                <li>
                                                    <div class="dropdown-cart-item">
                                                        <div class="cart-img">
                                                            <a href="#"><img src="/assets/img/product/01.png" alt="#"></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h4><a href="#">Surgical Face Mask</a></h4>
                                                            <p class="cart-qty">1x - <span
                                                                    class="cart-amount">$200.00</span></p>
                                                        </div>
                                                        <a href="#" class="cart-remove" title="Remove this item"><i
                                                                class="far fa-times-circle"></i></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dropdown-cart-item">
                                                        <div class="cart-img">
                                                            <a href="#"><img src="/assets/img/product/02.png" alt="#"></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h4><a href="#">Surgical Face Mask</a></h4>
                                                            <p class="cart-qty">1x - <span
                                                                    class="cart-amount">$120.00</span></p>
                                                        </div>
                                                        <a href="#" class="cart-remove" title="Remove this item"><i
                                                                class="far fa-times-circle"></i></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dropdown-cart-item">
                                                        <div class="cart-img">
                                                            <a href="#"><img src="/assets/img/product/03.png" alt="#"></a>
                                                        </div>
                                                        <div class="cart-info">
                                                            <h4><a href="#">Surgical Face Mask</a></h4>
                                                            <p class="cart-qty">1x - <span
                                                                    class="cart-amount">$330.00</span></p>
                                                        </div>
                                                        <a href="#" class="cart-remove" title="Remove this item"><i
                                                                class="far fa-times-circle"></i></a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="dropdown-cart-bottom">
                                                <div class="dropdown-cart-total">
                                                    <span>Total</span>
                                                    <span class="total-amount">$650.00</span>
                                                </div>
                                                <a href="#" class="theme-btn">Checkout</a>
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
                        <a class="navbar-brand" href="index.html">
                            <img src="/assets/img/logo/logo.png" class="logo-scrolled" alt="logo">
                        </a>
                        <div class="category-all">
                            <button @click="toggleCategories()" class="category-btn" type="button">
                                <i class="fas fa-list-ul"></i><span>Категории</span>
                            </button>
                            <ul  v-if="showCategories" class="main-category d-block">
                                <li v-for="(category,index) in categories"><a href="#"><img :src="getCategoryImage(category.name)" alt=""><span>{{getCategoryName(category.name)}}</span></a></li>
                            </ul>
                        </div>
                        <div class="mobile-menu-right">
                            <div class="mobile-menu-btn">
                                <a href="#" class="nav-right-link search-box-outer"><i class="far fa-search"></i></a>
                                <a href="wishlist.html" class="nav-right-link"><i
                                        class="far fa-heart"></i><span>2</span></a>
                                <a href="shop-cart.html" class="nav-right-link"><i
                                        class="far fa-shopping-bag"></i><span>5</span></a>
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
                                <a href="index.html" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                    <img src="/assets/img/logo/logo.png" alt="">
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
                                        <a class="nav-link " href="#" data-bs-toggle="dropdown">Онлине Продавница</a>
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

</style>
