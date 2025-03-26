<script setup>
import { ref, onMounted } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const loading = ref(false)
const blogs = ref([])
onMounted(async () => {
    await fetchBlogs()
});

async function fetchBlogs(){
    try{
        loading.value = true
        const response = await fetch('/blogs/fetch')
        if(!response.ok){
            throw new Error('An error occurred')
        }
        const data = await response.json()
        if(data.success){
            loading.value = false
            blogs.value =  data.data
        }
    }catch(error){
        console.log(error)
        loading.value = false
        return []
    }
}

</script>

<template>
    <!-- preloader -->
    <div v-if="loading" class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->


    <Header />

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

        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Нашиот Блог</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Нашиот Блог</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- blog area -->
        <div class="blog-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Нашиот блог</span>
                            <h2 class="site-title">Новости & Совети <span>Блог</span></h2>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div v-for="(blog,index) in blogs"  class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="blog-item-img">
                                <img :src="'/assets' + blog.image" alt="Thumb">
                                <span class="blog-date"><i class="far fa-calendar-alt"></i> {{blog.date}}</span>
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta  ">
                                    <ul class="ps-0 py-0 pb-0">
                                        <li><i class="far fa-user-circle"></i> {{blog.user}}</li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">{{blog.title}}</a>
                                </h4>
                                <div v-html="blog.short_description"></div>
                                <a class="theme-btn" href="#">Прочитај повеќе<i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- blog area end -->
    </main>

   <!-- scroll-top -->
   <a href="#" id="scroll-top"><i class="fa-solid fa-arrow-up-wide-short text-white"></i></a>
   <!-- scroll-top end -->

   <Footer/>



</template>

<style scoped>

</style>
