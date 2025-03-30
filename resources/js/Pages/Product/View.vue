<script setup>
import {ref, onMounted} from 'vue';
import {Head} from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
})
const product = ref({})
const related_products = ref({})
const loading = ref(false);
onMounted(async () => {
    loading.value = true
    product.value = props.data
    if (product.value.product_category_id) {
    const allRelated = await fetchProduct(product.value.product_category_id);
    if (allRelated.length >= 4) {
        related_products.value = allRelated.slice(0, 4);
    } else {
        related_products.value = [];
    }
}
    loading.value = false
});


async function fetchProduct(id){
    try{
        const reponse = await fetch('/products/categories/' + id + '/fetch')
        if(!reponse.ok){
            throw new Error('An error occurred')
        }
        const data = await reponse.json()
        if(data.success){
            loading.value = false
            return data.data
        }else{
        loading.value = false
        return []
        }
    }catch(error){
        console.log(error)
        return []
    }
}
</script>

<template>
<Head title="Single Product" />
    <!-- preloader -->
    <div class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->
    <Header />
    <main class="main ">

        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">{{product.name}}</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">{{product.name}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->
        <div class="shop-single py-90">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-6 col-xxl-5">
                        <div class="shop-single-gallery">

                            <div class="flexslider-thumbnails">
                                <ul class="slides">
                                    <li rel="adjustX:10, adjustY:">
                                        <img :src="product.main_image" alt="#">
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-6">
                        <div class="shop-single-info">
                            <h4 class="shop-single-title">{{product.name}}</h4>
                            <div class="shop-single-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="shop-single-price">
                                <del v-if="product.disscount">{{product.price * product.disscount}}</del>
                                <span class="amount">{{product.price}}</span>
                                <span v-if="product.disscount" class="discount-percentage">{{product.disscount}}% Off</span>
                            </div>
                            <p class="mb-3">
                                {{product.short_description}}
                            </p>
                            <div class="shop-single-cs">
                                <div class="row">
                                    <div class="col-md-3 col-lg-4 col-xl-3">
                                        <div class="shop-single-size">
                                            <h6>Количина</h6>
                                            <div class="shop-cart-qty">
                                                <button class="minus-btn"><i class="fal fa-minus"></i></button>
                                                <input class="quantity" type="text" value="1" disabled="">
                                                <button class="plus-btn"><i class="fal fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shop-single-sortinfo">
                                <ul>
                                    <li>Достапна количина: <span>{{product.quantity}}</span></li>
                                    <li v-if="product.category">Категорија: <span>{{product.category.translated}}</span></li>
                                    <li>Бренд: <a href="#">Novak</a></li>
                                </ul>
                            </div>
                            <div class="shop-single-action">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="shop-single-btn">
                                            <a href="#" class="theme-btn"><span class="fa fa-shopping-bag"></span>Додади во кошничка</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- shop single details -->
                <div class="shop-single-details">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">Опис</button>
                            <button class="nav-link" id="nav-tab2" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">Дополнителни информации</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                            <div class="shop-single-desc">
                                <div v-html="product.description"></div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="nav-tab2">
                            <div class="shop-single-additional">
                                <p> Нема пронајдено дополнителни информации.</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
                            <div class="shop-single-review">
                                <div class="blog-comments">
                                    <h5>Reviews (05)</h5>
                                    <div class="blog-comments-wrap">
                                        <div class="blog-comments-item mt-0">
                                            <img src="assets/img/blog/com-1.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Sinkler Denola</h5>
                                                <span><i class="far fa-clock"></i> August 20, 2024</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-comments-item ms-md-5">
                                            <img src="assets/img/blog/com-2.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Daniel Wellman</h5>
                                                <span><i class="far fa-clock"></i> August 20, 2024</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blog-comments-item">
                                            <img src="assets/img/blog/com-3.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Kenneth Evans</h5>
                                                <span><i class="far fa-clock"></i> August 20, 2024</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-comments-form">
                                        <h4 class="mb-4">Leave A Review</h4>
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Your Name*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" placeholder="Your Email*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Your Subject*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select class="form-control form-select">
                                                            <option value="">Your Rating</option>
                                                            <option value="5">5 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                            <option value="2">2 Stars</option>
                                                            <option value="1">1 Star</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="5" placeholder="Your Review*"></textarea>
                                                    </div>
                                                    <button type="submit" class="theme-btn"><span class="far fa-paper-plane"></span> Submit Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- shop single details end -->

                <!-- related item -->
                <div class="product-area related-item pt-10">
                    <div class="container px-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="site-heading-inline">
                                    <h2 class="site-title">Иста категорија на производи</h2>
                                </div>
                            </div>
                        </div>
                        <div v-if="related_products.length > 0" class="row g-4 item-2">
                            <div v-for="(product,index) in related_products" class="col-md-6 col-lg-3">
                                <div  class="product-item bg-white">
                                    <div class="product-img">
                                        <a :href="'/products/' + product.id + '/view/'"><img :src="product.main_image" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a :href="'/products/' + product.id + '/view/'" data-tooltip="tooltip" title="View"><i class="far fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a :href="'/products/' + product.id + '/view/'">{{product.name}}</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>{{product.price}} ден</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Додај во кошничка">
                                                <i class="fa fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- related item end -->
            </div>
        </div>
        <!-- shop single end -->
    </main>

    <Footer />

</template>


<script setup>

</script>
