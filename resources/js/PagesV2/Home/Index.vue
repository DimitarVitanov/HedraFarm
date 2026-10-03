<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import Icon from '@/Components/V2/Icon.vue';
import BlogCard from '@/Components/V2/BlogCard.vue';
import HeroSlider from './Components/HeroSlider.vue';
import Category from './Components/Category.vue';
import TrendingItems from './Components/TrendingItems.vue';
import PopularItems from './Components/PopularItems.vue';
import ProductList from './Components/ProductList.vue';
import { formatPrice, FREE_SHIPPING_FROM } from '@/utils/useShop';

const blogs = ref([])

const usps = [
    { icon: 'truck', title: 'Бесплатна достава', text: `Над ${formatPrice(FREE_SHIPPING_FROM)}` },
    { icon: 'refresh', title: 'Враќање на производи', text: 'Во рок од 15 дена' },
    { icon: 'wallet', title: 'Плаќање', text: '100% при достава' },
    { icon: 'headset', title: 'Поддршка', text: 'Слободно контактирајте нè' },
]

onMounted(fetchBlogs)

async function fetchBlogs(){
    try{
        const response = await fetch('/blogs/fetch')
        if(!response.ok){
            throw new Error('An error occurred')
        }
        const data = await response.json()
        if(data.success){
            blogs.value =  data.data.slice(0,3)
        }
    }catch(error){
        console.log(error)
    }
}
</script>

<template>
    <Head>
        <title>Apteka Strumica | Аптека Струмица - Хедра Фарм Онлајн Аптека</title>
        <meta name="description" content="Apteka Strumica - Аптека Струмица. Хедра Фарм е вашата доверлива аптека во Струмица и онлајн аптека во Македонија. Широк избор на лекови, козметика, витамини и здравствени производи со бесплатна достава." />
        <meta name="keywords" content="apteka strumica, apteki strumica, аптека Струмица, аптеки Струмица, аптека во Струмица, онлајн аптека, лекови Струмица, козметика Струмица, витамини, Хедра Фарм, Hedera Farm, pharmacy strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Аптека Струмица | Хедра Фарм - Онлајн Аптека" />
        <meta property="og:description" content="Аптека Струмица - Вашата доверлива аптека во Струмица. Широк избор на лекови, козметика и витамини со бесплатна достава." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk" />
        <meta property="og:image" content="/assets/img/logo/logo.png" />
        <meta property="og:locale" content="mk_MK" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Аптека Струмица | Хедра Фарм" />
        <meta name="twitter:description" content="Аптека Струмица - Вашата доверлива аптека во Струмица. Широк избор на лекови, козметика и витамини." />
        <link rel="canonical" href="https://hederafarmplus.mk" />
    </Head>

    <StoreLayout>
        <HeroSlider />

        <section class="hf-section--tight">
            <div class="hf-container">
                <div class="hf-usps">
                    <div v-for="usp in usps" :key="usp.title" class="hf-usp">
                        <span class="hf-usp__icon"><Icon :name="usp.icon" /></span>
                        <div>
                            <h4>{{ usp.title }}</h4>
                            <p>{{ usp.text }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <Category />

        <TrendingItems />

        <section class="hf-section--tight">
            <div class="hf-container">
                <a href="/store" class="hf-banner">
                    <img src="/assets/img/banner/promo-magnevital.webp" alt="Magnevital Direct - 220 денари" width="1920" height="500" loading="lazy" />
                </a>
            </div>
        </section>

        <PopularItems />

        <ProductList />

        <section v-if="blogs.length" class="hf-section">
            <div class="hf-container">
                <div class="hf-section-head">
                    <div>
                        <span class="hf-eyebrow">Нашиот блог</span>
                        <h2 class="hf-title">Новости & совети</h2>
                    </div>
                    <a href="/blogs" class="hf-link">Сите статии <Icon name="arrow-right" /></a>
                </div>
                <div class="hf-posts">
                    <BlogCard v-for="blog in blogs" :key="blog.id" :blog="blog" />
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
