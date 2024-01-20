<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
        google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
        active: function() {
            sessionStorage.fonts = true;
        }
        });
    </script>
    <style>
        body {
            display: flex;
            flex-direction: column;
        }
        html, body {
            height: 100%;
            margin: 0px;
            padding: 0px;
            font-size: 14px;
            font-weight: 300;
            font-family: Poppins;
            -ms-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile).m-grid--root {
            flex: 1;
        }
        .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile) {
            display: flex;
            flex-direction: column;
        }
        .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile) > .m-grid__item.m-grid__item--fluid {
            flex: 1 0 auto;
        }
        .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile) > .m-grid__item {
            flex: none;
        }
        .m-error-6 {
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .m-error-6 .m-error_container {
            text-align: center;
        }
        .m--font-light {
            color: #ffffff !important;
        }
        .m-error-6 .m-error_container .m-error_subtitle > h1 {
            font-size: 10rem;
            margin-top: 12rem;
            font-weight: 600;
        }
        .m-error-6 .m-error_container .m-error_description {
            margin-top: 3rem;
            font-size: 2.3rem;
            font-weight: 500;
            line-height: 3rem;
        }

        .m-error_container .m-error_description {
            color: #CC6622;
        }
        .m--font-light {
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <div class="m-grid__item m-grid__item--fluid m-grid  m-error-6" style="background-image: url('{{ asset('assets/media/img/error/bg6.jpg') }}'); flex: 1 0 auto;">
        <div class="m-error_container">
            <div class="m-error_subtitle m--font-light">
                <h1>
                    Oops...
                </h1>
            </div>
            <p class="m-error_description m--font-light">
                Looks like something went wrong.
                <br>
                We're working on it
            </p>
        </div>
    </div>
</body>
</html>