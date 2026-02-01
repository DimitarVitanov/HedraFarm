<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', 'Хедерафарм+') }}</title>
        <link rel="icon" href="/assets/img/logo/logo.png">

        <!-- LocalBusiness Schema for SEO -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Pharmacy",
            "name": "Хедра Фарм - Apteka Strumica",
            "alternateName": ["Hedera Farm", "Аптека Струмица", "Apteki Strumica"],
            "description": "Apteka Strumica - Аптека во Струмица. Онлајн аптека со широк избор на лекови, козметика и витамини.",
            "url": "https://hederafarmplus.mk",
            "logo": "https://hederafarmplus.mk/assets/img/logo/logo.png",
            "image": "https://hederafarmplus.mk/assets/img/logo/logo.png",
            "telephone": "+389 70 123 456",
            "email": "info@hederafarmplus.mk",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "ул. Маршал Тито",
                "addressLocality": "Струмица",
                "addressRegion": "Струмица",
                "postalCode": "2400",
                "addressCountry": "MK"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": "41.4378",
                "longitude": "22.6427"
            },
            "openingHoursSpecification": [
                {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                    "opens": "08:00",
                    "closes": "20:00"
                },
                {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": "Saturday",
                    "opens": "09:00",
                    "closes": "14:00"
                }
            ],
            "priceRange": "$$",
            "currenciesAccepted": "MKD",
            "paymentAccepted": "Cash, Credit Card",
            "areaServed": {
                "@type": "City",
                "name": "Струмица"
            },
            "sameAs": [
                "https://www.facebook.com/hederafarmplus",
                "https://www.instagram.com/hederafarmplus"
            ]
        }
        </script>

        <!-- Preconnect to external domains -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Font display swap override for FontAwesome -->
        <style>
            @font-face { font-family: "Font Awesome 6 Pro"; font-display: swap !important; }
            @font-face { font-family: "Font Awesome 6 Brands"; font-display: swap !important; }
            @font-face { font-family: "Font Awesome 6 Duotone"; font-display: swap !important; }
            @font-face { font-family: "Font Awesome 6 Sharp"; font-display: swap !important; }
            @font-face { font-family: "Font Awesome 5 Pro"; font-display: swap !important; }
            @font-face { font-family: "Font Awesome 5 Brands"; font-display: swap !important; }
        </style>
        
        <!-- Preload critical FontAwesome fonts (hashes from production build) -->
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $faFonts = array_filter(array_keys($manifest), fn($k) => str_contains($k, 'fa-') && str_ends_with($k, '.woff2'));
        @endphp
        @foreach($faFonts as $font)
            <link rel="preload" href="/build/{{ $manifest[$font]['file'] }}" as="font" type="font/woff2" crossorigin>
        @endforeach
        
        <!-- Preload critical CSS -->
        <link rel="preload" href="/assets/css/bootstrap.min.css" as="style">
        <link rel="preload" href="/assets/css/style.css" as="style">
        
        <!-- Critical CSS (Bootstrap + main styles) - loaded synchronously -->
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        
        <!-- Non-critical CSS - loaded asynchronously -->
        <!-- FontAwesome is loaded via Vite in app.js, no need to load again -->
        <link rel="stylesheet" href="/assets/css/animate.min.css" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="/assets/css/magnific-popup.min.css" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="/assets/css/jquery-ui.min.css" media="print" onload="this.media='all'">
        
        <!-- Fallback for browsers with JS disabled -->
        <noscript>
            <link rel="stylesheet" href="/assets/css/all-fontawesome.min.css">
            <link rel="stylesheet" href="/assets/css/animate.min.css">
            <link rel="stylesheet" href="/assets/css/magnific-popup.min.css">
            <link rel="stylesheet" href="/assets/css/jquery-ui.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
        </noscript>
        
        <!-- Fonts - loaded with display=swap for non-blocking -->
        <!-- Inter and Roboto are loaded from Google Fonts, Figtree removed (unused) -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        
        <!-- Scripts -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        <!-- JavaScript - jQuery must load first, then plugins -->
        <script src="/assets/js/jquery-3.7.1.min.js"></script>
        <script src="/assets/js/modernizr.min.js" defer></script>
        <script src="/assets/js/bootstrap.bundle.min.js" defer></script>
        <script src="/assets/js/imagesloaded.pkgd.min.js" defer></script>
        <script src="/assets/js/jquery.magnific-popup.min.js" defer></script>
        <script src="/assets/js/isotope.pkgd.min.js" defer></script>
        <script src="/assets/js/jquery.appear.min.js" defer></script>
        <script src="/assets/js/jquery.easing.min.js" defer></script>
        <script src="/assets/js/jquery-ui.min.js" defer></script>
        <script src="/assets/js/wow.min.js" defer></script>
        <script src="/assets/js/main.js" defer></script>
    </body>
</html>
