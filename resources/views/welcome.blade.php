<!doctype html>
<!-- The lang attribute specifies the language of the element's content. -->
<html lang="{{ app()->getLocale() }}"> 
    <head>
        <meta charset="utf-8"><!-- Meta Charset. ... Simply put, when you declare the "charset" as "UTF-8", you are telling your browser to use the UTF-8 character encoding, which is a method of converting your typed characters into machine-readable code -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- it will make this file supported for older version of Internet explorer -->
        <meta name="viewport" content="width=device-width, initial-scale=1"><!-- viewport-> user's visible area of a web page to make it responsive. it was a quick fix -->

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth   <!-- to check the current user is authenticated or not -->
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif  

            <div class="content">
                <div class="title m-b-md">
                    Demo App
                </div>
            </div>
        </div>
    </body>
</html>
