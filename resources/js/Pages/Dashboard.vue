<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Icon from '@/Components/V2/Icon.vue';
import Chart from 'primevue/chart';
import Modal from '@/Components/Modal.vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

// Register Chart.js components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
);

const props = defineProps({
    statistics: {
        type: Object,
        default: () => ({})
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
});

const page = usePage();
const showSettingsModal = ref(false);
const dashboardSettings = ref({
    show_charts: true,
    show_revenue: true,
    show_recent_activity: true,
    refresh_interval: 30,
    chart_type: 'line',
    theme: 'light'
});

// Double check admin status from user data
const isAdminUser = computed(() => {
    const user = page.props.auth.user;
    return user && (user.team_id === 1 || user.team_id === '1');
});

// Combined admin check
const showAdminDashboard = computed(() => {
    return props.isAdmin || isAdminUser.value;
});

// Chart data
const BRAND = '#0b8f7f';
const ordersChartData = ref({});
const revenueChartData = ref({});
const productsChartData = ref({});
const trendChartOptions = ref({});
const productsChartOptions = ref({});

const chartTooltip = {
    backgroundColor: '#10231f',
    titleColor: '#ffffff',
    bodyColor: '#e4ebe7',
    padding: 10,
    cornerRadius: 10,
    displayColors: false,
    titleFont: { family: 'Inter', weight: '600' },
    bodyFont: { family: 'Inter' },
};
const chartTicks = { color: '#7b8a84', font: { family: 'Inter', size: 11 } };

const trendDataset = (label, data) => ({
    label,
    data,
    borderColor: BRAND,
    backgroundColor: 'rgba(11, 143, 127, 0.08)',
    fill: true,
    borderWidth: 2,
    tension: 0.3,
    cubicInterpolationMode: 'monotone',
    pointRadius: data.length > 1 ? 0 : 4,
    pointBackgroundColor: BRAND,
    pointHoverRadius: 5,
    pointHoverBackgroundColor: BRAND,
    pointHoverBorderColor: '#ffffff',
    pointHoverBorderWidth: 2,
});

const productsChartHeight = computed(() => {
    const count = productsChartData.value.labels?.length || 1;
    return `${Math.max(190, count * 34 + 40)}px`;
});

onMounted(() => {
    console.log('Dashboard mounted:', {
        isAdmin: props.isAdmin,
        isAdminUser: isAdminUser.value,
        showAdminDashboard: showAdminDashboard.value,
        statistics: props.statistics,
        user: page.props.auth.user
    });

    if (showAdminDashboard.value) {
        setupCharts();
        loadDashboardSettings();
    }
});

const setupCharts = () => {
    if (!props.statistics?.charts) {
        console.log('No chart data available');
        return;
    }

    // Orders & revenue trend - one chart per measure, sharing the date axis
    const ordersTrend = props.statistics.charts.orders_trend || [];
    const trendLabels = ordersTrend.length > 0
        ? ordersTrend.map(item => new Date(item.date).toLocaleDateString(undefined, { day: 'numeric', month: 'short' }))
        : ['No Data'];
    ordersChartData.value = {
        labels: trendLabels,
        datasets: [trendDataset('Orders', ordersTrend.length > 0 ? ordersTrend.map(item => item.count) : [0])],
    };
    revenueChartData.value = {
        labels: trendLabels,
        datasets: [trendDataset('Revenue (Ден)', ordersTrend.length > 0 ? ordersTrend.map(item => parseFloat(item.revenue || 0)) : [0])],
    };

    // Products by category - largest first
    const productsByCategory = [...(props.statistics.charts.products_by_category || [])]
        .sort((a, b) => b.count - a.count);
    productsChartData.value = {
        labels: productsByCategory.length > 0 ? productsByCategory.map(item => item.name) : ['No Categories'],
        datasets: [{
            label: 'Products',
            data: productsByCategory.length > 0 ? productsByCategory.map(item => item.count) : [0],
            backgroundColor: BRAND,
            hoverBackgroundColor: '#08766a',
            borderRadius: 4,
            maxBarThickness: 16,
        }]
    };

    // Chart options
    trendChartOptions.value = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: chartTooltip,
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { ...chartTicks, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 },
            },
            y: {
                beginAtZero: true,
                grid: { color: '#eef2f0' },
                border: { display: false },
                ticks: { ...chartTicks, maxTicksLimit: 5, precision: 0 },
            },
        },
    };

    productsChartOptions.value = {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: chartTooltip,
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: '#eef2f0' },
                border: { display: false },
                ticks: { ...chartTicks, precision: 0 },
            },
            y: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#47564f', font: { family: 'Inter', size: 12 } },
            },
        },
    };
};

