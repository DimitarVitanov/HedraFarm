<script setup>
import { ref, onMounted,computed,watch, nextTick } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const { url, props } = usePage();
const route = new URL(url, window.location.origin);
const selectedCategoryParam = route.searchParams.get('category');
const loading = ref(false)
const products = ref([])
const categories = ref([])
const subcategories = ref([])
const selectedCategories = ref([]);
const selectedSubcategories = ref([]);
const currentPage = ref(1);
const itemsPerPage = 12;
onMounted(async()=>{
    loading.value = true
    await fetchProducts()
    await fetchCategories()
    await fetchSubcategories()

    if (selectedCategoryParam) {
    selectedCategories.value = [parseInt(selectedCategoryParam)]
    }

    loading.value = false
})

async function fetchProducts(){
    try{
        const response = await fetch('/products/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json()
        if(data.success){
            products.value = data.data
        }
    } catch(error){
        console.log(error)
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
            categories.value = data.data
        }
    } catch(error){
        console.log(error)
    }
}

async function fetchSubcategories(){
    try{
        const response = await fetch('/product-subcategories/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json()
        if(data.success){
            subcategories.value = data.data
        }
    } catch(error){
        console.log(error)
    }
}


/* Search Functionallity */
const searchQuery = ref('');

const filteredProducts = computed(() => {
    let result = products.value;

    const query = searchQuery.value.trim().toLowerCase();

    // Filter by search
    if (query) {
        result = result.filter(product => {
            return (
                product.name.toLowerCase().includes(query) ||
                String(product.price).includes(query)
            );
        });
    }

    // Filter by selected categories
    if (selectedCategories.value.length > 0) {
        result = result.filter(product =>
            selectedCategories.value.includes(product.product_category_id)
        );
    }

    // Filter by selected subcategories
    if (selectedSubcategories.value.length > 0) {
        result = result.filter(product =>
            product.subcategories.some(sub =>
                selectedSubcategories.value.includes(sub.id)
            )
        );
    }

    return result;
});

watch([selectedCategories, selectedSubcategories], () => {
  currentPage.value = 1;
});

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredProducts.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredProducts.value.length / itemsPerPage);
});

watch(searchQuery, () => {
  currentPage.value = 1;
});
</script>

<template>
<Head title="Online Store" />
    <!-- preloader -->
    <div class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->

    <!-- header -->
    <Header />
    <!-- header end -->

    <main class="main">

        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Онлајн Продавница</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Онлајн Продавница</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop-area -->
        <div class="shop-area2 py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-9">
                        <div class="shop-item-wrap item-2">
                            <div class="row g-4">
                                <div v-for="(product,index) in paginatedProducts " class="col-6 col-md-4 col-lg-3">
                                    <div class="product-item bg-white">
                                        <div class="product-img">
                                            <span v-if="product.disscount" class="type">На попуст</span>
                                            <a :href="'/products/' + product.id + '/view'"><img :src="product.main_image" alt=""></a>
                                            <div class="product-action-wrap">
                                                <div class="product-action">
                                                    <a :href="'/products/' + product.id + '/view'" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="product-title"><a :href="'/products/' + product.id + '/view'">{{product.name}}</a></h3>
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
                                                <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                    <i class="fa fa-shopping-bag"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- pagination -->
                        <div class="pagination-area mt-50">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                      <a class="page-link" href="#" @click.prevent="currentPage > 1 && currentPage--" aria-label="Previous">
                                        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
                                      </a>
                                    </li>

                                    <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                                      <a class="page-link" href="#" @click.prevent="currentPage = page">{{ page }}</a>
                                    </li>

                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                      <a class="page-link" href="#" @click.prevent="currentPage < totalPages && currentPage++" aria-label="Next">
                                        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
                                      </a>
                                    </li>
                                  </ul>
                            </div>
                        </div>
                        <!-- pagination end -->
                    </div>
                    <div class="col-lg-3">
                        <div class="shop-sidebar">
                            <div class="shop-widget">
                                <div class="shop-search-form">
                                    <h4 class="shop-widget-title">Пребарувач</h4>
                                    <form action="#">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Пребарувач" v-model="searchQuery">
                                            <button type="search"><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Категории</h4>
                                <ul class="shop-checkbox-list" >
                                    <li v-for="(category,index) in categories">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                :id="category.name"
                                                :value="category.id"
                                                v-model="selectedCategories"
                                                >
                                            <label class="form-check-label" for="brand1">{{category.translated}}<span>(12)</span></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="shop-widget subcategories">
                                <h4 class="shop-widget-title">Намена</h4>
                                <ul class="shop-checkbox-list" >
                                    <li v-for="(subcategory,index) in subcategories">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                :id="subcategory.name"
                                                :value="subcategory.id"
                                                v-model="selectedSubcategories"
                                                >
                                            <label class="form-check-label" for="brand1">{{subcategory.translated}}<span>(12)</span></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- shop-area end -->

    </main>

    <!-- footer -->
    <Footer/>


</template>


<style scoped>
.subcategories{
    max-height: 40rem;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #ccc transparent;
}
.subcategories::-webkit-scrollbar {
    width: 5px;
}

.subcategories::-webkit-scrollbar-track {
    background: transparent;
}

.subcategories::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}

</style>
