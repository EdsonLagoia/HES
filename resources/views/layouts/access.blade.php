<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistema de Registro de Entrada</title>
        <!-- CSS -->
        <link href="/css/style.css" rel="stylesheet">
            <!-- FontAwesome -->
        <link href="/fontawesome/css/fontawesome.css" rel="stylesheet">
        <link href="/fontawesome/css/all.css" rel="stylesheet">
            <!-- Bootstrap -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <!-- JavaScript -->
            <!-- JQuery -->
        <script src="/js/jquery.min.js" type="text/javascript"></script>
            <!-- JQueryMask -->
        <script src="/js/jquery.mask.min.js" type="text/javascript"></script>
            <!-- Bootstrap -->
        <script src="/js/bootstrap.bundle.min.js" type="text/javascript"></script>
            <!-- JavaScript -->
        <script src="/js/notification.js" type="text/javascript"></script>
    </head>

    <body>
        <main class="d-flex align-items-center justify-content-center" style="height: 100vh">
            <div class="col-sm-4 login-card">
                <div class="mb-2 text-center">
                    <img src="/img/logo.png" alt="Logo HES" class="logo">
                    <h3>Sistema de Registro de Entrada</h3>
                </div>

                @include('layouts.notification')

                @yield('content')
            </div>
        </main>
    </body>
</html>
