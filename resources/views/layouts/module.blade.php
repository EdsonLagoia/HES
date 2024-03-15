<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistema de Registro de Entrada</title>
        <!-- CSS -->
        <link href="/css/style.css" rel="stylesheet">
        <link href="/css/menu.css" rel="stylesheet">
        <!-- JQuery -->
        <script src="/js/jquery.min.js" type="text/javascript"></script>
        <!-- FontAwesome -->
        <link href="/fontawesome/css/fontawesome.css" rel="stylesheet">
        <link href="/fontawesome/css/all.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <script src="/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <!-- DataTables -->
        <link href="/css/datatables.min.css" rel="stylesheet">
        <script src="/js/datatables.min.js" type="text/javascript"></script>
        <!-- JQuery Mask -->
        <script src="/js/jquery.mask.min.js" type="text/javascript"></script>
    </head>
    <body id="body">
        @include('layouts.menu')

        <main
            @if(isset($home))
                class="d-flex align-items-center" style="height: calc(100vh - 80px)"
            @else
                style="height: calc(100vh - 80px)"
            @endif
        >
            <div class="container bg-system">
                <div class="col-sm-12 container">
                    @include('layouts.notification')

                    @yield('content')
                </div>
            </div>
        </main>

        <script src="/js/notification.js" type="text/javascript"></script>
        <script src="/js/menu.js" type="text/javascript"></script>
        <script src="/js/table.js" type="text/javascript"></script>
        <script src="/js/forms.js" type="text/javascript"></script>
    </body>
</html>
