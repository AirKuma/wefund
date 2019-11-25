<!DOCTYPE html>
<html ng-app="app">
  <head>
    <title>
      @if(Route::currentRouteName() == 'get.auction.index' || Route::currentRouteName() == 'get.auction.item' || Route::currentRouteName() == 'get.auction.item.edit')
        Loyaus 
        @if($auction=='bid')
          @if(Route::currentRouteName() == 'get.auction.index')
            拍賣
          @elseif(Route::currentRouteName() == 'get.auction.item')
            新增拍賣
          @else
            編輯拍賣
          @endif
        @else
          @if(Route::currentRouteName() == 'get.auction.index')
            競投
          @elseif(Route::currentRouteName() == 'get.auction.item')
            新增競投
          @else
            編輯競投
          @endif
        @endif
      @elseif(Route::currentRouteName() == 'get.auction.item.show')
        @if($item !=null)
          Loyaus {{ $item->name }}
        @else
          Loyaus 項目不見了
        @endif
      @elseif(Route::currentRouteName() == 'get.auction.admin')
        Loyaus 我的項目
      @elseif(Route::currentRouteName() == 'get.billboard.index')
        Loyaus 討論版
      @elseif(Route::currentRouteName() == 'get.discuss.billboard')
        Loyaus 新增討論版
      @elseif(Route::currentRouteName() == 'get.discuss.billboard.edit')
        Loyaus 編輯討論版
      @elseif(Route::currentRouteName() == 'get.billboard.admin')
        Loyaus 討論版管理
      @elseif(Route::currentRouteName() == 'get.billboard.subscriber')
        Loyaus {{ $billboard->name }}訂閱者
      @elseif(Route::currentRouteName() == 'get.billboard.applysubscriber')
        Loyaus {{ $billboard->name }}申請訂閱者
      @elseif(Route::currentRouteName() == 'get.billboard.category')
        Loyaus {{ $billboard->name }}類別管理
      @elseif(Route::currentRouteName() == 'get.post.index')
        Loyaus 話題
      @elseif(Route::currentRouteName() == 'get.discuss.post')
        Loyaus 新增文章
      @elseif(Route::currentRouteName() == 'get.discuss.post.show')
        @if(!$post)
          Loyaus 文章不見了
        @else
          {{ $post->title }}
        @endif
      @elseif(Route::currentRouteName() == 'get.discuss.post.edit')
        Loyaus 編輯文章
      @elseif(Route::currentRouteName() == 'get.post.mypost')
        Loyaus 我的文章
      @elseif(Route::currentRouteName() == 'get.notification.index')
        Loyaus 我的通知
      @elseif(Route::currentRouteName() == 'users.login')
        Loyaus 登入
      @elseif(Route::currentRouteName() == 'users.register')
        Loyaus 註冊
      @elseif(Route::currentRouteName() == 'get.edit.profile' || Route::currentRouteName() == 'get.edit.account' || Route::currentRouteName() == 'get.edit.fb')
        Loyaus 個人資料
      @else
        Loyaus
      @endif
    </title> 

       <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="token" id="token" value="{{ csrf_token() }}">
       <link rel="icon" type="image/png" href="{{ asset('images/default/favicon.ico') }}" sizes="32x32">
       <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
      <script src="//code.jquery.com/jquery.js"></script>
      <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.1.0/dist/jquery.countdown.min.js"></script>
      
      <link href="{{ asset('bootstrap-4.0.0-alpha/dist/css/bootstrap.min.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="/css/loyaus.css">




    <style>
    #menu{
      list-style:none;
      margin: 20px;
      padding: 0px;
      font: normal 12px Tahoma;
      text-transform: uppercase;
    }
    #menu li {
      float: left;
      background: #0489B1;
      box-shadow: 1px 0 17px #fff;
      position:relative;
    }

     
    #menu li a {
      color:#fff;
      text-decoration:none;
      display: inline-block;
      padding: 15px 25px;
      background: #0489B1;
    }
    #menu li a:hover{
      color:#58D3F7;
      background: #086A87;
      text-shadow: 0 1px 2px #2EFEF7;
    }
        #notificationContainer {
            background-color: #fff;
            border: 1px solid #E6E6E6;
            -webkit-box-shadow: 0 4px 8px rgba(0, 0, 0, .25);
            overflow: visible;
            position: absolute;
            top: 90%;
            right: 5%;
            z-index: 1;
            display: none;
        }
        #notificationContainer:before {
            content: '';
            display: block;
            position: absolute;
            left: -10px;
            width: 0;
            height: 0;
            color: transparent;
            border: 10px solid black;
            border-color: transparent transparent #F2F2F2;
            margin-top: -20px;
            margin-left: 50%;
        }
        #notificationTitle {
            font-weight: bold;
            padding: 8px;
            font-size: 13px;
            background-color: #F2F2F2;
            /*position: fixed;
            z-index: 1000;
            width: 334px;*/
            border-bottom: 1px solid #dddddd;
        }
        #notificationsBody {
            padding: 0px 0px 0px 0px !important;
            min-height:200px;
            max-height: 300px;
            overflow-y: auto;
        }
        #notificationFooter {
            background-color: #e9eaed;
            text-align: center;
            font-weight: bold;
            padding: 8px;
            font-size: 12px;
            border-top: 1px solid #dddddd;
        }
        #notificationFooter a{
            padding: 5px 10px !important;
        }
        #notification_count {
            padding: 1px 5px 1px 5px;
            background: #cc0000;
            color: #ffffff;
            font-weight: bold;
            border-radius: 9px;
            -moz-border-radius: 9px;
            -webkit-border-radius: 9px;
            position: relative;
            left: 40px;
            bottom: 10px;
            font-size: 12px;
        }
        @media screen and (min-width: 700px) {
          #notificationContainer {
            top: 90%;
            right: 5%;
            margin-left: 50%;
            width: 350px;
          }
          #notificationsBody {
            max-height: 400px;
          }
        }
         @media screen and (max-width: 700px) {
          #notificationContainer {
            left: 5%;
          }
         }
  </style>


    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-83222765-1', 'auto');
      ga('send', 'pageview');

    </script>
      
  </head>
  <body  ng-app="loyaus">
<!-- Include Form -->
@include('layouts.partials.navigation')

<br />
<br />
<br />
@yield('content')
<script type="text/javascript" src="{{ asset('js/vendor.js') }}" ></script>
@stack('scripts')

@include('layouts.partials.footer')

