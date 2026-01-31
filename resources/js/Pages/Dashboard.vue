<script setup>
import { ref, computed, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';
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
const ordersChartData = ref({});
const productsChartData = ref({});
const ordersChartOptions = ref({});
const productsChartOptions = ref({});

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

    // Orders trend chart
    const ordersTrend = props.statistics.charts.orders_trend || [];
    ordersChartData.value = {
        labels: ordersTrend.length > 0 ? ordersTrend.map(item => new Date(item.date).toLocaleDateString()) : ['No Data'],
        datasets: [
            {
                label: 'Orders',
                data: ordersTrend.length > 0 ? ordersTrend.map(item => item.count) : [0],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true
            },
            {
                label: 'Revenue (Ден)',
                data: ordersTrend.length > 0 ? ordersTrend.map(item => parseFloat(item.revenue || 0)) : [0],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                yAxisID: 'y1'
            }
        ]
    };

    // Products by category chart
    const productsByCategory = props.statistics.charts.products_by_category || [];
    productsChartData.value = {
        labels: productsByCategory.length > 0 ? productsByCategory.map(item => item.name) : ['No Categories'],
        datasets: [{
            data: productsByCategory.length > 0 ? productsByCategory.map(item => item.count) : [0],
            backgroundColor: [
                '#3B82F6',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#8B5CF6',
                '#F97316'
            ]
        }]
    };

    // Chart options
    ordersChartOptions.value = {
        responsive: true,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    };

    productsChartOptions.value = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
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
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
                <Button
                    v-if="showAdminDashboard"
                    label="Settings"
                    icon="fa fa-cog"
                    severity="secondary"
                    @click="showSettingsModal = true"
                />
            </div>
        </template>

        <div class="py-6" v-if="!showAdminDashboard">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Welcome to HedraFarm!</h3>
                        <p>You're logged in as a regular user. Explore our products and place orders.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Dashboard -->
        <div v-if="showAdminDashboard" class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Orders -->
                    <Card class="bg-blue-50 border-blue-200">
                        <template #content>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-600 text-sm font-medium">Total Orders</p>
                                    <p class="text-2xl font-bold text-blue-900">
                                        {{ formatNumber(statistics?.overview?.total_orders || 0) }}
                                    </p>
                                    <p class="text-xs text-blue-500 mt-1">
                                        +{{ statistics?.overview?.recent_orders || 0 }} this week
                                    </p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fa fa-shopping-cart text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <!-- Total Revenue -->
                    <Card class="bg-green-50 border-green-200">
                        <template #content>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-600 text-sm font-medium">Total Revenue</p>
                                    <p class="text-2xl font-bold text-green-900">
                                        {{ formatCurrency(statistics?.overview?.total_revenue || 0) }}
                                    </p>
                                    <p class="text-xs text-green-500 mt-1">
                                        {{ (statistics?.overview?.revenue_growth || 0) > 0 ? '+' : '' }}{{ statistics?.overview?.revenue_growth || 0 }}% from last month
                                    </p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fa fa-dollar-sign text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <!-- Total Products -->
                    <Card class="bg-purple-50 border-purple-200">
                        <template #content>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-600 text-sm font-medium">Total Products</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ formatNumber(statistics?.overview?.total_products || 0) }}
                                    </p>
                                    <p class="text-xs text-purple-500 mt-1">
                                        {{ statistics?.overview?.total_categories || 0 }} categories
                                    </p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class="fa fa-box text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <!-- Total Users -->
                    <Card class="bg-orange-50 border-orange-200">
                        <template #content>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-600 text-sm font-medium">Total Users</p>
                                    <p class="text-2xl font-bold text-orange-900">
                                        {{ formatNumber(statistics?.overview?.total_users || 0) }}
                                    </p>
                                    <p class="text-xs text-orange-500 mt-1">
                                        +{{ statistics?.overview?.recent_users || 0 }} this week
                                    </p>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-full">
                                    <i class="fa fa-users text-orange-600 text-xl"></i>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" v-if="dashboardSettings.show_charts && statistics?.charts">
                    <!-- Orders Trend Chart -->
                    <Card>
                        <template #title>
                            <span class="text-lg font-semibold">Orders & Revenue Trend (Last 30 Days)</span>
                        </template>
                        <template #content>
                            <Chart
                                type="line"
                                :data="ordersChartData"
                                :options="ordersChartOptions"
                                class="h-64"
                            />
                        </template>
                    </Card>

                    <!-- Products by Category Chart -->
                    <Card>
                        <template #title>
                            <span class="text-lg font-semibold">Products by Category</span>
                        </template>
                        <template #content>
                            <Chart
                                type="pie"
                                :data="productsChartData"
                                :options="productsChartOptions"
                                class="h-64"
                            />
                        </template>
                    </Card>
                </div>

                <!-- Alerts & Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Low Stock Alert -->
                    <Card v-if="statistics?.alerts?.low_stock_products?.length > 0">
                        <template #title>
                            <span class="text-lg font-semibold text-red-600">
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                Low Stock Alert
                            </span>
                        </template>
                        <template #content>
                            <div class="space-y-2">
                                <div
                                    v-for="product in statistics.alerts.low_stock_products"
                                    :key="product.name"
                                    class="flex justify-between items-center p-2 bg-red-50 rounded"
                                >
                                    <span class="font-medium">{{ product.name }}</span>
                                    <span class="text-red-600 font-bold">{{ product.quantity }} left</span>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <!-- Quick Stats -->
                    <Card>
                        <template #title>
                            <span class="text-lg font-semibold">Quick Stats</span>
                        </template>
                        <template #content>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Blog Posts:</span>
                                    <span class="font-semibold">{{ formatNumber(statistics?.overview?.total_blogs || 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">This Month Revenue:</span>
                                    <span class="font-semibold">{{ formatCurrency(statistics?.overview?.current_month_revenue || 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Revenue Growth:</span>
                                    <span :class="(statistics?.overview?.revenue_growth || 0) >= 0 ? 'text-green-600' : 'text-red-600'" class="font-semibold">
                                        {{ (statistics?.overview?.revenue_growth || 0) >= 0 ? '+' : '' }}{{ statistics?.overview?.revenue_growth || 0 }}%
                                    </span>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Settings Modal -->
        <Modal :show="showSettingsModal" @close="showSettingsModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Dashboard Settings</h2>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input
                            id="show_charts"
                            type="checkbox"
                            v-model="dashboardSettings.show_charts"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <label for="show_charts" class="ml-2 text-sm text-gray-600">Show Charts</label>
                    </div>

                    <div class="flex items-center">
                        <input
                            id="show_revenue"
                            type="checkbox"
                            v-model="dashboardSettings.show_revenue"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <label for="show_revenue" class="ml-2 text-sm text-gray-600">Show Revenue</label>
                    </div>

                    <div class="flex items-center">
                        <input
                            id="show_recent_activity"
                            type="checkbox"
                            v-model="dashboardSettings.show_recent_activity"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <label for="show_recent_activity" class="ml-2 text-sm text-gray-600">Show Recent Activity</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Auto-refresh interval (seconds)</label>
                        <input
                            type="number"
                            v-model.number="dashboardSettings.refresh_interval"
                            min="10"
                            max="300"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chart Type</label>
                        <select
                            v-model="dashboardSettings.chart_type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="line">Line Chart</option>
                            <option value="bar">Bar Chart</option>
                            <option value="pie">Pie Chart</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <Button
                        label="Cancel"
                        severity="secondary"
                        @click="showSettingsModal = false"
                    />
                    <Button
                        label="Save Settings"
                        @click="saveDashboardSettings"
                    />
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
