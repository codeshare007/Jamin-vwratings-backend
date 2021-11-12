<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stalkalot Promo</title>
    {{--    <link rel="icon" href="/favicon.ico">--}}
    {{--    <link rel="icon" href="/favicon.svg" type="image/svg+xml">--}}
    {{--    <link rel="apple-touch-icon" href="/apple-touch-icon.png">--}}

    <?php $timer = 10 ?>
    <style>
        body {
            background: #2c3e50;
            color: white;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        h1 {
            font-family: 'Helvetica', sans-serif;
            font-weight: normal;
            margin-bottom: 30px;
        }

        .promoPage {
            min-height: inherit;
        }

        .promoPage__header {
            padding: 10px;
        }

        .promoPage__content {
            display: block;
            width: 100%;
            height: calc(100% - 118px);
            min-height: inherit;
        }
    </style>
</head>

<body>
    <div class="promoPage">
        <div class="promoPage__header">
            <h1>Continue staling in <span id="countdown">{{ $timer }}</span></h1>

            <hr>
        </div>
        <div class="promoPage__content">

        </div>
    </div>
    <script>
        /*! js-cookie v3.0.1 | MIT */
        !function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self,function(){var n=e.Cookies,o=e.Cookies=t();o.noConflict=function(){return e.Cookies=n,o}}())}(this,(function(){"use strict";function e(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)e[o]=n[o]}return e}return function t(n,o){function r(t,r,i){if("undefined"!=typeof document){"number"==typeof(i=e({},o,i)).expires&&(i.expires=new Date(Date.now()+864e5*i.expires)),i.expires&&(i.expires=i.expires.toUTCString()),t=encodeURIComponent(t).replace(/%(2[346B]|5E|60|7C)/g,decodeURIComponent).replace(/[()]/g,escape);var c="";for(var u in i)i[u]&&(c+="; "+u,!0!==i[u]&&(c+="="+i[u].split(";")[0]));return document.cookie=t+"="+n.write(r,t)+c}}return Object.create({set:r,get:function(e){if("undefined"!=typeof document&&(!arguments.length||e)){for(var t=document.cookie?document.cookie.split("; "):[],o={},r=0;r<t.length;r++){var i=t[r].split("="),c=i.slice(1).join("=");try{var u=decodeURIComponent(i[0]);if(o[u]=n.read(c,u),e===u)break}catch(e){}}return e?o[e]:o}},remove:function(t,n){r(t,"",e({},n,{expires:-1}))},withAttributes:function(n){return t(this.converter,e({},this.attributes,n))},withConverter:function(n){return t(e({},this.converter,n),this.attributes)}},{attributes:{value:Object.freeze(o)},converter:{value:Object.freeze(n)}})}({read:function(e){return'"'===e[0]&&(e=e.slice(1,-1)),e.replace(/(%[\dA-F]{2})+/gi,decodeURIComponent)},write:function(e){return encodeURIComponent(e).replace(/%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,decodeURIComponent)}},{path:"/"})}));

        var timeleft = 10;
        var downloadTimer = setInterval(function(){
            if(timeleft <= 0){
                clearInterval(downloadTimer);
                Cookies.set('promo', 1);
                var lastPage = Cookies.get('last_page')
                Cookies.remove('last_page')
                window.location = lastPage;
            } else {
                document.getElementById("countdown").innerHTML = timeleft;
            }
            timeleft -= 1;
        }, 1000);
    </script>
</body>
</html>
