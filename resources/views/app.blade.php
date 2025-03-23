<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
        
        <!-- Dark Mode Script -->
        <script>
            // Inisialisasi dark mode ketika dokumen pertama kali dimuat
            (function() {
                try {
                    const isDarkMode = localStorage.getItem('dark-mode') === 'true' || 
                                     (localStorage.getItem('dark-mode') === null && 
                                      window.matchMedia('(prefers-color-scheme: dark)').matches);
                    
                    if (isDarkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) {
                    console.error('Error initializing dark mode:', e);
                }
            })();
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia

        <script>
            window.addEventListener('load', function() {
                console.log('Window loaded');
                if (window.Alpine) console.log('Alpine loaded');
                if (window.Vue) console.log('Vue loaded');
                if (window.Inertia) console.log('Inertia loaded');
            });
        </script>
    </body>
</html>
