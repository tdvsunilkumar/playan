<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'ERPGO')}} - Signed PDF</title>
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" id="main-style-link">
    <style>
        .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 75vh;
        height: 75vh;
        margin: auto;
        animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* center content */
        html, body, #body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }
        #body {
            display: table;
            overflow: hidden;
        }

        .content {
            display: table-cell;
            margin: 0;
            padding: 0;

            text-align: center;
            vertical-align: middle;
        }
        .center-content{
            position: absolute;
            top: 45%;
            left: 40%;
            color:#f10808;
            text-align:left;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <div id="body">
        <div class="content">
            <div class="loader">
            </div>
            <p class="text-danger center-content text-end" style="font-family: Arial;">
               @if ($signType =="2")
                Please Wait .....
                </br>
                User document has  digitally signed by the SYSTEM
                @else
                
                @endif
            </p>
        </div>
        <iframe id="pdf-iframe" src="{{$url}}" width="100%" height="100%"></iframe> 
    </div>
</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
    function preloader(immune, background, color) {
        $("body").prepend('<div class="preloader immune white"><span class="loading-bar blue-colored"></span><i class="radial-loader blue-colored"></i></div>');
    };
    preloader(true, 'white', 'blue');
    $(document).ready( function() {
        setTimeout(function () {
            $('.preloader').fadeOut();
        }, 500 + 300 * (Math.random() * 5));
    });
    $('#pdf-iframe').hide()
    $('#pdf-iframe').on("load", function() {
        $('#body .content').remove();
        $('#pdf-iframe').show()
    })
</script>
</html>