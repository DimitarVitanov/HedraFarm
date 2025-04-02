<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
</script>

<template>
    <Head title="Checkout" />
    <!-- preloader -->
    <div class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->

    <Header />

    <main>
        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Наплата</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Наплата</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop checkout -->
        <div class="shop-checkout py-5 bg-white">
            <div class="container">
                <div class="shop-checkout-wrap">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="shop-checkout-step">
                                <div class="accordion" id="shopCheckout">
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#checkoutStep1" aria-expanded="true" aria-controls="checkoutStep1">
                                            Нов Корисник
                                        </button>
                                      </h2>
                                      <div id="checkoutStep1" class="accordion-collapse  show" data-bs-parent="#shopCheckout">
                                        <div class="accordion-body">
                                            <div class="shop-checkout-form">
                                                <form action="#">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Име</label>
                                                                <input type="text" class="form-control" placeholder="Име">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Презиме</label>
                                                                <input type="text" class="form-control" placeholder="Презиме">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Еmail</label>
                                                                <input type="email" class="form-control" placeholder="Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Телефон</label>
                                                                <input type="text" class="form-control" placeholder="Телефон">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Адреса</label>
                                                                <input type="text" class="form-control" placeholder="Адреса">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Град</label>
                                                                <input type="text" class="form-control" placeholder="Град">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Општина</label>
                                                                <input type="text" class="form-control" placeholder="Општина">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Поштенски Код</label>
                                                                <input type="text" class="form-control" placeholder="Поштенски код">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Држава</label>
                                                                <input type="text" class="form-control" placeholder="Северна Македонија" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Дополнителен Опис</label>
                                                                <textarea cols="30" rows="4" class="form-control" placeholder="Your Message"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="shop-cart-summary mt-0">
                                <h5>Вашата Кошничка</h5>
                                <ul>
                                    <li><strong>Вкупно</strong> <span>3000 ден</span></li>
                                    <li><strong>Попуст:</strong> <span>20 ден</span></li>
                                    <li><strong>Достава:</strong> <span>200 ден</span></li>
                                    <li class="shop-cart-total"><strong>Вкупно:</strong> <span>2800 ден</span></li>
                                </ul>
                                <div class="text-end mt-40">
                                    <a href="#" class="theme-btn">Наплати<i
                                    class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- shop checkout end -->


    </main>
    <Footer />

</template>


<style scoped>

</style>
