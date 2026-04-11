<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Blog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $staticPages = [
            ['loc' => 'https://hederafarmplus.mk/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => 'https://hederafarmplus.mk/store', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => 'https://hederafarmplus.mk/about', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => 'https://hederafarmplus.mk/contact', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => 'https://hederafarmplus.mk/blogs', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => 'https://hederafarmplus.mk/delivery', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => 'https://hederafarmplus.mk/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $products = Product::where('is_active', 1)->select('id', 'updated_at', 'created_at')->get();
        $blogs = Blog::select('id', 'updated_at', 'created_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticPages as $page) {
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>' . $page['loc'] . '</loc>' . "\n";
            $xml .= '        <lastmod>' . now()->format('Y-m-d') . '</lastmod>' . "\n";
            $xml .= '        <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            $xml .= '        <priority>' . $page['priority'] . '</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }

        foreach ($products as $product) {
            $lastmod = ($product->updated_at ?? $product->created_at ?? now())->format('Y-m-d');
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>https://hederafarmplus.mk/products/' . $product->id . '/view</loc>' . "\n";
            $xml .= '        <lastmod>' . $lastmod . '</lastmod>' . "\n";
            $xml .= '        <changefreq>weekly</changefreq>' . "\n";
            $xml .= '        <priority>0.8</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }

        foreach ($blogs as $blog) {
            $lastmod = ($blog->updated_at ?? $blog->created_at ?? now())->format('Y-m-d');
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>https://hederafarmplus.mk/blogs/' . $blog->id . '/read</loc>' . "\n";
            $xml .= '        <lastmod>' . $lastmod . '</lastmod>' . "\n";
            $xml .= '        <changefreq>monthly</changefreq>' . "\n";
            $xml .= '        <priority>0.6</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
