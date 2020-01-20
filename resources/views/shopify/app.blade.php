<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME_FORMATTED')}}</title>

    <meta name="theme-color" content="#0049B0">
    <meta name="msapplication-TileColor" content="#0052CC">
    <meta name="msapplication-TileImage" content="/images/win-150.png">
    <link rel="apple-touch-icon" sizes="180x180" type="image/png" href="/images/win-180.png">
    <link rel="icon" sizes="192x192" type="image/png" href="/images/win-192.png">
    <link rel="icon" sizes="16x16 24x24 32x32 64x64" type="image/x-icon" href="/images/favicon.png">

    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" type="text/css" href="css/slim.css">
    <script type="text/javascript">
        let appCredentials = {
            apiKey: '{{env('SHOPIFY_API_KEY')}}',
            landingPage: '{{route('shopify.landing-page')}}'
        };
    </script>
    

    @yield('javascript')
@yield('css')
</head>
<body>
<div class="flex-center position-ref full-height wrapper">
    @yield('content')
</div>
@if(!isset($skipFoot))
<div class="flex-center position-ref footer">
    <div class="caption text-sm-center black--text">
        <span>© ERA OF </span><span style="color: Yellow; text-shadow: 1px 1px 2px #3d3d3d;">ECOM</span>&nbsp;<span>{{date("Y")}}</span>
    </div>
</div>
@endif
<script>
    function yooo() {
        var shop = document.querySelector('.shopName').value;
        shop = shop.replace('http:', 'https:');
        shop = shop.startsWith('https') ? shop : 'https://'+shop;
        shop = shop.endsWith('/') ? shop : shop+'/';

        window.location.href = shop + 'admin/api/auth?api_key={{env('SHOPIFY_API_KEY')}}';
    }
</script>
</body>
</html>