const loadDashboardSettings = async () => {
    try {
        const response = await fetch('/dashboard/settings');
        const data = await response.json();
        if (data.success) {
            dashboardSettings.value = { ...dashboardSettings.value, ...data.data };
        }
    } catch (error) {
        console.error('Failed to load dashboard settings:', error);
    }
};

const saveDashboardSettings = async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                         page.props.csrf_token;

        const response = await fetch('/dashboard/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dashboardSettings.value)
        });

        const data = await response.json();
        if (data.success) {
            showSettingsModal.value = false;
            // Optionally reload the page or update settings
        }
    } catch (error) {
        console.error('Failed to save dashboard settings:', error);
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'MKD',
    }).format(value || 0);
};

const formatNumber = (value) => {
    return new Intl.NumberFormat().format(value || 0);
};
</script>

<template>
    <Head>
        <title>Контролна Табла - Хедра Фарм</title>
        <meta name="description" content="Контролна табла за управување со вашата сметка на Хедра Фарм." />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <div class="ha-head__title">
                <h1>Dashboard</h1>
            </div>
            <div class="ha-head__actions" v-if="showAdminDashboard">
                <button type="button" class="ha-btn ha-btn--ghost" @click="showSettingsModal = true">
                    <Icon name="settings" /> Settings
                </button>
            </div>
        </template>

        <div v-if="!showAdminDashboard" class="ha-welcome">
            <span class="ha-welcome__icon"><Icon name="cross" /></span>
            <div>
                <h2>Welcome to HedraFarm!</h2>
                <p>You're logged in as a regular user. Explore our products and place orders.</p>
            </div>
        </div>

        <!-- Admin Dashboard -->
        <div v-if="showAdminDashboard" class="ha-stack">

            <!-- Overview Cards -->
            <div class="ha-stats">
                <div class="ha-stat">
                    <div class="ha-stat__top">
                        <span class="ha-stat__label">Total Orders</span>
                        <span class="ha-stat__icon"><Icon name="receipt" /></span>
                    </div>
                    <div class="ha-stat__value">{{ formatNumber(statistics?.overview?.total_orders || 0) }}</div>
                    <div class="ha-stat__note">+{{ statistics?.overview?.recent_orders || 0 }} this week</div>
                </div>

                <div class="ha-stat ha-stat--feature">
                    <div class="ha-stat__top">
                        <span class="ha-stat__label">Total Revenue</span>
                        <span class="ha-stat__icon"><Icon name="wallet" /></span>
                    </div>
                    <div class="ha-stat__value">{{ formatCurrency(statistics?.overview?.total_revenue || 0) }}</div>
                    <div class="ha-stat__note">
                        {{ (statistics?.overview?.revenue_growth || 0) > 0 ? '+' : '' }}{{ statistics?.overview?.revenue_growth || 0 }}% from last month
                    </div>
                </div>

                <div class="ha-stat">
                    <div class="ha-stat__top">
                        <span class="ha-stat__label">Total Products</span>
                        <span class="ha-stat__icon"><Icon name="box" /></span>
                    </div>
                    <div class="ha-stat__value">{{ formatNumber(statistics?.overview?.total_products || 0) }}</div>
                    <div class="ha-stat__note">{{ statistics?.overview?.total_categories || 0 }} categories</div>
                </div>

                <div class="ha-stat">
                    <div class="ha-stat__top">
                        <span class="ha-stat__label">Total Users</span>
                        <span class="ha-stat__icon"><Icon name="users" /></span>
                    </div>
                    <div class="ha-stat__value">{{ formatNumber(statistics?.overview?.total_users || 0) }}</div>
                    <div class="ha-stat__note">+{{ statistics?.overview?.recent_users || 0 }} this week</div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="ha-dash-row" v-if="dashboardSettings.show_charts && statistics?.charts">
                <!-- Orders Trend Chart -->
                <section class="ha-card">
                    <div class="ha-card__head"><h2>Orders & Revenue Trend (Last 30 Days)</h2></div>
                    <div class="ha-card__body">
                        <div class="ha-chart-block">
                            <p class="ha-chart-label">Orders</p>
                            <Chart type="line" :data="ordersChartData" :options="trendChartOptions" class="ha-chart" />
                        </div>
                        <div class="ha-chart-block">
                            <p class="ha-chart-label">Revenue (Ден)</p>
                            <Chart type="line" :data="revenueChartData" :options="trendChartOptions" class="ha-chart" />
                        </div>
                    </div>
                </section>

                <!-- Products by Category Chart -->
                <section class="ha-card">
                    <div class="ha-card__head"><h2>Products by Category</h2></div>
                    <div class="ha-card__body">
                        <Chart
                            type="bar"
                            :data="productsChartData"
                            :options="productsChartOptions"
                            class="ha-chart"
                            :style="{ height: productsChartHeight }"
                        />
                    </div>
                </section>
            </div>

            <!-- Alerts & Recent Activity -->
            <div class="ha-dash-row ha-dash-row--even">
                <!-- Low Stock Alert -->
                <section class="ha-card ha-card--danger" v-if="statistics?.alerts?.low_stock_products?.length > 0">
                    <div class="ha-card__head"><h2><Icon name="alert" /> Low Stock Alert</h2></div>
                    <div class="ha-card__body">
                        <div class="ha-list">
                            <div
                                v-for="product in statistics.alerts.low_stock_products"
                                :key="product.name"
                                class="ha-list__row"
                            >
                                <span class="ha-list__key">{{ product.name }}</span>
                                <span class="ha-badge ha-badge--danger">{{ product.quantity }} left</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Stats -->
                <section class="ha-card">
                    <div class="ha-card__head"><h2>Quick Stats</h2></div>
                    <div class="ha-card__body">
                        <div class="ha-list">
                            <div class="ha-list__row">
                                <span class="ha-list__key">Blog Posts:</span>
                                <span class="ha-list__val">{{ formatNumber(statistics?.overview?.total_blogs || 0) }}</span>
                            </div>
                            <div class="ha-list__row">
                                <span class="ha-list__key">This Month Revenue:</span>
                                <span class="ha-list__val">{{ formatCurrency(statistics?.overview?.current_month_revenue || 0) }}</span>
                            </div>
                            <div class="ha-list__row">
                                <span class="ha-list__key">Revenue Growth:</span>
                                <span :class="(statistics?.overview?.revenue_growth || 0) >= 0 ? 'ha-up' : 'ha-down'" class="ha-list__val">
                                    {{ (statistics?.overview?.revenue_growth || 0) >= 0 ? '+' : '' }}{{ statistics?.overview?.revenue_growth || 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Settings Modal -->
        <Modal :show="showSettingsModal" @close="showSettingsModal = false" maxWidth="md">
            <div class="ha-modal__head">
                <h3>Dashboard Settings</h3>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="showSettingsModal = false"><Icon name="x" /></button>
            </div>

            <div class="ha-modal__body">
                <label for="show_charts" class="ha-switch ha-toggle">
                    Show Charts
                    <input id="show_charts" type="checkbox" v-model="dashboardSettings.show_charts">
                </label>

                <label for="show_revenue" class="ha-switch ha-toggle">
                    Show Revenue
                    <input id="show_revenue" type="checkbox" v-model="dashboardSettings.show_revenue">
                </label>

                <label for="show_recent_activity" class="ha-switch ha-toggle">
                    Show Recent Activity
                    <input id="show_recent_activity" type="checkbox" v-model="dashboardSettings.show_recent_activity">
                </label>

                <div class="ha-field">
                    <label class="ha-label">Auto-refresh interval (seconds)</label>
                    <input
                        type="number"
                        v-model.number="dashboardSettings.refresh_interval"
                        min="10"
                        max="300"
                        class="ha-input"
                    >
                </div>

                <div class="ha-field">
                    <label class="ha-label">Chart Type</label>
                    <select v-model="dashboardSettings.chart_type" class="ha-input">
                        <option value="line">Line Chart</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                </div>
            </div>

            <div class="ha-modal__foot">
                <button type="button" class="ha-btn ha-btn--ghost" @click="showSettingsModal = false">Cancel</button>
                <button type="button" class="ha-btn" @click="saveDashboardSettings">Save Settings</button>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
