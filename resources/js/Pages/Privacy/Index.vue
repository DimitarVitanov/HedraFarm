<script setup>
import {ref, onMounted} from 'vue'
import {Head} from  '@inertiajs/vue3';
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'

const company = ref({})
const loading = ref(false)
const categories = ref([])

onMounted(async () => {
    loading.value = true
    company.value = await fetchCompany()
    await fetchCategories()
    loading.value = false
})

async function fetchCompany(){
    try{
        const reponse = await fetch('/company/fetch')
        if(!reponse.ok){
            throw new Error('Network response was not ok')
        }
        const data = await reponse.json()
        return data.data
    }catch(error){
        console.log(error)
        return null
    }
}
</script>

<template>
    <Head>
        <title>Политика на Приватност - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Политика на приватност на Аптека Струмица - Хедра Фарм. Дознајте како ги заштитуваме вашите лични податоци." />
        <meta name="keywords" content="политика на приватност, аптека Струмица, лични податоци, заштита на податоци, Хедра Фарм, GDPR, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Политика на Приватност - Аптека Струмица" />
        <meta property="og:description" content="Политика на приватност на Аптека Струмица - Хедра Фарм." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hedrafarm.mk/privacy" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hedrafarm.mk/privacy" />
    </Head>
    <Header/>
    <div class="main">
     <!-- breadcrumb -->
     <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">Политика на приватност</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                    <li class="active">Политика на приватност</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="privacy-policy container py-5">
        <h2 class="mb-4">Политика на приватност</h2>

        <p>
          Оваа Политика на приватност објаснува како ги собираме, користиме и заштитуваме вашите лични податоци кога ја користите нашата веб-страница и услугите што ги нудиме преку неа.
        </p>

        <h4 class="mt-4">1. Кои податоци ги собираме?</h4>
        <ul>
          <li>Име и презиме</li>
          <li>Адреса за достава</li>
          <li>Телефонски број</li>
          <li>Е-пошта</li>
          <li>Историја на нарачки</li>
          <li>Податоци за плаќање (не се чуваат на серверот)</li>
        </ul>

        <h4 class="mt-4">2. Како ги користиме вашите податоци?</h4>
        <ul>
          <li>За обработка и достава на нарачки</li>
          <li>За контакт со вас за потврда или промена на нарачката</li>
          <li>За подобрување на веб-страницата и услугата</li>
          <li>За маркетинг цели (само ако дадете согласност)</li>
        </ul>

        <h4 class="mt-4">3. Заштита на податоци</h4>
        <p>Вашите податоци се чуваат безбедно со користење на технички и организациски мерки против неовластен пристап или злоупотреба.</p>

        <h4 class="mt-4">4. Делење на информации</h4>
        <p>Не ги споделуваме вашите податоци со трети лица, освен кога е потребно за испорака или кога тоа го бара законот.</p>

        <h4 class="mt-4">5. Вашите права</h4>
        <ul>
          <li>Пристап до лични податоци</li>
          <li>Исправка или бришење на податоци</li>
          <li>Приговор за користење на податоците</li>
          <li>Повлекување на согласност</li>
        </ul>
        <p>Контактирајте не на: <strong> {{company.email}} </strong></p>

        <h4 class="mt-4">6. Времетраење на чување на податоци</h4>
        <p>Податоците се чуваат онолку долго колку што е потребно за законски и деловни цели.</p>

        <h4 class="mt-4">7. Промени на политиката</h4>
        <p>Задржуваме право на измена на политиката. Последна измена: <strong>{{ датум }}</strong></p>
      </div>



    </div>
    <Footer/>


</template>

<style scoped>

</style>
