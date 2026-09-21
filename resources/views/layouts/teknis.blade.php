<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        {{ $title ?? 'Dashboard' }} | Sistem SPJ
    </title>


    @vite([
    'resources/css/teknis.css',
    'resources/js/app.js'
    ])

</head>


<body>

    <div class="admin-layout">


        @include('components.teknis.sidebar')


        <main class="main-content">


            @include('components.teknis.navbar')


            <div class="content">

                @yield('content')

            </div>


        </main>

    </div>


    <script>
        function toggleSidebar() {
            const sidebar =
                document.querySelector('.sidebar');

            sidebar.classList.toggle('show');
        }
    </script>

</body>

</html>