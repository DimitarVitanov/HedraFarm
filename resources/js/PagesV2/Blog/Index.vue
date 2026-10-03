<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import BlogCard from '@/Components/V2/BlogCard.vue';
import Icon from '@/Components/V2/Icon.vue';

const loading = ref(true)
const blogs = ref([])

onMounted(fetchBlogs);

async function fetchBlogs(){
    try{
        loading.value = true
        const response = await fetch('/blogs/fetch')
        if(!response.ok){
            throw new Error('An error occurred')
        }
        const data = await response.json()
        if(data.success){
            blogs.value =  data.data
        }
    }catch(error){
        console.log(error)
    }finally{
        loading.value = false
    }
}
</script>

<template>
    <Head>
        <title>Блог - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Блог на Аптека Струмица - Хедра Фарм. Здравствени совети, новости за производи и корисни информации за вашето здравје." />
        <meta name="keywords" content="блог, здравствени совети, новости, Хедра Фарм, аптека Струмица, здравје, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Блог - Аптека Струмица | Хедра Фарм" />
        <meta property="og:description" content="Блог на Аптека Струмица - Здравствени совети и корисни информации." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk/blogs" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/blogs" />
    </Head>

    <StoreLayout>
        <PageHero title="Новости & совети" crumb="Блог">
            <p>Здравствени совети, новости за производи и корисни информации за вашето здравје.</p>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container">
                <div v-if="loading" class="hf-posts">
                    <div v-for="n in 3" :key="n" class="hf-skel" style="height: 400px"></div>
                </div>
                <div v-else-if="blogs.length" class="hf-posts">
                    <BlogCard v-for="blog in blogs" :key="blog.id" :blog="blog" />
                </div>
                <div v-else class="hf-panel hf-empty">
                    <div class="hf-empty__icon"><Icon name="calendar" /></div>
                    <h3>Наскоро нови статии</h3>
                    <p>Во моментов нема објавени статии. Навратете повторно наскоро.</p>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
