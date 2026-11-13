<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sales Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/Logo.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @stack('styles')
</head>

<body>

    <div class="app-layout">

        <!-- SIDEBAR -->
        @include('layouts.salessidebar')

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- TOP NAVBAR -->
            @include('layouts.navbar')

            <!-- PAGE CONTENT -->
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js/counter.js') }}"></script>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-collapsed');

                if (document.body.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebar', 'collapsed');
                } else {
                    localStorage.setItem('sidebar', 'expanded');
                }
            });
        }

        if (localStorage.getItem('sidebar') === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    @stack('scripts')

</body>

</html>

