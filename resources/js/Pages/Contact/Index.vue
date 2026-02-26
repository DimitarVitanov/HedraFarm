<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import NewsLetter from '@/Components/NewsLetter.vue';

const company = ref([])
const loading = ref(false)

const contact_form = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
})
const fireMessage = (message, success = false, reload = false) => {
  Swal.fire({
    position: "top",
    toast: true,
    icon: success ? "success" : "error",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (reload) {
      location.reload();
    }
  });
};
onMounted(async()=> {
    loading.value = true
    company.value = await fetchCompanyInfo()
    loading.value = false
})

const sendContantEmail =async () =>{
        try{
            const formData = new FormData();
            formData.append('name', contact_form.value.name);
            formData.append('email', contact_form.value.email);
            formData.append('subject', contact_form.value.subject);
            formData.append('message', contact_form.value.message);
            const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', _token);
            const response = await fetch('/contact/support/send-email',{
                method:'POST',
                body: formData
            })
            if(!response.ok){
                throw new Error('An error occurred while sending the email')
            }
            const data = await response.json()
            if(data.status == 'success'){
                fireMessage(data.message, true, false)
                contact_form.value.name = ''
                contact_form.value.email = ''
                contact_form.value.subject = ''
                contact_form.value.message = ''
            }else{
                fireMessage(data.message, false, false)
            }
        }
        catch(error){
            console.log(error)
        }
}

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
    <Head>
        <title>Контакт - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Контактирајте ја Аптека Струмица - Хедра Фарм за било какви прашања. Работно време: Пон-Саб 10:00-22:00. Брза и професионална поддршка." />
        <meta name="keywords" content="контакт, Хедра Фарм, аптека Струмица контакт, аптека во Струмица, работно време, поддршка, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Контакт - Аптека Струмица | Хедра Фарм" />
        <meta property="og:description" content="Контактирајте ја Аптека Струмица - Хедра Фарм за било какви прашања." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk/contact" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/contact" />
    </Head>
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
                                        <p>Пон - Пет (07:30 - 22:00)</p>
                                        <p>Сабота (07:30 - 21:00)</p>
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
                            <form id="contact-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input v-model="contact_form.name" type="text" class="form-control" name="name"
                                                placeholder="Вашето име" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input v-model="contact_form.email" type="email" class="form-control" name="email"
                                                placeholder="Вашиот Е-маил" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" v-model="contact_form.subject" class="form-control" name="subject"
                                        placeholder="Предмет" required>
                                </div>
                                <div class="form-group">
                                    <textarea v-model="contact_form.message" name="message" cols="30" rows="4" class="form-control"
                                        placeholder="Напишете ја вашата порака" required></textarea>
                                </div>
                                <button @click="sendContantEmail()" class="theme-btn">Испрати
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
        <NewsLetter/>
    <!-- newsletter area end -->


    <!-- map -->
    <div class="contact-map-section pb-100">
        <div class="container">
            <div class="map-heading text-center mb-4">
                <h2>Нашата Локација</h2>
                <p>Посетете не на нашата адреса или контактирајте не онлајн</p>
            </div>
            <div class="contact-map-wrapper">
                <iframe src="https://maps.google.com/maps?q=%D0%A5%D0%B5%D0%B4%D0%B5%D1%80%D0%B0+%D0%A4%D0%B0%D1%80%D0%BC+%D0%9F%D0%BB%D1%83%D1%81+%D0%91%D0%B0%D0%BB%D0%BA%D0%B0%D0%BD%D1%81%D0%BA%D0%B0+%D0%B1%D1%804+%D0%A1%D1%82%D1%80%D1%83%D0%BC%D0%B8%D1%86%D0%B0&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <!-- end map -->

    <Footer/>

</main>

</template>

<style scoped>
.contact-map-section {
    background-color: #f9f9f9;
    padding-top: 60px;
}

.map-heading h2 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}

.map-heading p {
    color: #777;
    font-size: 15px;
}

.contact-map-wrapper {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.contact-map-wrapper iframe {
    width: 100%;
    height: 450px;
    border: 0;
    display: block;
}
</style>
