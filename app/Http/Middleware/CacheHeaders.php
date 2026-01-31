<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     * Sets appropriate cache headers for static assets.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = $request->path();
        
        // Get file extension
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        // Define cache durations (in seconds)
        $oneYear = 31536000;
        $oneDay = 86400;
        
        // Static assets that should be cached for 1 year
        $longCacheExtensions = [
            'css', 'js',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico',
            'woff', 'woff2', 'ttf', 'otf', 'eot'
        ];
        
        if (in_array(strtolower($extension), $longCacheExtensions)) {
            $response->headers->set('Cache-Control', 'public, max-age=' . $oneYear . ', immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $oneYear) . ' GMT');
            
            // Add CORS for fonts
            if (in_array(strtolower($extension), ['woff', 'woff2', 'ttf', 'otf', 'eot'])) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            }
        }
        
        return $response;
    }
}
