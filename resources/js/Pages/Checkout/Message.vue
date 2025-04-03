<script setup>
import Footer from '@/Components/Footer.vue';
import Header from '@/Components/Header.vue';



</script>


<template>

        <!-- preloader -->
        <div class="preloader">
            <div class="loader-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
        <!-- preloader end -->

        <Header/>

        <div class="main">
                <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Успешна нарачка</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Успешна Нарачка</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->
               <!-- shop checkout complete -->
        <div class="shop-checkout-complete py-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div class="checkout-complete-content">
                            <div class="checkout-complete-icon"><i class="fa fa-check"></i></div>
                            <h3>Ви благодариме за вашата нарачка!</h3>
                            <p>На внесенио email ќе добиете повеќе информации за вашата нарачка.</p>
                            <a href="/" class="theme-btn">Вратете се назад<i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- shop checkout complete end -->
        </div>

        <Footer/>

</template>


<style scoped>

</style>
