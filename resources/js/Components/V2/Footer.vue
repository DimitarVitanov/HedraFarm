<script setup>
import { onMounted } from 'vue';
import Icon from '@/Components/V2/Icon.vue';
import NewsLetter from '@/Components/V2/NewsLetter.vue';
import { useShop } from '@/utils/useShop';

const { company, categories, loadCompany, loadCategories } = useShop();
const year = new Date().getFullYear();

onMounted(() => {
    loadCompany();
    loadCategories();
});
</script>

<template>
    <footer class="hf-footer">
        <div class="hf-container">
            <div class="hf-footer__news">
                <NewsLetter />
            </div>

            <div class="hf-footer__grid">
                <div class="hf-footer__brand">
                    <a href="/"><img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="186" height="46" /></a>
                    <p>Вашата доверлива аптека во Струмица и онлајн аптека со достава низ цела Македонија.</p>
                    <div class="hf-social">
                        <a href="https://www.facebook.com/hederafarmplus" target="_blank" rel="noopener" aria-label="Facebook">
                            <Icon name="facebook" />
                        </a>
                        <a href="https://www.instagram.com/hederafarmplus/" target="_blank" rel="noopener" aria-label="Instagram">
                            <Icon name="instagram" />
                        </a>
                    </div>
                </div>

                <div>
                    <h4>Линкови</h4>
                    <ul class="hf-footer__list">
                        <li><a href="/">Почетна</a></li>
                        <li><a href="/store">Онлајн Продавница</a></li>
                        <li><a href="/about">За Нас</a></li>
                        <li><a href="/blogs">Блог</a></li>
                        <li><a href="/contact">Контакт</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Категории</h4>
                    <ul class="hf-footer__list">
                        <li v-for="category in categories" :key="category.id">
                            <a :href="'/store?category=' + category.id">{{ category.translated }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4>Центар за поддршка</h4>
                    <ul class="hf-footer__list">
                        <li><a href="/contact">Често поставувани прашања</a></li>
                        <li><a href="/contact">Како да направите нарачка</a></li>
                        <li><a href="/privacy">Политика на приватност</a></li>
                        <li><a href="/delivery-policy">Политика на достава</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Контакт</h4>
                    <ul class="hf-footer__contact">
                        <li v-if="company.phone">
                            <Icon name="phone" /><a :href="'tel:' + company.phone">{{ company.phone }}</a>
                        </li>
                        <li v-if="company.address"><Icon name="pin" /><span>{{ company.address }}</span></li>
                        <li v-if="company.email">
                            <Icon name="mail" /><a :href="'mailto:' + company.email">{{ company.email }}</a>
                        </li>
                        <li>
                            <Icon name="clock" />
                            <span>Пон - Пет (07:30 - 22:00)<br />Сабота (07:30 - 21:00)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="hf-footer__bottom">
                <p>&copy; {{ year }} Хедерафарм+. Сите права се задржани.</p>
                <ul>
                    <li><a href="/privacy">Приватност</a></li>
                    <li><a href="/delivery-policy">Достава</a></li>
                    <li><a href="/contact">Контакт</a></li>
                </ul>
            </div>
        </div>
    </footer>
</template>
