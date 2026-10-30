<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import Icon from '@/Components/V2/Icon.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

const isAdmin = computed(() => {
    return page.props.auth.user?.team_id == '1' || page.props.auth.user?.team_id == 1;
});

const navGroups = computed(() => {
    if (isAdmin.value) {
        return [
            { items: [{ label: 'Dashboard', route: 'dashboard', icon: 'dashboard' }] },
            {
                label: 'Shop',
                items: [
                    { label: 'Orders', route: 'admin.orders', icon: 'receipt' },
                    { label: 'Products', route: 'admin.products', icon: 'box' },
                    { label: 'Categories', route: 'admin.categories', icon: 'layers' },
                ],
            },
            {
                label: 'Content',
                items: [
                    { label: 'Slider', route: 'admin.slider', icon: 'image' },
                    { label: 'Blog', route: 'admin.blog', icon: 'file' },
                ],
            },
            {
                label: 'Settings',
                items: [
                    { label: 'Company', route: 'admin.company', icon: 'building' },
                    { label: 'Users', route: 'admin.users', icon: 'users' },
                ],
            },
        ];
    }
    return [
        {
            items: [
                { label: 'Dashboard', route: 'dashboard', icon: 'dashboard' },
                { label: 'Home', route: 'home', icon: 'bag' },
            ],
        },
        {
            label: 'Shop',
            items: [
                { label: 'Orders', route: 'admin.orders', icon: 'receipt' },
                { label: 'Products', route: 'admin.products', icon: 'box' },
                { label: 'Categories', route: 'admin.categories', icon: 'layers' },
            ],
        },
    ];
});

const initials = computed(() => {
    const name = page.props.auth.user?.name || '';
    return name.split(' ').filter(Boolean).slice(0, 2).map(part => part[0]).join('') || '?';
});

watch(showingNavigationDropdown, (open) => {
    document.body.style.overflow = open ? 'hidden' : null;
});

let removeNavigateListener;
const closeOnEscape = (e) => {
    if (e.key === 'Escape') showingNavigationDropdown.value = false;
};

onMounted(() => {
    removeNavigateListener = router.on('navigate', () => (showingNavigationDropdown.value = false));
    document.addEventListener('keydown', closeOnEscape);
});

onBeforeUnmount(() => {
    removeNavigateListener?.();
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});
</script>

<template>
    <div class="ha">
        <aside class="ha-side" :class="{ 'is-open': showingNavigationDropdown }" aria-label="Admin navigation">
            <div class="ha-side__brand">
                <Link :href="route('dashboard')">
                    <img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="146" height="36" />
                </Link>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain ha-side__close" aria-label="Close menu" @click="showingNavigationDropdown = false">
                    <Icon name="x" />
                </button>
            </div>

            <nav class="ha-side__nav">
                <div v-for="(group, index) in navGroups" :key="index" class="ha-nav-group">
                    <p v-if="group.label" class="ha-nav-label">{{ group.label }}</p>
                    <Link
                        v-for="item in group.items"
                        :key="item.route"
                        :href="route(item.route)"
                        class="ha-nav-link"
                        :class="{ 'is-active': route().current(item.route) }"
                    >
                        <Icon :name="item.icon" />
                        {{ item.label }}
                    </Link>
                </div>
            </nav>

            <div class="ha-side__foot">
                <div class="ha-user">
                    <span class="ha-avatar">{{ initials }}</span>
                    <div class="ha-user__meta">
                        <span class="ha-user__name">
                            <span>{{ $page.props.auth.user.name }}</span>
                            <span v-if="isAdmin" class="ha-role">Admin</span>
                        </span>
                        <span class="ha-user__email">{{ $page.props.auth.user.email }}</span>
                    </div>
                </div>
                <div class="ha-user__actions">
                    <Link :href="route('profile.edit')" :class="{ 'is-active': route().current('profile.edit') }">
                        <Icon name="user" /> Profile
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="ha-logout">
                        <Icon name="logout" /> Log Out
                    </Link>
                </div>
            </div>
        </aside>

        <Transition name="ha-modal-fade">
            <div v-if="showingNavigationDropdown" class="ha-scrim" @click="showingNavigationDropdown = false"></div>
        </Transition>

        <div class="ha-main">
            <header class="ha-topbar">
                <button type="button" class="ha-icon-btn" aria-label="Open menu" @click="showingNavigationDropdown = true">
                    <Icon name="menu" />
                </button>
                <Link :href="route('dashboard')">
                    <img src="/assets/img/logo/logo-without-bg.webp" alt="Хедерафарм+" width="122" height="30" />
                </Link>
                <Link :href="route('profile.edit')" class="ha-avatar" aria-label="Profile">{{ initials }}</Link>
            </header>

            <main class="ha-content">
                <div v-if="$slots.header" class="ha-head">
                    <slot name="header" />
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
