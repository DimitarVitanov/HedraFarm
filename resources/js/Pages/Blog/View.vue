<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});
const blog = ref({})
const loading = ref(false);

onMounted(async () => {
    loading.value = true
    blog.value = props.data
    loading.value = false
});
</script>

<template>
    <Head>
        <title>{{ blog.title }} - Хедра Фарм Блог</title>
        <meta name="description" :content="blog.short_description || 'Прочитајте ја оваа статија на Хедра Фарм блог.'" />
        <meta name="keywords" :content="'блог, ' + blog.title + ', Хедра Фарм, здравје'" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" :content="blog.title + ' - Хедра Фарм'" />
        <meta property="og:description" :content="blog.short_description || 'Прочитајте ја оваа статија на Хедра Фарм блог.'" />
        <meta property="og:type" content="article" />
        <meta property="og:image" :content="'/assets' + blog.image" />
        <meta property="og:locale" content="mk_MK" />
    </Head>
    <!-- preloader -->
    <div v-if="loading" class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->

    <Header />

    <main class="main">
        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div
                class="site-breadcrumb-bg"
                style="background: url(/assets/img/about/about_hero.jpg)"
            ></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">{{ blog.title }}</h4>
                    <ul class="breadcrumb-menu">
                        <li>
                            <a href="/"><i class="fa fa-home"></i> Дома</a>
                        </li>
                        <li class="active">{{ blog.title }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->

        <!-- blog single area -->
        <div class="blog-single-area py-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-xxl-9 mx-auto">
                        <div class="blog-single-wrap">
                            <div class="blog-single-content">
                                <div class="blog-thumb-img">
                                    <img
                                        :src="'/assets' + blog.image"
                                        alt="thumb"
                                    />
                                </div>
                                <div class="blog-info">
                                    <div class="blog-meta">
                                        <div class="blog-meta-left">
                                            <ul>
                                                <li>
                                                    <i class="far fa-user"></i
                                                    ><a href="#">{{
                                                        blog.user
                                                    }}</a>
                                                </li>
                                        </ul>
                                        </div>
                                    </div>
                                    <div class="blog-details">
                                        <h3 class="blog-details-title mb-5">
                                            {{ blog.title }}
                                        </h3>
                                        <div v-html="blog.content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <Footer/>
</template>

<style scoped>
</style>
