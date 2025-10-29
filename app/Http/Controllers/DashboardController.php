<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductCategory;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user && ($user->team_id == '1' || $user->team_id == 1);

        if ($isAdmin) {
            try {
                $statistics = $this->getAdminStatistics();
                return Inertia::render('Dashboard', compact('statistics', 'isAdmin'));
            } catch (\Exception $e) {
                Log::error('Dashboard statistics error: ' . $e->getMessage());
                // Return admin dashboard with empty statistics
                return Inertia::render('Dashboard', [
                    'isAdmin' => true,
                    'statistics' => $this->getEmptyStatistics()
                ]);
            }
        }

        return Inertia::render('Dashboard', ['isAdmin' => false, 'statistics' => null]);
    }

    private function getEmptyStatistics()
    {
        return [
            'overview' => [
                'total_users' => 0,
                'total_products' => 0,
                'total_orders' => 0,
                'total_categories' => 0,
                'total_blogs' => 0,
                'total_revenue' => 0,
                'recent_orders' => 0,
                'recent_users' => 0,
                'current_month_revenue' => 0,
                'revenue_growth' => 0
            ],
            'charts' => [
                'orders_by_status' => [],
                'orders_trend' => [],
                'products_by_category' => [],
            ],
            'alerts' => [
                'low_stock_products' => [],
            ]
        ];
    }

    public function getAdminStatistics()
    {
        // Get basic counts
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = ProductCategory::count();
        $totalBlogs = Blog::count();

        // Calculate total revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        // Get recent orders (last 7 days)
        $recentOrders = Order::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Get orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Get orders trend (last 30 days)
        $ordersTrend = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_price) as revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Get products by category
        $productsByCategory = Product::join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->select('product_categories.name', DB::raw('count(*) as count'))
            ->groupBy('product_categories.name', 'product_categories.id')
            ->get()
            ->toArray();

        // Get low stock products (quantity < 10)
        $lowStockProducts = Product::where('quantity', '<', 10)
            ->select('name', 'quantity')
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get()
            ->toArray();

        // Get recent users (last 7 days)
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Monthly revenue comparison
        $currentMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_price');

        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return [
            'overview' => [
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_categories' => $totalCategories,
                'total_blogs' => $totalBlogs,
                'total_revenue' => $totalRevenue,
                'recent_orders' => $recentOrders,
                'recent_users' => $recentUsers,
                'current_month_revenue' => $currentMonthRevenue,
                'revenue_growth' => round($revenueGrowth, 2)
            ],
            'charts' => [
                'orders_by_status' => $ordersByStatus,
                'orders_trend' => $ordersTrend,
                'products_by_category' => $productsByCategory,
            ],
            'alerts' => [
                'low_stock_products' => $lowStockProducts,
            ]
        ];
    }

    public function getDashboardSettings()
    {
        $user = Auth::user();

        // You can store dashboard preferences in user settings or a separate table
        // For now, return default settings
        return response()->json([
            'success' => true,
            'data' => [
                'show_charts' => true,
                'show_revenue' => true,
                'show_recent_activity' => true,
                'refresh_interval' => 30, // seconds
                'chart_type' => 'line', // line, bar, pie
                'theme' => 'light' // light, dark
            ]
        ]);
    }

    public function updateDashboardSettings(Request $request)
    {
        $user = Auth::user();

        // Validate settings
        $validated = $request->validate([
            'show_charts' => 'boolean',
            'show_revenue' => 'boolean',
            'show_recent_activity' => 'boolean',
            'refresh_interval' => 'integer|min:10|max:300',
            'chart_type' => 'in:line,bar,pie',
            'theme' => 'in:light,dark'
        ]);

        // Here you would save the settings to database
        // For now, just return success

        return response()->json([
            'success' => true,
            'message' => 'Dashboard settings updated successfully'
        ]);
    }
}
