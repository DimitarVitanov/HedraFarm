<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import ProductCard from '@/Components/V2/ProductCard.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useShop, finalPrice } from '@/utils/useShop';

const { url } = usePage();
const route = new URL(url, window.location.origin);
const selectedCategoryParam = route.searchParams.get('category');
const searchParam = route.searchParams.get('search');

const { products, categories, loadProducts, loadCategories } = useShop();
const loading = ref(true)
const subcategories = ref([])
const selectedCategories = ref([]);
const selectedSubcategories = ref([]);
const searchQuery = ref('');
const sortBy = ref('default');
const filtersOpen = ref(false);
const currentPage = ref(1);
const itemsPerPage = 12;
const gridTop = ref(null);

onMounted(async()=>{
    loading.value = true
    await Promise.all([loadProducts(), loadCategories(), fetchSubcategories()])

    if (selectedCategoryParam) {
        selectedCategories.value = [parseInt(selectedCategoryParam)]
    }

    if (searchParam) {
        searchQuery.value = searchParam
    }

    loading.value = false
})

async function fetchSubcategories(){
    try{
        const response = await fetch('/product-subcategories/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json()
        if(data.success){
            subcategories.value = data.data
        }
    } catch(error){
        console.log(error)
    }
}

// Only offer subcategories that belong to the chosen categories
const visibleSubcategories = computed(() => {
    if (selectedCategories.value.length === 0) return subcategories.value;
    return subcategories.value.filter(sub => selectedCategories.value.includes(sub.product_category_id));
});

watch(visibleSubcategories, (visible) => {
    const ids = visible.map(sub => sub.id);
    selectedSubcategories.value = selectedSubcategories.value.filter(id => ids.includes(id));
});

const filteredProducts = computed(() => {
    let result = products.value;

    const query = searchQuery.value.trim().toLowerCase();

    // Filter by search
    if (query) {
        result = result.filter(product => {
            return (
                product.name.toLowerCase().includes(query) ||
                String(product.price).includes(query)
            );
        });
    }

    // Filter by selected categories
    if (selectedCategories.value.length > 0) {
        result = result.filter(product =>
            selectedCategories.value.includes(product.product_category_id)
        );
    }

    // Filter by selected subcategories
    if (selectedSubcategories.value.length > 0) {
        result = result.filter(product =>
            product.subcategories.some(sub =>
                selectedSubcategories.value.includes(sub.id)
            )
        );
    }

    if (sortBy.value === 'price-asc') {
        result = [...result].sort((a, b) => finalPrice(a) - finalPrice(b));
    } else if (sortBy.value === 'price-desc') {
        result = [...result].sort((a, b) => finalPrice(b) - finalPrice(a));
    } else if (sortBy.value === 'name') {
        result = [...result].sort((a, b) => a.name.localeCompare(b.name, 'mk'));
    } else if (sortBy.value === 'sale') {
        result = [...result].sort((a, b) => (b.disscount || 0) - (a.disscount || 0));
    }

    return result;
});

watch([selectedCategories, selectedSubcategories, searchQuery, sortBy], () => {
  currentPage.value = 1;
});

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredProducts.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage));

const visiblePages = computed(() => {
  const pages = [];
  const total = totalPages.value;
  const current = currentPage.value;

  if (total <= 3) {
    for (let i = 1; i <= total; i++) {
      pages.push(i);
    }
  } else {
    // Show 3 pages around current page
    let start = Math.max(1, current - 1);
    let end = Math.min(total, current + 1);

    if (current === 1) {
      end = Math.min(total, 3);
    } else if (current === total) {
      start = Math.max(1, total - 2);
    }

    for (let i = start; i <= end; i++) {
      pages.push(i);
    }
  }

  return pages;
});

const goToPage = (page) => {
    currentPage.value = page;
    const top = gridTop.value?.getBoundingClientRect().top + window.scrollY - 170;
    window.scrollTo({ top, behavior: 'smooth' });
};

const activeFilters = computed(() => [
    ...categories.value
        .filter(c => selectedCategories.value.includes(c.id))
        .map(c => ({ key: 'c' + c.id, label: c.translated, remove: () => selectedCategories.value = selectedCategories.value.filter(id => id !== c.id) })),
    ...subcategories.value
        .filter(s => selectedSubcategories.value.includes(s.id))
        .map(s => ({ key: 's' + s.id, label: s.translated, remove: () => selectedSubcategories.value = selectedSubcategories.value.filter(id => id !== s.id) })),
    ...(searchQuery.value.trim() ? [{ key: 'q', label: '„' + searchQuery.value.trim() + '“', remove: () => searchQuery.value = '' }] : []),
]);

const clearFilters = () => {
    selectedCategories.value = [];
    selectedSubcategories.value = [];
    searchQuery.value = '';
};

function getCategoryProductCount(categoryId) {
    return products.value.filter(p => p.product_category_id === categoryId).length;
}

function getSubcategoryProductCount(subcategoryId) {
    return products.value.filter(p =>
        p.subcategories && p.subcategories.some(sub => sub.id === subcategoryId)
    ).length;
}

watch(filtersOpen, (open) => {
    document.documentElement.style.overflow = open ? 'hidden' : '';
});
</script>

