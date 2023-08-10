<!DOCTYPE html>
<html>

<head>
    <title>funDeal</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon2.ico') }}">

    <!-- plugin css -->
    <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <!-- end plugin css -->

    @stack('plugin-styles')

    <style type="text/css">
        .card-title {
            text-transform: none !important;
        }

    </style>

    <!-- common css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}" class="sidebar-dark">

    <script src="{{ asset('assets/js/spinner.js') }}"></script>

    <div class="main-wrapper" id="app">
        @include('layout.sidebar')
        <div class="page-wrapper">
            @include('layout.header')
            <div class="page-content">
                @yield('content')
            </div>
            @include('layout.footer')
        </div>
    </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->

    <script>
        var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
            'nineteen'
        ];

        function convert_millions(num) {
            if (num >= 1000000) {
                return convert_millions(Math.floor(num / 1000000)) + " million " + convert_thousands(num % 1000000);
            } else {
                return convert_thousands(num);
            }
        }

        function convert_thousands(num) {
            if (num >= 1000) {
                return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
            } else {
                return convert_hundreds(num);
            }
        }

        function convert_hundreds(num) {
            if (num > 99) {
                return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
            } else {
                return convert_tens(num);
            }
        }

        function convert_tens(num) {
            if (num < 10) return ones[num];
            else if (num >= 10 && num < 20) return teens[num - 10];
            else {
                return tens[Math.floor(num / 10)] + " " + ones[num % 10];
            }
        }

        function convert(num) {
            if (num == 0) return "zero";
            else return convert_millions(num);
        }
    </script>

    @stack('custom-scripts')
</body>

</html>
