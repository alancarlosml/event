<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Ticket DZ6</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"> --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/css/OverlayScrollbars.css" integrity="sha512-Ho1L8FTfzcVPAlvfkL1BV/Lmy1JDUVAP82/LkhmKbRX5PnQ7CNDHAUp2GZe7ybBpovS+ssJDf+SlBOswrpFr8g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
        <!-- {!! RecaptchaV3::initJs() !!} -->
        @stack('head')

        <style>
            .text-red-600 { color: #e53e3e; }
            .text-green-600 { color: #38a169; }
            .text-gray-600 { color: #718096; }

            .text-base { font-size: 1rem; }
            .text-sm { font-size: .875rem; }
            .text-lg { font-size: 1.125rem; }
            .text-xl { font-size: 1.25rem; }
            .font-medium { font-weight: 500; }

            .ftco-section .ftco-section {
                padding: 7em 0; 
            }

            .ftco-section .logo img {
                width: 300px;
            }

            .ftco-section .ftco-no-pt {
                padding-top: 0; 
            }

            .ftco-section .ftco-no-pb {
                padding-bottom: 0; 
            }

            .ftco-section .heading-section {
                font-size: 28px;
                color: #000; 
            }

            .ftco-section .img {
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center; 
            }

            .ftco-section .wrap {
                width: 100%;
                overflow: hidden;
                background: #fff;
                border-radius: 5px;
                -webkit-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
                -moz-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
                box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24); 
            }

            .ftco-section .img, .ftco-section .login-wrap {
                width: 100%; 
            }
            @media (max-width: 991.98px) {
                .ftco-section .img, .ftco-section .login-wrap {
                    width: 100%; 
                } 
            }

            @media (max-width: 767.98px) {
            .ftco-section .ftco-section .wrap .img {
                    height: 250px; 
                } 
            }

            .ftco-section .login-wrap {
                position: relative;
                background: #fff h3;
                background-font-weight: 300; 
            }

            .ftco-section .form-group {
                position: relative; 
            }
            .ftco-section .form-group .label {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
                font-weight: 700; 
            }

            .ftco-section .form-group a {
                color: gray; 
            }

            .ftco-section .form-control {
                height: 48px;
                background: #fff;
                color: #000;
                font-size: 16px;
                border-radius: 5px;
                -webkit-box-shadow: none;
                box-shadow: none;
                border: 1px solid rgba(0, 0, 0, 0.1); 
            }
            .ftco-section .form-control::-webkit-input-placeholder {
                /* Chrome/Opera/Safari */
                color: rgba(0, 0, 0, 0.2) !important; 
            }
            .ftco-section .form-control::-moz-placeholder {
                /* Firefox 19+ */
                color: rgba(0, 0, 0, 0.2) !important; 
            }
            .ftco-section .form-control:-ms-input-placeholder {
                /* IE 10+ */
                color: rgba(0, 0, 0, 0.2) !important; 
            }
            .ftco-section .form-control:-moz-placeholder {
                /* Firefox 18- */
                color: rgba(0, 0, 0, 0.2) !important; 
            }
            .ftco-section .form-control:focus, .ftco-section .form-control:active {
                outline: none !important;
                -webkit-box-shadow: none;
                box-shadow: none;
                border: 1px solid #e3b04b; 
            }

            .ftco-section .social-media {
                position: relative;
                width: 100%; 
            }

            .ftco-section .social-media .social-icon {
                display: block;
                width: 40px;
                height: 40px;
                background: transparent;
                border: 1px solid rgba(0, 0, 0, 0.05);
                font-size: 16px;
                margin-right: 5px;
                border-radius: 50%; 
            }
            
            .ftco-section .social-media .social-icon span {
                color: #999999; 
            }
            .ftco-section .social-media .social-icon:hover, .ftco-section .social-media .social-icon:focus {
                background: #e3b04b; 
            }
            .ftco-section .social-media .social-icon:hover span, .ftco-section .social-media .social-icon:focus span {
                color: #fff; 
            }

            .ftco-section .checkbox-wrap {
                display: block;
                position: relative;
                padding-left: 30px;
                margin-bottom: 12px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 500;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none; 
            }

            /* Hide the browser's default checkbox */
            .ftco-section .checkbox-wrap input {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                height: 0;
                width: 0; 
            }

            /* Create a custom checkbox */
            .ftco-section .checkmark {
                position: absolute;
                top: 0;
                left: 0; 
            }

            /* Create the checkmark/indicator (hidden when not checked) */
            .ftco-section .checkmark:after {
                content: "\f0c8";
                font-family: "FontAwesome";
                position: absolute;
                color: rgba(0, 0, 0, 0.1);
                font-size: 20px;
                margin-top: -4px;
                -webkit-transition: 0.3s;
                -o-transition: 0.3s;
                transition: 0.3s; 
            }
            @media (prefers-reduced-motion: reduce) {
                .ftco-section .checkmark:after {
                    -webkit-transition: none;
                    -o-transition: none;
                    transition: none; 
                } 
            }

            /* Show the checkmark when checked */
            .ftco-section .checkbox-wrap input:checked ~ .checkmark:after {
                display: block;
                content: "\f14a";
                font-family: "FontAwesome";
                color: rgba(0, 0, 0, 0.2); 
            }

            /* Style the checkmark/indicator */
            .ftco-section .checkbox-primary {
                color: #e3b04b; 
            }

            .ftco-section .checkbox-primary input:checked ~ .checkmark:after {
                color: #e3b04b; 
            }

            .ftco-section .btn {
                cursor: pointer;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                font-size: 16px;
                padding: 10px 20px; 
                font-weight: 600;
            }
            .ftco-section .btn:hover, .ftco-section .btn:active, .ftco-section .btn:focus {
                outline: none; 
            }

            .ftco-section .btn.btn-primary {
                background: #185ca4 !important;
                border: 1px solid #4f9bd5 !important;
                color: #fff !important; 
            }

            .ftco-section .btn.btn-primary:hover {
                border: 1px solid #185ca4;
                background: #4f9bd5 !important;
                /* color: #4f9bd5;  */
            }

            .ftco-section .btn.btn-primary.btn-outline-primary {
                border: 1px solid #e3b04b;
                background: transparent;
                color: #e3b04b; 
            }

            .ftco-section .btn.btn-primary.btn-outline-primary:hover {
                border: 1px solid transparent;
                background: #e3b04b;
                color: #fff; 
            }

        </style>

    </head>
    <body class="hold-transition login-page">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/js/OverlayScrollbars.min.js" integrity="sha512-5UqQ4jRiUk3pxl9wZxAtep5wCxqcoo6Yu4FI5ufygoOMV2I2/lOtH1YjKdt3FcQ9uhcKFJapG0HAQ0oTC5LnOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('footer')
</html>