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
        <meta property="og:url" content="https://hedrafarm.mk/contact" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hedrafarm.mk/contact" />
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


    <!-- map
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96708.34194156103!2d-74.03927096447748!3d40.759040329405195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4a01c8df6fb3cb8!2sSolomon%20R.%20Guggenheim%20Museum!5e0!3m2!1sen!2sbd!4v1619410634508!5m2!1sen!2s"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
     end map -->

    <Footer/>

</main>

</template>

<style scoped>

</style>
