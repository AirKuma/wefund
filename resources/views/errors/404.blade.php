<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8">
        <title>Loyaus Be right back</title>
        <link rel="icon" type="image/png" href="{{ asset('images/default/favicon.ico') }}" sizes="32x32">
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: Microsoft JhengHei,"Helvetica Neue",Helvetica,Arial,sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">找不到此頁面！</div>
                <a href="{{ URL::route('index') }}">回到Loyaus</a>
            </div>
        </div>
    </body>
</html>
