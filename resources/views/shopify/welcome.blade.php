@extends('shopify.app')

@section('content')
    <div class="content">
        <div class="flex-center">
            <img src="/images/winAds-navLogo.png" alt="{{env('APP_NAME_FORMATTED')}}" title="{{env('APP_NAME_FORMATTED')}}" class="logo" />
        </div>
        <br />
        <hr style="width: 50%; margin: 0px auto;" class="whiteHR"/>
        <br />

        <div class="links">
            <form action="/" onsubmit="event.preventDefault(); yooo();">
                <input type="text" class="shopName" required
                       name="shopName" placeholder="Enter the shop URL where you want to install WinAds.">
                <button type="submit" name="install" class="clean-gray">Install App</button>
                <br />
                <span class="info">i.e. <i>https://forum.myshopify.com</i></span>
            </form>
        </div>
        <script type="text/javascript">
            var urlParams = new URLSearchParams(window.location.search);
            var shopName = document.querySelector('.shopName');
            if (urlParams.has('shop')) {
                shopName.value = urlParams.get('shop');
            }
        </script>
    </div>
@endsection
