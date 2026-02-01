<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', 'Хедерафарм+') }}</title>
        <link rel="icon" href="/assets/img/logo/logo.png">

        <!-- Preconnect to external domains -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
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
        
        <!-- Preload critical CSS -->
        <link rel="preload" href="/assets/css/bootstrap.min.css" as="style">
        <link rel="preload" href="/assets/css/style.css" as="style">
        
        <!-- Critical CSS (Bootstrap + main styles) - loaded synchronously -->
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        
        <!-- Non-critical CSS - loaded asynchronously -->
        <link rel="stylesheet" href="/assets/css/all-fontawesome.min.css" media="print" onload="this.media='all'">
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
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
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
