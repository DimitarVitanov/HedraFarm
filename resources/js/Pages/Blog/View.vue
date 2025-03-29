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
const loading = ref(false);

onMounted(async () => {});
</script>

<template>
    <Head title="Single Blog" />
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
                    <h4 class="breadcrumb-title">{{ props.title }}</h4>
                    <ul class="breadcrumb-menu">
                        <li>
                            <a href="/"><i class="fa fa-home"></i> Дома</a>
                        </li>
                        <li class="active">{{ data.title }}</li>
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
                                        :src="'/assets' + data.image"
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
                                                        data.user
                                                    }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="blog-details">
                                        <h3 class="blog-details-title mb-5">
                                            {{ data.title }}
                                        </h3>
                                        <div v-html="data.content"></div>

                                        <div class="row">
                                            <div class="col-md-6 mb-20">
                                                <img
                                                    src="assets/img/blog/01.jpg"
                                                    alt=""
                                                />
                                            </div>
                                            <div class="col-md-6 mb-20">
                                                <img
                                                    src="assets/img/blog/02.jpg"
                                                    alt=""
                                                />
                                            </div>
                                        </div>
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

<style scoped></style>
