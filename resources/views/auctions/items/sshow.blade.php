
<!DOCTYPE html>
<html ng-app="app">
  <head>
    <title> @if($item != null) Loyaus {{ $item->name }} @else Loyaus 項目不見了 @endif </title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  @if($item != null)
    @if($images->first()!=null)
      <meta property="og:image" content="{{ Request::root() }}/images/auctions/{{ $images->first()->file_name }}"/>
    @endif  
      <meta property="og:title" content="[{{$item->category()->first()->name}}] {{ $item->name }}"/>
      <meta property="og:site_name" content="輔大Loyaus拍賣平台"/>
      <meta property="og:description" content="NT$ {{ $item->price }}" />
  @endif
      <meta property="og:type" content="blog"/>
      <meta property="og:url" content="{{ Request::url() }}"/>
      <meta property="fb:app_id" content="298858140449739"/>

       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


<!--     
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta property="og:image" content="http://d3v9w2rcr4yc0o.cloudfront.net/uploads/hourliesAttachments/2015/03/4badd40a1440b874f12a94a237484829.jpg" />
    <!-- Bootstrap CSS --> 
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->

    <!-- Bootstrap -->
    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
      <script src="//code.jquery.com/jquery.js"></script>
      <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.1.0/dist/jquery.countdown.min.js"></script>
      <link rel="stylesheet" href="/css/loyaus.css">
  </head>
  <body style="background-color: #fafafa;" ng-app="loyaus">



<div class="container" >





      <div class="row" style="margin-top:50px;">


        <!-- /.blog-sidebar -->
        	
	        <div class="col-lg-6 col-centered">
            <div class="card-header">
              <a href="{{ URL::route('index') }}">Layous</a>
            </div>
			<div class="card card-block" style="background-color: #FFFFFF;">
			@if($item->disabled==1)	
        <h4>此項目已遭封鎖</h4>
      @else
        <h4 class="card-title"><span class="label label-danger">@if($auction=='bid')拍賣@else競投@endif</span> @if($item->free==1)<span class="freeIcon"></span> @endif @if($item->new==1)<span class="newIcon"></span>@endif {{ $item->name }}</h4>
				<p class="card-text">
				@if($images->count()==1)
        <img src="/images/auctions/{{ $images->first()->file_name }}"　class="img-rounded" style="max-width:100%;" alt="">
        @elseif($images->count()>1)
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              @foreach ($images as $key => $image)
              <li data-target="#carousel-example-generic" data-slide-to="{{ $key }}" @if($key==0)class="active" @endif></li>
              @endforeach
            </ol>
            <div class="carousel-inner" role="listbox" style="">
              @foreach ($images as $key => $image)
              <div class="itemimgs carousel-item @if($key==0)active @endif">
                    <img id="centerimg" src="/images/auctions/{{ $image->file_name }}" class="img-rounded" style="max-height:100%;" alt="">
              </div>
              @endforeach
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="icon-prev" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="icon-next" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        @endif
				</p>
				<p class="card-text">
          <div class="col-lg-12">
          <div class="@if($item->free==0)col-lg-8 @else col-lg-12 @endif">
            分類：{{ $item->category()->first()->name }}<br />
            <span class="block-480">開始時間：</span>{{ $item->start_time }}<br />
            <span class="block-480">剩餘時間：</span><span data-countdown="{{ $item->end_time }}"></span>
          </div>
          @if($item->free==0)
          <div class="col-lg-4">
            底價：NT${{ $item->price }}
          </div>
          @endif
        </div>
          <div class="col-lg-12">
             <center>@if($item->free==0)
              目前標價：<span class="item_price">@if(count($item->users()->get()) == 0)NT${{ $item->price }} @else ${{ $item_user->first()->pivot->price }} @endif</span>
              @else
              <span class="item_price">免費</span>
              @endif
            </center>
            {!! nl2br(e($item->description)) !!}</br>
          </div>
				</p>
        <center>
        @if($item->end_time > $now)
          <a href="{{ URL::route('index') }}" class="btn btn-secondary @if($item->free==0)bidIcon @else wantIcon2 @endif">@if($item->free==0)我要出價@else我想要@endif</a> 
			  @else
          @if($item_user->first()!=null)
            <h5>@if($item->type==0)拍賣@else競投@endif已經結束，此項目由<b>{{ $item_user->first()->lastname. '' .$item_user->first()->firstname }}</b>@if($item->free==1)獲得@else得標@endif！</h5>
          @else
            <h5>@if($item->type==0)拍賣@else競投@endif已經結束。</h5>  
          @endif   
        @endif
        </center>
       @endif 
      </div>
	  

	

@if(count($item_user->first()) != 0 && $item->free==0)
			<div class="card card-block" style="background-color: #FFFFFF;">
			  <h5 class="card-title text-center" >出價記錄 <span class="label label-pill label-warning">{{ count($item_user) }}</h5>
			  <p class="card-text">
@foreach($item_user->take(5) as $user) </span>

<div class="media">
<div class="media-left">
<img style="height: 30px; " src="{{ $user->facebook()->first() == '' ? 'http://teambeyond.net/forum/public/style_images/custom__4_/profile/default_large.png' : $user->facebook->avatar }}" class="img-circle special-img media-object">
</div>
<div class="media-body">
<h4 class="media-heading">${{ $user->pivot->price }}</h4>
<p>{{ $user->major()->first()->name }}</p>

</div>
</div>

@endforeach

			  </p>
			</div>
      @endif

      @if(count($item->comments()->get()) != 0)
      
      <div class="card card-block" style="background-color: #FFFFFF;">
        <h4 class="card-title">留言</h4>
        <p class="card-text">
        @foreach ($item->comments()->orderby('created_at','desc')->get() as $comments)
          <div class="media">
          <div class="media-left">
          <img style="height: 40px;" class="media-object img-circle " src="@if($comments->user()->first()->facebook()->first()==null && $comments->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif($comments->user()->first()->facebook()->first()==null && $comments->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $comments->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
          </div>
          <div class="media-body">
            <h4 class="media-heading">{{ $comments->user()->first()->lastname. '' .$comments->user()->first()->firstname }}</h4>
            <p>{{ $comments->content }}<br />
            <font color="#7e7e7e">{{ $comments->created_at->diffForHumans() }}</font></p>
               
          </div>
          </div>
        @endforeach
        </p>
      </div>
      @endif
        </div>
      </div>
       </div>     

<script type="text/javascript">

  $('[data-countdown]').each(function() {
    var $this = $(this),
        finalDate = $(this).data('countdown');

    $this.countdown(finalDate, function(event) {
      $this.html(event.strftime('%D 天 %H 時 %M 分 %S 秒'));
    });
  });
</script>

<!-- !-->



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
  </body>
</html>






