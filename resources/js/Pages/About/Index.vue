<script setup>
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted, ref } from 'vue';

const company = ref([])

onMounted(async()=> {
    company.value = await fetchCompanyInfo()
})

/* Fetch Company Info */
async function fetchCompanyInfo(){
    try{
        const response = await fetch('/company/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            return data.data
        }
        return []
    } catch(error){
        console.log(error);
        return []
    }
}

</script>

<template>
        <Head>
        <title>За Нас - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Дознајте повеќе за Хедра Фарм - вашата доверлива аптека во Струмица со долгогодишно искуство. Аптека Струмица која се грижи за здравјето на сите генерации со квалитетни производи." />
        <meta name="keywords" content="за нас, Хедра Фарм, аптека Струмица, аптека во Струмица, здравствена грижа, искуство, професионална услуга, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="За Нас - Аптека Струмица | Хедра Фарм" />
        <meta property="og:description" content="Дознајте повеќе за Хедра Фарм - вашата доверлива аптека во Струмица со долгогодишно искуство." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hedrafarm.mk/about" />
        <meta property="og:image" content="/assets/img/about/hedra_about_1.png" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hedrafarm.mk/about" />
    </Head>
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

        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg);"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">За Нас</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Почетна</a></li>
                        <li class="active"> За Нас</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- about area -->
        <div class="about-area py-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <div class="row">
                                    <div class="col-7">
                                        <img class="img-1" src="/assets/img/about/hedra_about_1.png" alt="About Image">
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img class="img-2" src="/assets/img/about/hedra_about_2.png" alt="About Second Image">
                                    </div>
                                </div>
                            </div>
                            <div class="about-experience">
                                <div class="about-experience-icon">
                                    <img src="assets/img/icon/experience.svg" alt="">
                                </div>
                                <b>Докажано <br> Искуство</b>
                            </div>
                            <div class="about-shape">
                                <img src="assets/img/shape/01.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                            <div class="site-heading mb-3">
                                <span class="site-title-tagline justify-content-start">
                                    <i class="flaticon-drive"></i> За нас
                                </span>
                                <h2 class="site-title">
                                   Ние се грижиме за <span>СИТЕ</span> генерации
                                </h2>
                            </div>
                            <div v-html="company.about">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- about area end -->


        <!-- counter area -->
        <div class="counter-area pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon d-flex justify-content-center">
                                <img src="assets/img/icon/sale.svg" alt="">
                            </div>
                            <div class="counter-info">
                                <div class="counter-amount">
                                    <span class="counter" data-count="+" data-to="50" data-speed="3000">50000</span>
                                </div>
                                <h6 class="title">Продадени производи </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon d-flex justify-content-center">
                                <img src="assets/img/icon/rate.svg" alt="">
                            </div>
                            <div class="counter-info">
                                <div class="counter-amount">
                                    <span class="counter" data-count="+" data-to="90" data-speed="3000">5000</span>
                                </div>
                                <h6 class="title">Задоволни Клиенти</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon d-flex justify-content-center">
                                <img src="assets/img/icon/employee.svg" alt="">
                            </div>
                            <div class="counter-info">
                                <div class="counter-amount">
                                    <span class="counter" data-count="+" data-to="150" data-speed="3000">5</span>
                                    <span class="counter-sign">+</span>
                                </div>
                                <h6 class="title">Вработени</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon d-flex justify-content-center">
                                <img src="assets/img/icon/award.svg" alt="">
                            </div>
                            <div class="counter-info">
                                <div class="counter-amount">
                                    <span class="counter" data-count="+" data-to="30" data-speed="3000">5</span>
                                    <span class="counter-sign">+</span>
                                </div>
                                <h6 class="title">Освоени Награди</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter area end -->

    </main>

    <Footer/>

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="fa-solid fa-arrow-up-wide-short text-white"></i></a>
    <!-- scroll-top end -->
        </div>
</template>

<style scoped>

</style>
