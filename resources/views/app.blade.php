<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ANISA JATIM</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('/assets/vendors/mdi/css/materialdesignicons.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/assets/vendors/css/vendor.bundle.base.css')}}" type="text/css">

    <link rel="stylesheet" href="{{asset('/assets/css/style.css')}}" type="text/css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
   {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}

    @vite('resources/css/app.css')
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
        <div id="app">
            <App />
        </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    @vite('resources/js/app.js')
    <script src="{{asset('/assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('/assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('/assets/js/jquery.cookie.js')}}" type="text/javascript")></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('/assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('/assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('/assets/js/misc.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('/assets/js/dashboard.js')}}"></script>
    <script src="{{asset('/assets/js/todolist.js')}}"></script>
    <!-- End custom js for this page -->

  </body>
</html>
