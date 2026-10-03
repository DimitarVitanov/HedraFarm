<script setup>
import { onMounted } from 'vue'
import { Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue'
import PageHero from '@/Components/V2/PageHero.vue'
import { useShop } from '@/utils/useShop'

const { company, loadCompany } = useShop()

onMounted(loadCompany)
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
        <meta property="og:url" content="https://hederafarmplus.mk/privacy" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/privacy" />
    </Head>

    <StoreLayout>
        <PageHero title="Политика на приватност">
            <p>Како ги собираме, користиме и заштитуваме вашите лични податоци.</p>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container hf-legal">
                <aside class="hf-panel hf-legal__card">
                    <h5>ПЗУ - аптека ХЕДЕРА ФАРМ ДВА Струмица</h5>
                    <p><strong>Адреса:</strong> Балканска бр.4 Струмица, Струмица</p>
                    <p><strong>ЕМБС:</strong> 7766637</p>
                    <p><strong>ЕДБ:</strong> 4027024539687</p>
                    <p><strong>Телефон:</strong> 075 472 123</p>
                    <p><strong>Email:</strong> pzuhederaplusfarm@yahoo.com</p>
                </aside>

                <div class="hf-panel hf-prose">
                    <p>
                    Оваа Политика на приватност објаснува како ги собираме, користиме и заштитуваме вашите лични податоци кога ја користите нашата веб-страница и услугите што ги нудиме преку неа.
                    </p>

                    <h4>1. Кои податоци ги собираме?</h4>
                    <ul>
                    <li>Име и презиме</li>
                    <li>Адреса за достава</li>
                    <li>Телефонски број</li>
                    <li>Е-пошта</li>
                    <li>Историја на нарачки</li>
                    <li>Податоци за плаќање (не се чуваат на серверот)</li>
                    </ul>

                    <h4>2. Како ги користиме вашите податоци?</h4>
                    <ul>
                    <li>За обработка и достава на нарачки</li>
                    <li>За контакт со вас за потврда или промена на нарачката</li>
                    <li>За подобрување на веб-страницата и услугата</li>
                    <li>За маркетинг цели (само ако дадете согласност)</li>
                    </ul>

                    <h4>3. Заштита на податоци</h4>
                    <p>Вашите податоци се чуваат безбедно со користење на технички и организациски мерки против неовластен пристап или злоупотреба.</p>

                    <h4>4. Делење на информации</h4>
                    <p>Не ги споделуваме вашите податоци со трети лица, освен кога е потребно за испорака или кога тоа го бара законот.</p>

                    <h4>5. Вашите права</h4>
                    <ul>
                    <li>Пристап до лични податоци</li>
                    <li>Исправка или бришење на податоци</li>
                    <li>Приговор за користење на податоците</li>
                    <li>Повлекување на согласност</li>
                    </ul>
                    <p>Контактирајте не на: <strong> {{company.email}} </strong></p>

                    <h4>6. Времетраење на чување на податоци</h4>
                    <p>Податоците се чуваат онолку долго колку што е потребно за законски и деловни цели.</p>

                    <h4>7. Промени на политиката</h4>
                    <p>Задржуваме право на измена на политиката.</p>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
