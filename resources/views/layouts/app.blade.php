<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

        <!-- Styles -->
        <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        {{-- sweet alert 2 --}}
        <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">

        <!-- Custom styles for this template-->
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/custom.css')}}" rel="stylesheet">
        {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

        <!-- Scripts -->
        {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

        {{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> --}}


        @yield('styles')
    </head>
    <body id="page-top" class="font-sans antialiased">

        <div id="wrapper">

            @include('layouts.sidebar')
            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">
                    {{-- Top Bar --}}
                    @include('layouts.topbar')
                    <div class="container-fluid">

                        @yield('header')

                        <!-- Page Content -->
                        <main>
                            {{ $slot }}
                        </main>
                    </div>

                </div>

            </div>

            {{-- <div id="content" class="min-h-screen bg-gray-100"> --}}
                {{-- @include('layouts.navigation') --}}

                {{-- <div class="container-fluid">
                    <!-- Page Heading -->
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div> --}}
            {{-- </div> --}}

        </div>


        @yield('modals')
         <!-- Bootstrap core JavaScript-->
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> --}}
        <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('vendor/jquery/jquery.ui.min.js')}}"></script>
        <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

        <script src="{{asset('vendor/fontawesome-free/js/all.min.js')}}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        {{-- sweet alert 2 --}}
        <script src="{{asset('js/sweetalert2.all.min.js')}}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
        <script src="{{asset('js/app.js')}}"></script>
        @yield('scripts')
    </body>
</html>
