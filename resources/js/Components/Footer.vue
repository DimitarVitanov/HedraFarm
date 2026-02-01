<script setup>
import {ref, onMounted} from 'vue'

const company = ref({})
const categories = ref([])

onMounted(async () => {
    await fetchCompany()
    await fetchCategories()
})

async function fetchCompany(){
    try{
        const reponse = await fetch('/company/fetch')
        if(!reponse.ok){
            throw new Error('Network response was not ok')
        }
        const data = await reponse.json()
        company.value = data.data
    }catch(error){
        console.log(error)
        return null
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

</script>

<template>
    <!-- footer area -->
    <footer class="footer-area ft-bg">
        <div class="footer-widget">
            <div class="container">
                <div class="row footer-widget-wrapper pt-100 pb-40">
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box about-us">
                            <a href="/" class="footer-logo">
                                <img src="/assets/img/logo/logo-without-bg.png" alt="">
                            </a>

                            <ul class="footer-contact">
                                <li><a :href="'tel:' + company.phone"><i class="fa fa-phone"></i>{{company.phone}}</a></li>
                                <li><i class="fa fa-map-marker-alt"></i>{{company.address}}</li>
                                <li><a :href="'mailto:' + company.email"><i
                                            class="far fa-envelope"></i>{{company.email}}</a></li>
                                <li><i class="far fa-clock"></i>Пон-Саб (10:00 - 22:00)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Линкови</h4>
                            <ul class="footer-list">
                                <li><a href="/">Почетна</a></li>
                                <li><a href="/store">Онлајн Продавница</a></li>
                                <li><a href="/about">За Нас</a></li>
                                <li><a href="/blogs">Блог</a></li>
                                <li><a href="/contact">Контакт</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Категории</h4>
                            <ul class="footer-list" v-for="(category,index) in categories">
                                <li ><a href="/store">{{category.translated}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Центар за поддршка</h4>
                            <ul class="footer-list">
                                <li><a href="/contact">Често поставувани прашања</a></li>
                                <li><a href="/contact">Како да направите нарачка</a></li>
                                <li><a href="/contact">Контакт</a></li>
                                <li><a href="/privacy">Политика на приватност</a></li>
                                <li><a href="/delivery-policy">Политика на достава</a></li>
                            </ul>
                        </div>
                    </div>
                    </div>
                    </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="copyright-wrap">
                    <div class="row">
                        <div class="col-12 col-lg-6 align-self-center">
                            <p class="copyright-text">
                                &copy; Copyright <span id="date"></span> <a href="/"> Хедерафарм+ </a> All Rights Reserved.
                            </p>
                        </div>
                        <div class="col-12 col-lg-6 align-self-center">
                            <div class="footer-social">
                                <span>Социјални Мрежи:</span>
                                <a href="javascript:void(0)"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com/hederafarmplus/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                                <a class="d-none" href="javascript:void(0)"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->

</template>
