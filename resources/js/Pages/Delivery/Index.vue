<script setup>
import {ref, onMounted} from 'vue'
import {Head} from  '@inertiajs/vue3';
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'

const company = ref({})
const loading = ref(false)

onMounted(async () => {
    loading.value = true
    company.value = await fetchCompany()
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
        <title>Политика на Достава - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Достава од Аптека Струмица - Хедра Фарм. Бесплатна достава над 2000 денари. Испорака низ цела Македонија." />
        <meta name="keywords" content="достава, аптека Струмица достава, испорака, бесплатна достава, Хедра Фарм, Македонија, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Политика на Достава - Аптека Струмица" />
        <meta property="og:description" content="Достава од Аптека Струмица. Бесплатна достава над 2000 денари." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk/delivery" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/delivery" />
    </Head>
    <Header/>
    <div class="main">
     <!-- breadcrumb -->
     <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">Политика на достава</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                    <li class="active">Политика на достава</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="delivery-policy container py-5">
        <h2 class="mb-4">Политика за достава на производи</h2>

        <p>
          Оваа политика се однесува на испорака на производи нарачани преку нашата веб-страница. Испораката се врши преку курирска служба одбрана од нас, на адресата која ќе ја наведете при нарачката.
        </p>

        <h4 class="mt-4">1. Територија на достава</h4>
        <p>
          Испораката се врши само на територија на Република Северна Македонија.
        </p>

        <h4 class="mt-4">2. Време на испорака</h4>
        <ul>
          <li>Испораките се вршат секој работен ден, освен за време на државни празници и неработни денови.</li>
          <li>Сите нарачки ќе бидат дистрибуирани во избраниот термин, освен во случаи на технички или сообраќајни пречки.</li>
        </ul>

        <h4 class="mt-4">3. Трошоци за достава</h4>
        <ul>
          <li>За нарачки до 2000 денари: достава изнесува 150 денари.</li>
          <li>За нарачки над 2000 денари: доставата е бесплатна.</li>
        </ul>

        <h4 class="mt-4">4. Примопредавање на пратката</h4>
        <p>
          Испораката се врши до вратата на адресата. Ако клиентот инсистира да ја прими пратката надвор од објектот, доставувачот нема обврска да ја носи до вратата.
        </p>

        <!--
        <h4 class="mt-4">5. Недостапност на клиентот</h4>
        <p>
          Доколку клиентот не е достапен при испорака, пратката ќе биде вратена во нашето седиште и ќе може да се подигне во рок од 15 дена. Повторна достава чини 150 денари.
        </p>
      -->

        <h4 class="mt-4">5. Приговори и оштетувања</h4>
        <ul>
          <li>Купувачот мора да ја провери пратката при прием за оштетувања или грешки.</li>
          <li>Рекламации се прифаќаат само доколку производите се во оригинално пакување и не се употребувани.</li>
        </ul>

        <h4 class="mt-4">6. Услови за рекламација</h4>
        <p>Рекламација е возможна во следниве случаи:</p>
        <ul>
          <li>Испорака на погрешни производи</li>
          <li>Истечен рок на употреба</li>
          <li>Физички оштетувања на производот при транспорт</li>
        </ul>

        <h4 class="mt-4">8. Начин на пријавување на рекламација</h4>
        <p>
          Рекламација се праќа писмено на нашата е-пошта: <strong>{{ company.email }}</strong>. Производите може да се вратат во рок од 15 дена, само доколку се неупотребени и неоштетени.
        </p>

        <h4 class="mt-4">9. Промени на политиката</h4>
        <p>
          Задржуваме право на измена на оваа политика. Сите измени ќе бидат навремено објавени на нашата веб-страница.
        </p>
      </div>
    </div>
    <Footer/>


</template>

<style scoped>

</style>