<template>
    <Head>
        <title>Онлајн Продавница - Аптека Струмица | Хедра Фарм</title>
        <meta name="description" content="Аптека Струмица онлајн продавница - широк избор на лекови, козметика, витамини и здравствени производи. Бесплатна достава над 2000 денари низ Македонија." />
        <meta name="keywords" content="онлајн продавница, аптека Струмица, аптека во Струмица, лекови, козметика, витамини, здравствени производи, Хедра Фарм, купи онлајн, apteka Strumica" />
        <meta name="robots" content="index, follow" />
        <meta property="og:title" content="Онлајн Продавница - Аптека Струмица | Хедра Фарм" />
        <meta property="og:description" content="Аптека Струмица - Широк избор на лекови, козметика и витамини. Бесплатна достава над 2000 денари." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://hederafarmplus.mk/store" />
        <meta property="og:locale" content="mk_MK" />
        <link rel="canonical" href="https://hederafarmplus.mk/store" />
    </Head>

    <StoreLayout>
        <PageHero title="Онлајн Продавница">
            <p>Лекови, додатоци во исхрана, козметика и опрема за бебиња - со достава до вашата врата.</p>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container hf-shop">
                <Transition name="hf-fade">
                    <div v-if="filtersOpen" class="hf-overlay" data-lenis-prevent @click="filtersOpen = false"></div>
                </Transition>

                <aside class="hf-filters" :class="{ 'is-open': filtersOpen }" data-lenis-prevent>
                    <div class="hf-filters__head">
                        <h3>Филтри</h3>
                        <button type="button" class="hf-icon-btn hf-icon-btn--plain" aria-label="Затвори" @click="filtersOpen = false">
                            <Icon name="x" />
                        </button>
                    </div>

                    <div class="hf-panel">
                        <h4 class="hf-panel__title">Пребарувач</h4>
                        <div class="hf-inputicon">
                            <Icon name="search" />
                            <input v-model="searchQuery" type="text" class="hf-input" placeholder="Име на производ..." aria-label="Пребарувач" />
                        </div>
                    </div>

                    <div class="hf-panel">
                        <h4 class="hf-panel__title">Категории</h4>
                        <label v-for="category in categories" :key="category.id" class="hf-check">
                            <input v-model="selectedCategories" type="checkbox" :value="category.id" />
                            <span>{{ category.translated }}</span>
                            <small>{{ getCategoryProductCount(category.id) }}</small>
                        </label>
                    </div>

                    <div v-if="visibleSubcategories.length" class="hf-panel">
                        <h4 class="hf-panel__title">Намена</h4>
                        <div class="hf-filters__scroll" data-lenis-prevent>
                            <label v-for="subcategory in visibleSubcategories" :key="subcategory.id" class="hf-check">
                                <input v-model="selectedSubcategories" type="checkbox" :value="subcategory.id" />
                                <span>{{ subcategory.translated }}</span>
                                <small>{{ getSubcategoryProductCount(subcategory.id) }}</small>
                            </label>
                        </div>
                    </div>

                    <button type="button" class="hf-btn hf-toolbar__filter" @click="filtersOpen = false">
                        Прикажи {{ filteredProducts.length }} производи
                    </button>
                </aside>

                <div ref="gridTop">
                    <div class="hf-toolbar">
                        <p class="hf-toolbar__count">
                            <strong>{{ filteredProducts.length }}</strong> производи
                        </p>
                        <div class="hf-toolbar__right">
                            <button type="button" class="hf-btn hf-btn--outline hf-btn--sm hf-toolbar__filter" @click="filtersOpen = true">
                                <Icon name="filter" /> Филтри
                            </button>
                            <select v-model="sortBy" class="hf-select" aria-label="Подреди">
                                <option value="default">Препорачано</option>
                                <option value="price-asc">Цена: од најниска</option>
                                <option value="price-desc">Цена: од највисока</option>
                                <option value="name">Име: А - Ш</option>
                                <option value="sale">Најголем попуст</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="activeFilters.length" class="hf-active">
                        <span v-for="filter in activeFilters" :key="filter.key" class="hf-chip">
                            {{ filter.label }}
                            <button type="button" aria-label="Отстрани филтер" @click="filter.remove()"><Icon name="x" /></button>
                        </span>
                        <button type="button" class="hf-chip hf-chip--line" @click="clearFilters">Исчисти ги сите</button>
                    </div>

                    <div v-if="loading" class="hf-grid">
                        <div v-for="n in 8" :key="n" class="hf-skel" style="height: 340px"></div>
                    </div>

                    <div v-else-if="paginatedProducts.length" class="hf-grid">
                        <ProductCard v-for="product in paginatedProducts" :key="product.id" :product="product" />
                    </div>

                    <div v-else class="hf-panel hf-empty">
                        <div class="hf-empty__icon"><Icon name="search" /></div>
                        <h3>Нема пронајдени производи</h3>
                        <p>Обидете се со друг збор за пребарување или отстранете некои од филтрите.</p>
                        <button type="button" class="hf-btn" @click="clearFilters">Исчисти филтри</button>
                    </div>

                    <nav v-if="totalPages > 1" class="hf-pager" aria-label="Страници">
                        <button type="button" :disabled="currentPage === 1" aria-label="Претходна" @click="goToPage(currentPage - 1)">
                            <Icon name="chevron-left" />
                        </button>
                        <template v-if="visiblePages[0] > 1">
                            <button type="button" @click="goToPage(1)">1</button>
                            <span v-if="visiblePages[0] > 2">…</span>
                        </template>
                        <button
                            v-for="page in visiblePages"
                            :key="page"
                            type="button"
                            :class="{ 'is-active': currentPage === page }"
                            @click="goToPage(page)"
                        >
                            {{ page }}
                        </button>
                        <template v-if="visiblePages[visiblePages.length - 1] < totalPages">
                            <span v-if="visiblePages[visiblePages.length - 1] < totalPages - 1">…</span>
                            <button type="button" @click="goToPage(totalPages)">{{ totalPages }}</button>
                        </template>
                        <button type="button" :disabled="currentPage === totalPages" aria-label="Следна" @click="goToPage(currentPage + 1)">
                            <Icon name="chevron-right" />
                        </button>
                    </nav>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
