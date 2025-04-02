<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import HeroSlider from './Components/HeroSlider.vue';
import Category from './Components/Category.vue';
import TrendingItems from './Components/TrendingItems.vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import NewsLetter from '@/Components/NewsLetter.vue';
const page = usePage();
const logged_user = ref(page.props.user);

const loading = ref(true);
const blogs = ref([])
const company = ref({})

onMounted(async ()=>{
    loading.value = true;
    await fetchCompanyInfo();
    await fetchBlogs();
    loading.value = false;

})

async function fetchCompanyInfo(){
    try{
        const response = await fetch('/company/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            company.value = data.data
        }
    }catch(error){
        console.log(error);
    }
}

async function fetchBlogs(){
    try{
        const response = await fetch('/blogs/fetch')
        if(!response.ok){
            throw new Error('An error occurred')
        }
        const data = await response.json()
        if(data.success){
            blogs.value =  data.data.slice(0,3)
        }
    }catch(error){
        console.log(error)
    }
}
</script>

<template>
    <Head title="Home"/>
    <div>

        <!-- preloader -->
        <div v-if="loading" class="preloader">
            <div class="loader-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
        <!-- preloader end -->
        <Header/>
        <!-- mobile popup search -->
        <div class="search-popup">
            <button class="close-search"><span class="far fa-times"></span></button>
            <form action="#">
                <div class="form-group">
                    <input type="search" name="search-field" class="form-control" placeholder="Search Here..." required>
                    <button type="submit"><i class="far fa-search"></i></button>
                </div>
            </form>
        </div>
        <!-- mobile popup search end -->


        <main class="main">

            <!-- hero section -->
            <HeroSlider/>
            <!-- hero section-->


            <!-- category area -->
            <Category/>
            <!-- category area end-->


            <!-- small banner -->
            <div class="small-banner pb-100">
                <div class="container wow fadeInUp" data-wow-delay=".25s">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="banner-item">
                                <img :src="'/assets/img/banner/mini-banner-1.jpg'" alt="">
                                <div class="banner-content">
                                    <p>Sanitizer</p>
                                    <h3>Hand Sanitizer <br> Collectons</h3>
                                    <a href="#"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="banner-item">
                                <img src="/assets/img/banner/mini-banner-2.jpg" alt="">
                                <div class="banner-content">
                                    <p>Hot Sale</p>
                                    <h3>Face Wash Sale <br> Collections</h3>
                                    <a href="#">Discover Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="banner-item">
                                <img src="/assets/img/banner/mini-banner-3.jpg" alt="">
                                <div class="banner-content">
                                    <p>Facial Mask</p>
                                    <h3>Facial Mask Sale <br> Up To <span>50%</span> Off</h3>
                                    <a href="#">Discover Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- small banner end -->


            <!-- trending item -->
            <TrendingItems/>
            <!-- trending item end -->


            <!-- feature area -->
            <div class="feature-area pb-100">
                <div class="container wow fadeInUp" data-wow-delay=".25s">
                    <div class="feature-wrap">
                        <div class="row g-0">
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fal fa-truck"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h4>Бесплата Достава</h4>
                                        <p>Над 2000 денари</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fal fa-sync"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h4>Враќање на производи</h4>
                                        <p>Во рок од 15 дена</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fal fa-wallet"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h4>Плаќање</h4>
                                        <p>100% При достава</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fal fa-headset"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h4>24/7 Подршка</h4>
                                        <p>Слободно контакријате не</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- feature area end -->


            <!-- popular item -->
            <div class="product-area pb-100">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                                    <div class="site-heading-inline">
                                        <h2 class="site-title">Популарни продукти</h2>
                                        <a href="/store">Сите Продукти <i class="fas fa-angle-double-right"></i></a>
                                    </div>
                                    <div class="item-tab">
                                        <ul class="nav nav-pills mt-6 mb-20" id="item-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="item-tab1" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab1" type="button" role="tab"
                                                    aria-controls="pill-item-tab1" aria-selected="true">Medicine</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="item-tab2" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab2" type="button" role="tab"
                                                    aria-controls="pill-item-tab2" aria-selected="false">Beauty Care</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="item-tab3" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab3" type="button" role="tab"
                                                    aria-controls="pill-item-tab3" aria-selected="false">Healthcare</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="item-tab4" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab4" type="button" role="tab"
                                                    aria-controls="pill-item-tab4" aria-selected="false">Baby & Mom Care</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="item-tab5" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab5" type="button" role="tab"
                                                    aria-controls="pill-item-tab5" aria-selected="false">Food & Nutrition</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="item-tab6" data-bs-toggle="pill"
                                                    data-bs-target="#pill-item-tab6" type="button" role="tab"
                                                    aria-controls="pill-item-tab6" aria-selected="false">Medical Equipments</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content wow fadeInUp" data-wow-delay=".25s" id="item-tabContent">
                                <div class="tab-pane show active" id="pill-item-tab1" role="tabpanel" aria-labelledby="item-tab1"
                                    tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/14.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/07.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/24.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/15.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pill-item-tab2" role="tabpanel" aria-labelledby="item-tab2" tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/10.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/11.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/12.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/13.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pill-item-tab3" role="tabpanel" aria-labelledby="item-tab3" tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/17.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/18.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/19.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/20.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pill-item-tab4" role="tabpanel" aria-labelledby="item-tab4" tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/22.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/23.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/24.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/25.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pill-item-tab5" role="tabpanel" aria-labelledby="item-tab5" tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/30.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/31.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/32.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/33.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pill-item-tab6" role="tabpanel" aria-labelledby="item-tab6" tabindex="0">
                                    <div class="row g-3 item-2">
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type new">New</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/34.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type hot">Hot</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/35.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type oos">Out Of Stock</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/36.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <span>$250.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <span class="type discount">10% Off</span>
                                                    <a href="shop-single.html"><img src="/assets/img/product/37.png" alt=""></a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
                                                                data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-bs-placement="top" data-tooltip="tooltip"
                                                                title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="shop-single.html">Surgical Face Mask</a></h3>
                                                    <div class="product-rate">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            <del>$250.00</del>
                                                            <span>$190.00</span>
                                                        </div>
                                                        <button type="button" class="product-cart-btn" data-bs-placement="left"
                                                            data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
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
                                <a href="#">
                                    <img src="/assets/img/banner/product-banner.jpg" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- popular item end -->


            <!-- big banner -->
            <div class="big-banner">
                <div class="container wow fadeInUp" data-wow-delay=".25s">
                    <div class="banner-wrap" style="background-image: url(/assets/img/banner/big-banner.jpg);">
                        <div class="row">
                            <div class="col-lg-8 mx-auto">
                                <div class="banner-content">
                                    <div class="banner-info">
                                        <h6>Mega Collections</h6>
                                        <h2>Huge Sale Up To <span>40%</span> Off</h2>
                                        <p>at our outlet stores</p>
                                    </div>
                                    <a href="#" class="theme-btn">Shop Now<i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- big banner end -->


            <!-- brand area -->
            <div class="brand-area pt-10">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="site-heading-inline">
                                <h2 class="site-title">Популарни Брендови</h2>
                            </div>
                        </div>
                    </div>
                    <div class="brand-slider owl-carousel owl-theme">
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/01.png" alt="">
                            </a>
                        </div>
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/02.png" alt="">
                            </a>
                        </div>
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/03.png" alt="">
                            </a>
                        </div>
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/04.png" alt="">
                            </a>
                        </div>
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/05.png" alt="">
                            </a>
                        </div>
                        <div class="brand-item">
                            <a href="#">
                                <img src="/assets/img/brand/06.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- brand area end -->



            <!-- product list -->
            <div class="product-list py-100">
                <div class="container wow fadeInUp" data-wow-delay=".25s">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="product-list-box">
                                <h2 class="product-list-title">On sale</h2>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/01.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/02.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/03.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="product-list-box">
                                <h2 class="product-list-title">Best Seller</h2>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/04.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/05.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/06.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="product-list-box">
                                <h2 class="product-list-title">Top Rated</h2>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/07.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/08.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                                <div class="product-list-item">
                                    <div class="product-list-img">
                                        <a href="shop-single.html"><img src="/assets/img/product/09.png" alt="#"></a>
                                    </div>
                                    <div class="product-list-content">
                                        <h4><a href="shop-single.html">Surgical Face Mask</a></h4>
                                        <div class="product-list-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-list-price">
                                            <del>60.00</del><span>$40.00</span>
                                        </div>
                                    </div>
                                    <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip"
                                        title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product list end -->


            <!-- deal area -->
            <div class="deal-area pt-50 pb-50">
                <div class="deal-text-shape">Deal</div>
                <div class="container">
                    <div class="deal-wrap wow fadeInUp" data-wow-delay=".25s">
                        <div class="deal-slider owl-carousel owl-theme">
                            <div class="deal-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="deal-content">
                                            <div class="deal-info">
                                                <span>Weekly Deal</span>
                                                <h1>Best Deal For This Week</h1>
                                                <p>There are many variations of passages available but the majority have
                                                    suffered alteration in some form
                                                    by injected humour, or randomised words which don't look even slightly
                                                    believable.</p>
                                            </div>
                                            <div class="deal-countdown">
                                                <div class="countdown" data-countdown="2025/12/30"></div>
                                            </div>
                                            <a href="#" class="theme-btn theme-btn2">Shop Now <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="deal-img">
                                            <img src="/assets/img/deal/01.png" alt="">
                                            <div class="deal-discount">
                                                <span>35%</span>
                                                <span>off</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deal-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="deal-content">
                                            <div class="deal-info">
                                                <span>Weekly Deal</span>
                                                <h1>Best Deal For This Week</h1>
                                                <p>There are many variations of passages available but the majority have
                                                    suffered alteration in some form
                                                    by injected humour, or randomised words which don't look even slightly
                                                    believable.</p>
                                            </div>
                                            <div class="deal-countdown">
                                                <div class="countdown" data-countdown="2025/12/30"></div>
                                            </div>
                                            <a href="#" class="theme-btn theme-btn2">Shop Now <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="deal-img">
                                            <img src="/assets/img/deal/02.png" alt="">
                                            <div class="deal-discount">
                                                <span>35%</span>
                                                <span>off</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deal-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="deal-content">
                                            <div class="deal-info">
                                                <span>Weekly Deal</span>
                                                <h1>Best Deal For This Week</h1>
                                                <p>There are many variations of passages available but the majority have
                                                    suffered alteration in some form
                                                    by injected humour, or randomised words which don't look even slightly
                                                    believable.</p>
                                            </div>
                                            <div class="deal-countdown">
                                                <div class="countdown" data-countdown="2025/12/30"></div>
                                            </div>
                                            <a href="#" class="theme-btn theme-btn2">Shop Now <i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="deal-img">
                                            <img src="/assets/img/deal/03.png" alt="">
                                            <div class="deal-discount">
                                                <span>35%</span>
                                                <span>off</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- deal area end -->


            <!-- gallery-area -->
            <div class="gallery-area py-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="site-heading text-center">
                                <h2 class="site-title">Разгледете ја нашата<span> Галерија</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 popup-gallery">
                        <div class="col-md-12 col-lg-6">
                            <div class="gallery-item gallery-btn-active wow fadeInUp" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/01.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/01.jpg"><i
                                        class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/02.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/02.jpg"><i
                                            class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/03.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/03.jpg"><i
                                            class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/04.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/04.jpg"><i
                                            class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/05.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/05.jpg"><i
                                            class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                                <div class="gallery-img">
                                    <img src="/assets/img/gallery/06.jpg" alt="">
                                    <a class="popup-img gallery-link" href="/assets/img/gallery/06.jpg"><i
                                            class="fal fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- gallery-area end -->

            <!-- blog area -->
            <div class="blog-area py-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline">Нашиот Блог </span>
                                <h2 class="site-title">Новости & <span>Блог</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div v-for="(blog,index) in blogs" class="col-md-6 col-lg-4">
                            <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="blog-item-img">
                                    <img :src="'/assets' + blog.image" alt="Thumb">
                                    <span class="blog-date"><i class="far fa-calendar-alt"></i> {{blog.date}}</span>
                                </div>
                                <div class="blog-item-info">
                                    <div class="blog-item-meta">
                                        <ul class="ps-0 py-0 pb-0">
                                            <li><a href="#"><i class="far fa-user-circle"></i> {{blog.user}}</a></li>
                                        </ul>
                                    </div>
                                    <h4 class="blog-title">
                                        <a href="#">{{blog.title}}</a>
                                    </h4>
                                    <div v-html="blog.short_description"></div>
                                    <a class="theme-btn" :href="'/blogs/' + blog.id + '/read' ">Прочитај повеќе<i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- blog area end -->


            <!-- newsletter area -->
            <NewsLetter/>
            <!-- newsletter area end -->


            <!-- instagram-area
            <div class="instagram-area pb-100">
                <div class="container wow fadeInUp" data-wow-delay=".25s">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="site-heading text-center">
                                <h2 class="site-title">Instagram <span>@medica</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="instagram-slider owl-carousel owl-theme">
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/01.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/02.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/03.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/04.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/05.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/06.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="instagram-item">
                            <div class="instagram-img">
                                <img src="/assets/img/instagram/07.jpg" alt="Thumb">
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <!-- instagram-area end -->

        </main>


        <!-- footer area -->
        <Footer />
        <!-- footer area end -->


        <!-- scroll-top -->
        <a href="#" id="scroll-top"><i class="fa-solid fa-arrow-up-wide-short text-white"></i></a>
        <!-- scroll-top end -->


        <!-- modal quick shop-->
        <div class="modal quickview fade" id="quickview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="quickview" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-xmark"></i></button>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="quickview-img">
                                    <img src="/assets/img/product/04.png" alt="#">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="quickview-content">
                                    <h4 class="quickview-title">Surgical Face Mask</h4>
                                    <div class="quickview-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="quickview-price">
                                        <h5><del>$860</del><span>$740</span></h5>
                                    </div>
                                    <ul class="quickview-list">
                                        <li>Brand:<span>Medica</span></li>
                                        <li>Category:<span>Healthcare</span></li>
                                        <li>Stock:<span class="stock">Available</span></li>
                                        <li>Code:<span>789FGDF</span></li>
                                    </ul>
                                    <div class="quickview-cart">
                                        <a href="#" class="theme-btn">Додади во кошничка</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal quick shop end -->



    </div>

</template>

<style scoped>
.main-category {
    display: none; /* Initially hidden */
  }

.main-category[style*="display: block"] {
    display: block;
  }

.main{
    padding:0 15rem;
}
@media (max-width: 1500px) {
    .main{
        padding:0 1rem;
    }
}
header{
    overflow: hidden;
}
</style>
