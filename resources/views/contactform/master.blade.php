<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
      
    
        <!-- Scripts -->
       <!-- @vite(['resources/css/app.css', 'resources/js/app.js'])  -->
       <!-- @vite('resources/css/app.css') -->
        <link type="text/css"  href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

       <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
       @yield('header')
        <script type='text/javascript'>
            let base_url = {app_url: "{{url('/')}}"};
        </script>
</head>
<body class="font-sans antialiased">
      <div class="min-h-screen bg-gray-100">

            @include('contactform.navbar')
    
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Page Footer -->
            <footer>
                <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>                                          
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

                @yield('footer')
             </footer>
      </div>
</body>
</html>