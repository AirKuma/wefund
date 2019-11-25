





<nav class="navbar navbar-light navbar-fixed-top" style="background-color: #fff;border-bottom: 1px solid #dbdbdb;">
<div class="container">
  <button class="navbar-toggler hidden-lg-up collapsed" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">
    &#9776;
  </button>
  <a class="navbar-brand" href="{{ URL::route('index') }}">Loyaus</a>
  <div class="collapse navbar-toggleable" id="exCollapsingNavbar2">

    <ul class="nav navbar-nav">
      <li class="nav-item dropdown">
          <a class="nav-link" href="{{ URL::route('get.auction.index',['college' =>  Auth::check() ? Auth::user()->college()->first()->acronym : 'fju','auction' => 'bid','type' => 'all']) }}">拍賣</a>
      </li>
      <li class="nav-item dropdown">
          <a class="nav-link" href="{{ URL::route('get.auction.index',['college' =>  Auth::check() ? Auth::user()->college()->first()->acronym : 'fju','auction' => 'seek','type' => 'all']) }}">競投</a>
      </li>
      <li class="nav-item dropdown">
          <a class="nav-link" href="{{ URL::route('get.post.index',['billboard' =>  'all','category' => 'all']) }}">話題</a>
      </li>
    </ul>

 <!--  <form class="form-inline pull-xs-right"> -->
    @if(!Auth::check())
    {!! Form::open(['route' => 'users.login', 'method' => 'post', 'class' => 'form-inline pull-xs-right']) !!}
    <div class="form-group">
      {!! Form::label('email', '電子郵件') !!}
      {!! Form::email('email', null, ['class'=> 'form-control', 'placeholder' => '請輸入學校電子郵件','required' => 'required']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('password', '密碼') !!}
      {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '請輸入你的密碼','required' => 'required']) !!}

    </div>
      {!! Form::submit('登入', ['class' => 'btn btn-secondary']) !!}
      <a class="facebookIcon btn btn-secondary" href="{!!URL::to('auth/facebook')!!}" class="btn btn-primary" role="button">Facebook 帳號登入</a>
    {!! Form::close() !!}  
    @else

    <ul class="nav navbar-nav pull-xs-right"> 
    

<!-- {{Auth::user()->first()->read_notification_at < Auth::user()->notifications()->where('is_read', 0)}} -->


            @if(count(Auth::user()->notifications()->where('is_read', 0)->get()) != 0)
            <span id="notification_count">{{ count(Auth::user()->notifications()->where('is_read', 0)->get()) }}</span>
            @endif
          
          
          
          <button id="notificationLink" class="notificationIcon"></button>
          <div id="notificationContainer">
            <div id="notificationTitle">通知</div>
            <div id="notificationsBody" class="notifications">
<div class="list-group">
@if(1)
@foreach(Auth::user()->notifications()->orderBy('created_at', 'desc')->get()->take(10) as $notification)
  
  <a onclick="window.location.href='{{ $notification->link }}'" class="list-group-item list-group-item-action" @if($notification->is_read == 0)style="background-color:#f5f5f5;" @endif>
    <h7 class="list-group-item-heading">@if($notification->notificatable_type != "App\Post" ) <b>@if($notification->sender()->first()->id != 1){{$notification->sender()->first()->lastname }}{{$notification->sender()->first()->firstname }}@endif </b> @endif {{ $notification->content }}</h7>
    <p class="list-group-item-text">{{ $notification->created_at->diffForHumans() }}  </p>
  </a>


@endforeach
@endif
</div>
            </div>
            <div id="notificationFooter" >
                <a onclick="window.location.href='{{ URL::route('get.notification.index') }}'">顯示全部</a> 
                <!-- <a href="{{ URL::route('get.notification.index') }}">顯示全部</a> -->
            </div>
          </div>
        
        <!-- <a href="#"><i class="material-icons" style="float: left; color:#FFF">add_alert</i>  -->
        <!-- <span class="label label-pill label-danger">2</span></a>&nbsp&nbsp -->
        <div class="pull-xs-right">
        <img style="size:30; height: 30px; float: left; " src="@if(Auth::user()->facebook()->first()==null && Auth::user()->gender==1){{ asset('images/default/male.png') }}@elseif(Auth::user()->facebook()->first()==null && Auth::user()->gender==0){{ asset('images/default/female.png') }}@else{{ Auth::user()->facebook->avatar }}@endif" class="img-circle special-img">
        <li class="dropdown nav-item">
        <a  href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> &nbsp @if(Auth::check()){{ Auth::user()->lastname . '' . Auth::user()->firstname}}@endif<span class="caret"></span></a>
          <ul class="dropdown-menu" style="left:-30px;">
            <li><a class="dropdown-item" href="{{ URL::route('get.edit.profile') }}">個人資料</a></li>
            <li><a class="dropdown-item" href="{{ URL::route('get.auction.admin','bid') }}">我的項目</a></li>
            <li><a class="dropdown-item" href="{{ URL::route('get.post.mypost','bookmark') }}">我的文章</a></li>
            @if(Auth::user()->admin_billboards()->first()!=null)<li><a class="dropdown-item" href="{{ URL::route('get.billboard.admin') }}">討論版管理</a></li>@endif
            <div class="dropdown-divider"></div>
            <li><a class="dropdown-item" href="{{ URL::route('user.logout') }}">登出</a></li>

          </ul>
        </li>
        </div>
    </ul>
    @endif
  </div>
</div>
</nav>