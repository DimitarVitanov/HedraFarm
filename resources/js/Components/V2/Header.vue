<script setup>
import { onMounted, onBeforeUnmount, ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from '@/utils/useCart';
import {
    useShop,
    categoryIcon,
    finalPrice,
    formatPrice,
    productUrl,
    FREE_SHIPPING_FROM,
} from '@/utils/useShop';

const page = usePage();
const { company, categories, products, loadCompany, loadCategories, loadProducts } = useShop();
const { cart, totalPrice, removeFromCart, updateQuantity } = useCart();

const showCategories = ref(false);
const searchQuery = ref('');
const showSearchResults = ref(false);
const cartOpen = ref(false);
const menuOpen = ref(false);
const scrolled = ref(false);

const links = [
    { label: 'Почетна', href: '/' },
    { label: 'Онлајн Продавница', href: '/store' },
    { label: 'За Нас', href: '/about' },
    { label: 'Блог', href: '/blogs' },
    { label: 'Контакт', href: '/contact' },
];

const currentPath = computed(() => page.url.split('?')[0]);
const isActive = (href) => (href === '/' ? currentPath.value === '/' : currentPath.value.startsWith(href));

const cartCount = computed(() => cart.items.reduce((total, item) => total + item.quantity, 0));
const freeShippingLeft = computed(() => Math.max(0, FREE_SHIPPING_FROM - totalPrice.value));
const freeShippingProgress = computed(() => Math.min(100, (totalPrice.value / FREE_SHIPPING_FROM) * 100));

const onScroll = () => {
    scrolled.value = window.scrollY > 40;
};

const onKeydown = (event) => {
    if (event.key === 'Escape') {
        cartOpen.value = false;
        menuOpen.value = false;
        showCategories.value = false;
    }
};

onMounted(() => {
    loadCompany();
    loadCategories();
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('keydown', onKeydown);
    document.documentElement.style.overflow = '';
});

watch([cartOpen, menuOpen], ([cartIsOpen, menuIsOpen]) => {
    document.documentElement.style.overflow = cartIsOpen || menuIsOpen ? 'hidden' : '';
});

const filteredSearchResults = computed(() => {
    if (!searchQuery.value || searchQuery.value.length < 2) return [];

    const query = searchQuery.value.toLowerCase();

    return products.value
        .filter(
            (p) =>
                p.name.toLowerCase().includes(query) ||
                (p.short_description && p.short_description.toLowerCase().includes(query)),
        )
        .slice(0, 8); // Limit to 8 results
});

const handleSearch = () => {
    loadProducts();
    showSearchResults.value = searchQuery.value.length >= 2;
};

const hideSearchResults = () => {
    setTimeout(() => {
        showSearchResults.value = false;
    }, 200);
};

const goToProduct = (product) => {
    window.location.href = productUrl(product);
};

const submitSearch = () => {
    if (searchQuery.value) {
        window.location.href = `/store?search=${encodeURIComponent(searchQuery.value)}`;
    }
};
</script>

<template>
    <!-- announcement bar -->
    <div class="hf-topbar">
        <div class="hf-container hf-topbar__inner">
            <span class="hf-topbar__msg">
                <Icon name="truck" />
                Бесплатна достава за нарачки над {{ formatPrice(FREE_SHIPPING_FROM) }}
            </span>
            <div class="hf-topbar__links">
                <a v-if="company.phone" :href="'tel:' + company.phone"><Icon name="phone" /> {{ company.phone }}</a>
                <a v-if="company.email" :href="'mailto:' + company.email"><Icon name="mail" /> {{ company.email }}</a>
                <a href="/contact">Ви треба помош?</a>
            </div>
        </div>
    </div>

    <!-- header -->
    <header class="hf-header" :class="{ 'is-scrolled': scrolled }">
        <div class="hf-container">
            <div class="hf-header__main">
                <button type="button" class="hf-icon-btn hf-icon-btn--plain hf-burger" aria-label="Мени" @click="menuOpen = true">
                    <Icon name="menu" />
                </button>

                <a class="hf-logo" href="/" aria-label="Хедерафарм+">
                    <img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="170" height="42" />
                </a>

                <div class="hf-search">
                    <form class="hf-search__box" role="search" @submit.prevent="submitSearch">
                        <Icon name="search" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Пребарај производи..."
                            aria-label="Пребарај производи"
                            @input="handleSearch"
                            @focus="handleSearch"
                            @blur="hideSearchResults"
                        />
                        <button type="submit">Барај</button>
                    </form>
                    <Transition name="hf-pop">
                        <div v-if="showSearchResults" class="hf-search__results" data-lenis-prevent>
                            <template v-if="filteredSearchResults.length > 0">
                                <div class="hf-search__list">
                                    <button
                                        v-for="product in filteredSearchResults"
                                        :key="product.id"
                                        type="button"
                                        class="hf-search__item"
                                        @mousedown="goToProduct(product)"
                                    >
                                        <img :src="product.main_image" :alt="product.name" width="48" height="48" />
                                        <div>
                                            <strong>{{ product.name }}</strong>
                                            <span>{{ formatPrice(finalPrice(product)) }}</span>
                                        </div>
                                    </button>
                                </div>
                                <button
                                    v-if="filteredSearchResults.length >= 8"
                                    type="button"
                                    class="hf-search__all"
                                    @mousedown.prevent="submitSearch"
                                >
                                    Прикажи ги сите резултати
                                </button>
                            </template>
                            <p v-else class="hf-search__none">Нема пронајдени производи</p>
                        </div>
                    </Transition>
                </div>

                <div class="hf-header__actions">
                    <button type="button" class="hf-cart-btn" aria-label="Кошничка" @click="cartOpen = true">
                        <span class="hf-cart-btn__icon">
                            <Icon name="bag" />
                            <span class="hf-cart-btn__count">{{ cartCount }}</span>
                        </span>
                        <span class="hf-cart-btn__label">
                            <small>Кошничка</small>
                            <strong>{{ formatPrice(totalPrice) }}</strong>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <nav class="hf-nav" aria-label="Главна навигација">
            <div class="hf-container hf-nav__inner">
                <div
                    class="hf-cats"
                    :class="{ 'is-open': showCategories }"
                    @mouseenter="showCategories = true"
                    @mouseleave="showCategories = false"
                >
                    <button
                        type="button"
                        class="hf-cats__btn"
                        :aria-expanded="showCategories"
                        @click="showCategories = !showCategories"
                    >
                        <Icon name="grid" /> Категории <Icon name="chevron-down" />
                    </button>
                    <Transition name="hf-pop">
                        <div v-if="showCategories" class="hf-cats__menu">
                            <a v-for="category in categories" :key="category.id" :href="'/store?category=' + category.id">
                                <i :class="'fas ' + categoryIcon(category)"></i>
                                <span>{{ category.translated }}</span>
                            </a>
                        </div>
                    </Transition>
                </div>

                <div class="hf-nav__links">
                    <a v-for="link in links" :key="link.href" :href="link.href" :class="{ 'is-active': isActive(link.href) }">
                        {{ link.label }}
                    </a>
                </div>

                <span class="hf-nav__meta"><Icon name="clock" /> Пон - Пет 07:30 - 22:00 · Саб 07:30 - 21:00</span>
            </div>
        </nav>
    </header>

    <!-- cart drawer -->
    <Transition name="hf-fade">
        <div v-if="cartOpen" class="hf-overlay" data-lenis-prevent @click="cartOpen = false"></div>
    </Transition>
    <Transition name="hf-slide-r">
        <aside v-if="cartOpen" class="hf-drawer hf-drawer--right" role="dialog" aria-label="Кошничка" data-lenis-prevent>
            <div class="hf-drawer__head">
                <h3>Кошничка <small>({{ cartCount }} продукти)</small></h3>
                <button type="button" class="hf-icon-btn hf-icon-btn--plain" aria-label="Затвори" @click="cartOpen = false">
                    <Icon name="x" />
                </button>
            </div>

            <template v-if="cart.items.length > 0">
                <div class="hf-drawer__body">
                    <div class="hf-ship">
                        <template v-if="freeShippingLeft > 0">
                            Уште <strong>{{ formatPrice(freeShippingLeft) }}</strong> до бесплатна достава
                        </template>
                        <template v-else><strong>Имате бесплатна достава!</strong></template>
                        <div class="hf-ship__bar"><i :style="{ width: freeShippingProgress + '%' }"></i></div>
                    </div>

                    <ul class="hf-mini">
                        <li v-for="item in cart.items" :key="item.id" class="hf-mini__item">
                            <a :href="productUrl(item)">
                                <img class="hf-mini__img" :src="item.img || item.main_image" :alt="item.title ?? item.name" />
                            </a>
                            <div>
                                <a class="hf-mini__name" :href="productUrl(item)">{{ item.title ?? item.name }}</a>
                                <div class="hf-mini__meta">
                                    <div class="hf-qty">
                                        <button
                                            type="button"
                                            aria-label="Намали"
                                            :disabled="item.quantity <= 1"
                                            @click="updateQuantity(item.id, item.quantity - 1)"
                                        >
                                            <Icon name="minus" />
                                        </button>
                                        <span>{{ item.quantity }}</span>
                                        <button type="button" aria-label="Зголеми" @click="updateQuantity(item.id, item.quantity + 1)">
                                            <Icon name="plus" />
                                        </button>
                                    </div>
                                    <span class="hf-mini__price">{{ formatPrice(finalPrice(item) * item.quantity) }}</span>
                                </div>
                            </div>
                            <button type="button" class="hf-mini__remove" title="Отстрани" @click="removeFromCart(item.id)">
                                <Icon name="trash" />
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="hf-drawer__foot">
                    <div class="hf-drawer__total">
                        <span>Вкупно</span>
                        <strong>{{ formatPrice(totalPrice) }}</strong>
                    </div>
                    <div class="hf-drawer__actions">
                        <a href="/cart-preview" class="hf-btn hf-btn--outline">Кошничка</a>
                        <a href="/checkout" class="hf-btn">Наплати <Icon name="arrow-right" /></a>
                    </div>
                </div>
            </template>

            <div v-else class="hf-drawer__body">
                <div class="hf-empty">
                    <div class="hf-empty__icon"><Icon name="bag" /></div>
                    <h3>Вашата кошничка е празна</h3>
                    <p>Разгледајте ја нашата понуда и додадете производи.</p>
                    <a href="/store" class="hf-btn">Онлајн Продавница</a>
                </div>
            </div>
        </aside>
    </Transition>

    <!-- mobile menu -->
    <Transition name="hf-fade">
        <div v-if="menuOpen" class="hf-overlay" data-lenis-prevent @click="menuOpen = false"></div>
    </Transition>
    <Transition name="hf-slide-l">
        <aside v-if="menuOpen" class="hf-drawer hf-drawer--left" role="dialog" aria-label="Мени" data-lenis-prevent>
            <div class="hf-drawer__head">
                <a class="hf-logo" href="/">
                    <img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="150" height="36" />
                </a>
                <button type="button" class="hf-icon-btn hf-icon-btn--plain" aria-label="Затвори" @click="menuOpen = false">
                    <Icon name="x" />
                </button>
            </div>
            <div class="hf-drawer__body">
                <nav class="hf-mnav">
                    <a v-for="link in links" :key="link.href" :href="link.href" :class="{ 'is-active': isActive(link.href) }">
                        {{ link.label }} <Icon name="chevron-right" />
                    </a>
                </nav>

                <p class="hf-drawer__label">Категории</p>
                <div class="hf-mcats">
                    <a v-for="category in categories" :key="category.id" :href="'/store?category=' + category.id">
                        <i :class="'fas ' + categoryIcon(category)"></i>
                        {{ category.translated }}
                    </a>
                </div>

                <p class="hf-drawer__label">Контакт</p>
                <div class="hf-drawer__contact" style="padding: 0 0.75rem">
                    <a v-if="company.phone" :href="'tel:' + company.phone"><Icon name="phone" /> {{ company.phone }}</a>
                    <a v-if="company.email" :href="'mailto:' + company.email"><Icon name="mail" /> {{ company.email }}</a>
                    <div class="hf-social" style="margin-top: 0.5rem">
                        <a href="https://www.facebook.com/hederafarmplus" target="_blank" rel="noopener" aria-label="Facebook">
                            <Icon name="facebook" />
                        </a>
                        <a href="https://www.instagram.com/hederafarmplus/" target="_blank" rel="noopener" aria-label="Instagram">
                            <Icon name="instagram" />
                        </a>
                    </div>
                </div>
            </div>
        </aside>
    </Transition>
</template>
