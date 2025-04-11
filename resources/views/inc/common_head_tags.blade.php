<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
@vite('resources/css/ckeditor-tailwind-reset.css')
@vite('resources/css/app.css')
<link rel="stylesheet" href="/fontawesome/css/all.min.css">
<link rel="icon" href="favicon.ico" type="image/x-icon">
<meta property="og:url" content="{{ $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ env('APP_NAME', 'Cuisine Compass') }}" />
<meta property="snapchat:sticker" content="{{ env('SNAPCHAT_STICKER_URL', '') }}" />
<meta property="snapchat:app_id" content="{{ env('SNAPCHAT_APP_ID', '') }}" />
<meta property="snapchat:publisher_id" content="{{ env('SNAPCHAT_PUBLISHER_ID', '') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css"
integrity="sha512-eMxdaSf5XW3ZW1wZCrWItO2jZ7A9FhuZfjVdztr7ZsKNOmt6TUMTQgfpNoVRyfPE5S9BC0A4suXzsGSrAOWcoQ=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"
integrity="sha512-j+F4W//4Pu39at5I8HC8q2l1BNz4OF3ju39HyWeqKQagW6ww3ZF9gFcu8rzUbyTDY7gEo/vqqzGte0UPpo65QQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script>
    window.twttr = (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function(f) {
            t._e.push(f);
        };
        return t;
    }(document, "script", "twitter-wjs"));
</script>
<script>
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js,
            sjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://sdk.snapkit.com/js/v1/create.js";
        sjs.parentNode.insertBefore(js, sjs);
    })(document, "script", "snapkit-creative-kit-sdk");
</script>
<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
<script src="https://platform.linkedin.com/in.js" type="text/javascript">
    lang: en_US
</script>
