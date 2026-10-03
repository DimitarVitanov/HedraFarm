<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useShop, toast } from '@/utils/useShop';

const { company, loadCompany } = useShop()
const sending = ref(false)

const contact_form = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
})

onMounted(loadCompany)

const sendContantEmail = async () => {
    if (sending.value) return
    try{
        sending.value = true
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
            toast(data.message, true)
            contact_form.value.name = ''
            contact_form.value.email = ''
            contact_form.value.subject = ''
            contact_form.value.message = ''
        }else{
            toast(data.message)
        }
    }
    catch(error){
        console.log(error)
    }
    finally{
        sending.value = false
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

    <StoreLayout>
        <PageHero title="Контакт">
            <p>За било каква информација, слободно контактирајте нè - тука сме да помогнеме.</p>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container hf-contact">
                <div class="hf-infos">
                    <div class="hf-info">
                        <span class="hf-info__icon"><Icon name="pin" /></span>
                        <h4>Адреса</h4>
                        <p>{{ company.address }}</p>
                    </div>
                    <div class="hf-info">
                        <span class="hf-info__icon"><Icon name="phone" /></span>
                        <h4>Телефон</h4>
                        <a :href="'tel:' + company.phone">{{ company.phone }}</a>
                    </div>
                    <div class="hf-info">
                        <span class="hf-info__icon"><Icon name="mail" /></span>
                        <h4>Емаил</h4>
                        <a :href="'mailto:' + company.email">{{ company.email }}</a>
                        <a href="mailto:support@hedrafarm.mk">support@hedrafarm.mk</a>
                    </div>
                    <div class="hf-info">
                        <span class="hf-info__icon"><Icon name="clock" /></span>
                        <h4>Работно време</h4>
                        <p>Пон - Пет (07:30 - 22:00)</p>
                        <p>Сабота (07:30 - 21:00)</p>
                        <p>Недела - <em>Затворено</em></p>
                    </div>
                </div>

                <div class="hf-panel">
                    <span class="hf-eyebrow">Пишете ни</span>
                    <h2 class="hf-title">Контактирајте нè</h2>
                    <p style="margin: 0.4rem 0 1.5rem">За било каква информација, слободно пополнете ја нашата контакт форма.</p>
                    <form class="hf-form-grid" @submit.prevent="sendContantEmail">
                        <div class="hf-field">
                            <label for="ct-name">Вашето име</label>
                            <input id="ct-name" v-model="contact_form.name" type="text" class="hf-input" name="name" placeholder="Име и презиме" required />
                        </div>
                        <div class="hf-field">
                            <label for="ct-email">Вашиот е-маил</label>
                            <input id="ct-email" v-model="contact_form.email" type="email" class="hf-input" name="email" placeholder="ime@primer.com" required />
                        </div>
                        <div class="hf-field hf-col-2">
                            <label for="ct-subject">Предмет</label>
                            <input id="ct-subject" v-model="contact_form.subject" type="text" class="hf-input" name="subject" placeholder="Предмет" required />
                        </div>
                        <div class="hf-field hf-col-2">
                            <label for="ct-message">Порака</label>
                            <textarea id="ct-message" v-model="contact_form.message" name="message" rows="5" class="hf-textarea" placeholder="Напишете ја вашата порака" required></textarea>
                        </div>
                        <div class="hf-col-2">
                            <button type="submit" class="hf-btn hf-btn--lg" :disabled="sending">
                                {{ sending ? 'Се испраќа...' : 'Испрати порака' }} <Icon name="send" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="hf-section hf-section--flush-top">
            <div class="hf-container">
                <div class="hf-section-head">
                    <div>
                        <span class="hf-eyebrow">Посетете нè</span>
                        <h2 class="hf-title">Нашата локација</h2>
                    </div>
                </div>
                <div class="hf-map">
                    <iframe
                        title="Мапа - Хедерафарм+"
                        src="https://maps.google.com/maps?q=%D0%A5%D0%B5%D0%B4%D0%B5%D1%80%D0%B0+%D0%A4%D0%B0%D1%80%D0%BC+%D0%9F%D0%BB%D1%83%D1%81+%D0%91%D0%B0%D0%BB%D0%BA%D0%B0%D0%BD%D1%81%D0%BA%D0%B0+%D0%B1%D1%804+%D0%A1%D1%82%D1%80%D1%83%D0%BC%D0%B8%D1%86%D0%B0&t=&z=17&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
