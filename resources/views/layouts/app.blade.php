<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Shopping</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href={{ asset('assets/img/favicon/favicon.ico') }} />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href={{ asset('assets/vendor/fonts/boxicons.css') }} />

    <!-- Core CSS -->
    <link rel="stylesheet" href={{ asset('assets/vendor/css/core.css') }} class="template-customizer-core-css" />
    <link rel="stylesheet" href={{ asset('assets/vendor/css/theme-default.css') }} class="template-customizer-theme-css" />
    <link rel="stylesheet" href= {{ asset('assets/css/demo.css') }} />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href={{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }} />
    <link rel="stylesheet" href= {{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }} />
    <link rel="stylesheet" href= {{ asset('assets/vendor/css/pages/page-auth.css') }} />

    <!-- Helpers -->
    <script src={{asset('assets/vendor/js/helpers.js')}}></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
      <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
      <script src={{asset('assets/js/config.js')}}></script>

    <!-- Start Datatable  CSS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@2.8.2/dist/alpine.min.js"></script>


    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
    
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> --}}
    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

      <style>
        /* .layout-navbar .navbar-dropdown .dropdown-menu {
          min-width: 25rem;
          overflow: hidden;
        } */
        .layout-navbar .dropdown-notifications .dropdown-menu{
          min-width: 25rem;
          overflow: hidden;
        }
      </style>
    <!--End Datatable  CSS -->
  </head>

  <body>
    <!-- Layout wrapper -->
    @yield('content')

    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src={{ asset('assets/vendor/libs/jquery/jquery.js') }}></script>
    <script src={{ asset('assets/vendor/libs/popper/popper.js') }}></script>
    <script src={{ asset('assets/vendor/js/bootstrap.js') }}></script>
    <script src={{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}></script>
    <script src={{ asset('assets/vendor/js/menu.js')}}></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src={{ asset('assets/vendor/libs/apex-charts/apexcharts.js')}}></script>

    <!-- Main JS -->
    <script src={{ asset('assets/js/main.js')}}></script>

    <!-- Page JS -->
    <script src={{ asset('assets/js/dashboards-analytics.js')}}></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    @stack('scripts')

    <!-- Place this tag in your head or just before your close body tag. -->
  </body>
</html>
