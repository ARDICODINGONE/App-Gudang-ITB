<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>GUDIT - Gudang Digital</title>
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='15' fill='%23007bff'/%3E%3Ctext x='50' y='65' text-anchor='middle' font-family='-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif' font-size='32' font-weight='600' letter-spacing='-0.5' fill='white'%3EGD%3C/text%3E%3C/svg%3E">

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/dash.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet">
</head>

<body>

    @include('layouts.navbar')

    @yield('content')

    @include('layouts.footer')


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        // Save current page URL to localStorage when not on cart page
        // This allows cart to know where to return to
        function saveReturnUrl() {
            if (window.location.pathname !== '/cart') {
                localStorage.setItem('cartReturnUrl', window.location.pathname + window.location.search);
            }
        }
        
        // Run on page load
        document.addEventListener('DOMContentLoaded', saveReturnUrl);
        
        // Also run immediately in case DOMContentLoaded has already fired
        if (document.readyState === 'loading') {
            // DOM still loading
        } else {
            // DOM already loaded
            saveReturnUrl();
        }
    </script>
</body>

</html>
