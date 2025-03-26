<script setup>
import { onMounted, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const company = ref([])
const loading = ref(false)
onMounted(async()=> {
    loading.value = true
    company.value = await fetchCompanyInfo()
    loading.value = false
})

async function fetchCompanyInfo(){
    try{
        const response = await fetch('/company/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json()
        if(data.success){
            return data.data
        }
        return []
    } catch(error){
        console.log(error)
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


<!-- header -->

<Header/>

<!-- header end-->


<main class="main">

    <!-- breadcrumb -->
    <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">Контакт</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                    <li class="active">Контакт</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->


    <!-- contact area -->
    <div class="contact-area pt-100 pb-30">
        <div class="container">
            <div class="contact-wrapper">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="contact-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="contact-info">
                                        <div class="contact-info-icon">
                                            <i class="fal fa-map-location-dot"></i>
                                        </div>
                                        <div class="contact-info-content">
                                            <h5>Адреса</h5>
                                           <p>{{ company.address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-info">
                                        <div class="contact-info-icon">
                                            <i class="fal fa-headset"></i>
                                        </div>
                                        <div class="contact-info-content">
                                            <h5>Контакт</h5>
                                            <p>{{company.phone}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-info">
                                        <div class="contact-info-icon">
                                            <i class="fal fa-envelopes"></i>
                                        </div>
                                        <div class="contact-info-content">
                                            <h5>Емаил</h5>
                                            <p>{{ company.email }}</p>
                                            <p>support@hedrafarm.mk</p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-info">
                                        <div class="contact-info-icon">
                                            <i class="fal fa-alarm-clock"></i>
                                        </div>
                                        <div class="contact-info-content">
                                            <h5>Работно Време</h5>
                                            <p>Пон - Саб (10:00 - 22:00)</p>
                                            <p>Недела - <span class="text-danger">Затворено</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="contact-form">
                            <div class="contact-form-header">
                                <h2>Контактирајте не</h2>
                                <p> За било каква информациа, слободно пополнета ја нашата контакт форма. </p>
                            </div>
                            <div class="form-message"></div>
                            <form method="post" action="/medica/assets/php/contact.php" id="contact-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Вашето име" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Вашето Презиме" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject"
                                        placeholder="Предмент" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" cols="30" rows="4" class="form-control"
                                        placeholder="Напишете ја вашата порака" required></textarea>
                                </div>
                                <button type="submit" class="theme-btn">Испрати
                                    Порака <i class="far fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact area -->


    <!-- newsletter area -->
    <div class="newsletter-area pb-100">
        <div class="container wow fadeInUp" data-wow-delay=".25s">
            <div class="newsletter-wrap">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="newsletter-content">
                            <h3>Не пропуштај ниту една одлична понуда!</h3>
                            <p>Со внесување на вашата e-mail адреса се согласувате да ги добивате нашите маркетинг понуди.
                            </p>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="email" class="form-control" placeholder="Вашата е-маил адреса">
                                    <button class="theme-btn" type="submit">
                                        Претплати се <i class="far fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- newsletter area end -->


    <!-- map -->
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96708.34194156103!2d-74.03927096447748!3d40.759040329405195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4a01c8df6fb3cb8!2sSolomon%20R.%20Guggenheim%20Museum!5e0!3m2!1sen!2sbd!4v1619410634508!5m2!1sen!2s"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <!-- end map -->

    <Footer/>

</main>

</template>

<style scoped>

</style>
