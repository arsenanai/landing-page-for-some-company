<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cybersec') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">
    <link rel="stylesheet" href="{{ asset('css/main.css')}}">
    <link rel="stylesheet" href="{{ asset('css/alertify.core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alertify.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    @yield('style')
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <div id="wrapper">
        @include('layouts.admin.navbar')
        <div id="page-wrapper">
            <div class="container">
                @if (Entrust::can('manage_content') AND Request::url()!=url('/'))
                    <div class="row">
                        <div class="col-md-3">
                            @include('layouts.admin.sidebar')
                        </div>
                        <div class="col-md-9">
                            @yield('content')
                        </div>
                    </div>    
                @else
                    @yield('content')
                @endif
                </div>
            </div>    
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{--<script src="{{ asset('js/main.js') }}"></script>--}}
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script type="text/javascript">
        /*common scripts for all pages. Use this if need blade template. For other purpose use main.js*/
        (function() {
            @if(Session::has('success'))
                alertify.success("{{ Session::get('success') }}");
            @endif
            @if(Session::has('warning'))
                alertify.error("{{ Session::get('warning') }}");
            @endif
        })();
        var contentSensitivePage = false;
        var setFormSubmitting = function() { formSubmitting = true; };
        window.onload = function() {
            window.addEventListener("beforeunload", function (e) {
                if (contentSensitivePage==false) {
                    return undefined;
                }else{
                    var confirmationMessage = "@lang('Несохраненные изменения будут утеряны, вы уверены? ')";
                    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
                }
            });
        };
    </script>
    @yield('script')
</body>
</html>
