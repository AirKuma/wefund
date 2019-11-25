@extends('layouts.master')

@section('content')
	<div class="container">

	<div class="row">


<div class="col-lg-3">
  <!-- <button class="navbar-toggler hidden-lg-up collapsed" type="button" data-toggle="collapse" data-target="#billboards" style="padding: .5rem 0rem;">
    <a href="{{ URL::route('get.post.index',['billboard' =>  'all','category' => 'all']) }}" class="list-group-item ">討論版&#9776;</a>
  </button> -->
  <!-- <div class="collapse navbar-toggleable" id="billboards"> -->
  <div class="list-group">
      <a href="{{ URL::route('get.post.index',['billboard' =>  'all','category' => 'all']) }}" class="list-group-item @if($billboard == 'all' )active @endif">全部<span class="label label-pill label-warning pull-xs-right">{{ $allcount }}</span></a>
      @foreach($subscriptions as $subscription)
        <a title="{{ $subscription->name }}" href="{{ URL::route('get.post.index',['billboard' => $subscription->domain,'category' => 'all']) }}" class="list-group-item @if($billboard == $subscription->domain )active @endif">{{ str_limit($subscription->name, $limit = 10, $end = '...') }}@if($subscription->type==1) <button class="secret" title="私密討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->anonymous==1)<button class="anonymous" title="匿名討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @elseif($subscription->anonymous==0)<button class="optanonymous" title="選擇性匿名討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->target==1)<button class="male" title="限定男性" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @elseif($subscription->target==2)<button class="female" title="限定女性" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->adult==1) <button class="adult" title="18禁" style="padding: 10px 7px 5px 8px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->limit_college==1) <button class="college" title="只限制{{ $subscription->college()->first()->name }}學生發言" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif<span class="label label-pill label-warning pull-xs-right">{{ $allbillboard->where('id',$subscription->id)->first()->posts()->where('status',0)->where('created_at','<=',\Carbon\Carbon::now())->where('created_at','>=',\Carbon\Carbon::today())->count() }}</span></a>
      @endforeach
    </div>
    <a href="{{ URL::route('get.billboard.index') }}" class="moreIcon list-group-item">更多討論版</a>
  <!-- </div> -->
</div>
 



<div class="col-lg-6 search discuss-padding">
   @if($billboard!='all' && $categories->count()!=0)
          <div style="margin-bottom:10px;">  
            <div class="dropdown">  <!-- dropdown 加 margin-bottom：10px -->
                 <button type="button" class="btn dropdown-toggle" id="rank" 
                    data-toggle="dropdown">                 
                   @if($category!='all'){{ $categories->where('id',(int)$category)->first()->name }}@else全部分類@endif
                    <span class="caret"></span>
                 </button>
                 <ul class="dropdown-menu" role="menu" aria-labelledby="rank">
                  <a href="{{ URL::route('get.post.index',['billboard' =>  $billboard,'category' => 'all']) }}" class="dropdown-item">全部分類</a>
                  @foreach($categories as $category)
                    <li role="presentation">
                       <a href="{{ URL::route('get.post.index',['billboard' =>  $billboard,'category' => $category->id]) }}" class="dropdown-item">{{ $category->name }}</a>
                    </li>
                  @endforeach
                 </ul>
            </div>
          </div>
@endif

<!-- CHUEN -->
<!-- <ul class="list-group">
 <a href="#" class="list-group-item  list-group-item-action">
    <button class="moreOption pull-xs-right"></button>
    <img   style="height: 50px;padding-right: 15px;" class="pull-xs-left " src="http://teambeyond.net/forum/public/style_images/custom__4_/profile/default_large.png" >
    <h5 class="list-group-item-heading">
    <button class="pushPinIcon"></button>
    Pokémon GO掀熱潮不同學校因抓怪認識
    </h5>
    <p class="list-group-item-text">[靠北]   <button class="commentIcon"></button>22 <button class="bookmarkIcon"></button>35 · 10 minutes ago  </p>

  </a>

</ul>
<br /> -->
<!-- CHUEN -->


  <div>
    @if($billboard!='all' && $allbillboard->where('domain',$billboard)->first()->status==1)
    <h4><center>此討論版已遭封鎖</h4>
    @elseif(count($posts) == 0)
    <h4><center>暫無貼文</center></h4>
    @elseif(!Auth::check() && $billboard!='all' && $allbillboard->where('domain',$billboard)->first()->target!=0)
    <h4><center>此討論版只能給@if($allbillboard->where('domain',$billboard)->first()->target==1)男@else女性看@endif。</center></h4>
    @elseif($billboard!='all' && $allbillboard->where('domain',$billboard)->first()->target==1 && Auth::user()->gender!=1)
    <h4><center>此討論版只能給男性看。</center></h4>
    @elseif($billboard!='all' && $allbillboard->where('domain',$billboard)->first()->target==2 && Auth::user()->gender!=0)
    <h4><center>此討論版只能給女性看。</center></h4>
    @elseif($billboard!='all' && $allbillboard->where('domain',$billboard)->first()->type==1 && $subscriptions->where('domain',$billboard)->first()==null)
    <h4><center>此討論版為私密版，請先訂閱才可察看貼文。</center></h4>
    @else
    <ul class="list-group" id="post">
        @foreach($posts as $key => $post)
        <!-- Split button -->
        <!-- @if(Auth::check())
          <div class="btn-group pull-xs-right @if($key!=0)toggle_margin @endif">
            <button type="button" class="moreOption    fix-height" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" class="dropdown-item" data-toggle="modal" data-target="#reportPostModal-{{ $post->id }}" data-whatever="@mdo">舉報</a>
              @if($post->user()->first()->id == Auth::id())
              <a class="dropdown-item" href="{{ URL::route('get.discuss.post.edit',['id' => $post->id,'domain' => $post->billboard()->first()->domain]) }}">編輯</a>
              <a class="dropdown-item" data-toggle="modal" data-target="#deleteItemModal-{{ $post->id }}" data-whatever="@mdo">刪除</a>
              @endif
              @if($post->billboard()->first()->admins()->where('user_id',Auth::id())->first() != null)
              <a class="dropdown-item" data-toggle="modal" data-target="#blockPostModal-{{ $post->id }}" data-whatever="@mdo">封鎖</a>
              @if($billboard!='all')
              <a class="dropdown-item" data-toggle="modal" data-target="#setPriorityPostModal-{{ $post->id }}" data-whatever="@mdo">頂置設定</a>
              @endif
              @endif
            </div>
          </div>
        @endif -->
           <li class="list-group-item">
              <img class="avatar pull-xs-left " src="@if( ($post->user()->first()->facebook()->first() == ''  || $post->anonymous==1) && $post->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif(($post->user()->first()->facebook()->first() == ''  || $post->anonymous==1) && $post->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $post->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
              <h5 class="list-group-item-heading">
              @if($post->priority==1)<button class="pushPinIcongreen"></button>@elseif($post->priority==2)<button class="pushPinIconyellow"></button>@elseif($post->priority==3)<button class="pushPinIconred"></button>@endif
              <a href="@if($post->link!=null){{ $post->link }} @else {{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }} @endif" @if($post->link!=null)target="_blank"@endif>{{ str_limit($post->title, $limit = 30, $end = '...') }}</a>
              @if($post->link!=null)<button class="linkSmallIcon pull-xs-right"></buton>@endif
              </h5>
              <p class="list-group-item-text">
              <a href="{{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }}"><button class="commentIcon"></button> {{ $post->comments()->count() }}</a>  
              <!-- <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check() || Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)<button class="bookmarkIcon" title="我要收藏"></button>@else<button class="bookmarkIcon2" title="取消收藏"></button>@endif</a>{{ $post->bookmarks()->count() }} -->
              <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check() || Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)<button class="bookmarkIcon" title="我要收藏"></button>@else<button class="bookmarkIcon2" title="取消收藏"></button>@endif</a>{{ $post->bookmarks()->count() }}
               @if($post->billboards()->first()!=null)<a href="{{ URL::route('get.post.index',['billboard' =>  $post->billboard()->first()->domain,'category' => $post->billboards()->first()->id]) }}" class="label label-success">{{ $post->billboards()->first()->name }}</a> @endif
                · <font class="small">{{ $post->created_at->diffForHumans() }}</font>  
              <span class="pull-right-480">
                <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}">
                <!-- <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}"> -->
                  @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',1)->first()==null)<button class="upIcon"></button>@else<button class="upIcon2"></button>@endif</a> 
                {{ $post->votes()->where('vote',1)->count()-$post->votes()->where('vote',0)->count() }} 
                <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}">
                <!-- <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}"> -->
                  @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',0)->first()==null)<button class="downIcon"></button>@else<button class="downIcon2"></button>@endif</a>
              </span>
             </p>
            </li>
        <!-- <div class="media">
          <a class="media-left" href="#">
          <img style="height: 40px;" class="media-object img-circle " src="{{ $post->user()->first()->facebook()->first() == ''  || $post->anonymous==1 ? 'http://teambeyond.net/forum/public/style_images/custom__4_/profile/default_large.png' : $post->user()->first()->facebook->avatar }}" alt="Photo of Pukeko in New Zealand">
          </a>
          <div class="media-body">
            <a href="@if($post->link!=null){{ $post->link }} @else {{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }} @endif"><h4 class="media-heading">@if($post->billboards()->first()!=null)<span class="small">[{{ $post->billboards()->first()->name }}]</span> @endif{{ $post->title }}</h4></a>
            <font color="#7e7e7e">{{ $post->created_at->diffForHumans() }}</font></p>
             <a href="{{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }}" style="padding-right:10px;">{{ $post->comments()->count() }}則留言</a>  
             <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check())我要收藏@elseif(Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)我要收藏@else取消收藏@endif</a>
             {{ $post->bookmarks()->count() }}人收藏
             <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}">up</a> {{ $post->votes()->where('vote',1)->count()-$post->votes()->where('vote',0)->count() }} <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}">down</a>
          </div>
        </div> -->

         <!--檢舉modal-->
       <!--  <div class="modal fade" id="reportPostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="reportPostModal-{{ $post->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">檢舉貼文</h4>
              </div>
              {!! Form::open(['action' => ['Discuss\PostController@postReport', 'id' => $post->id], 'method' => 'post', 'role' => 'form']) !!}

                 @include('auctions.items.forms.report', ['submitButtonText' => '檢舉'])

                {!! Form::close() !!}
              
            </div>
          </div>
        </div> -->
         <!--刪除modal-->
         <!--  <div class="modal fade" id="deleteItemModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteItemModal-{{ $post->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteItemModal">刪除貼文</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\PostController@destroyPost', 'id' => $post->id], 'method' => 'delete', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要刪除該貼文？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div> -->
            <!--封鎖modal-->
         <!--  <div class="modal fade" id="blockPostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="blockPostModal-{{ $post->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteItemModal">封鎖貼文</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\PostController@postBlock','id' => $post->id], 'method' => 'post', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要封鎖該貼文？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div> -->

          <!--頂置貼文設定modal-->
         <!--  <div class="modal fade" id="setPriorityPostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="setPriorityPostModal-{{ $post->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="setPriorityPostModal">頂置貼文設定</h4>
                </div>
                {!! Form::model($post, ['method' => 'PATCH', 'action' => ['Discuss\PostController@patchSetpriority',$post->id]]) !!}
                   
                   @include('discuss.posts.forms.setpriority', ['submitButtonText' => '設定'])

                  {!! Form::close() !!}
                
              </div>
            </div>
          </div> -->
  
      @endforeach
    </ul>
   @endif   
   <center>{!! $posts->render() !!}</center>
  </div>
</div>

<div class="col-lg-3">
  <div class="card card-block" style="background-color: #FFFFFF;">
   @if($billboard!='all')
      <a href="{{ URL::route('get.discuss.post',['type' => 'link','domain' => $billboard]) }} " class="linkIcon btn btn-secondary btn-block">新增連結</a>
        <a href="{{ URL::route('get.discuss.post',['type' => 'content','domain' => $billboard]) }} " class="contentIcon btn btn-secondary btn-block">新增內容</a>
  </div>
  <div class="card card-block" style="background-color: #FFFFFF;">
        <h4 class="card-title">{{ $billboarddata->name }}</h4>
        @if($billboarddata->type==1)<button class="secret" title="私密討論版"></button> @endif @if($billboarddata->anonymous==1)<button class="anonymous" title="匿名討論版"></button> @elseif($billboarddata->anonymous==0)<button class="optanonymous" title="選擇性匿名討論版"></button> @endif @if($billboarddata->target==1)<button class="male" title="限定男性"></button> @elseif($billboarddata->target==2)<button class="female" title="限定女性"></button> @endif @if($billboarddata->adult==1) <button class="adult" title="18禁"></button> @endif @if($billboarddata->limit_college==1)<button class="college" title="只限制{{ $billboarddata->college()->first()->name }}學生發言"></button> @endif
        <br><br>
        {!! nl2br(e($billboarddata->description)) !!}<br><br>
        <a href="{{ URL::route('get.billboard.subscriber',['id' => $billboarddata->id]) }}" class="personIcon btn btn-secondary">{{ $billboarddata->subscriptions()->where('allow',0)->count() }}</a>
        @if($billboarddata->status==0)
        <button @if(!Auth::check() || ($billboarddata->target==1 && Auth::user()->gender!=1) || ($billboarddata->target==2 && Auth::user()->gender!=0))disabled="disabled" title="無法訂閱該版" @endif class="btn btn-secondary" style="padding: 0px 0px 0px 6px;" data-toggle="modal" data-target="#subscriptionPostModal" data-whatever="@mdo">@if(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboarddata->id)->first()!=null && Auth::user()->subscriptions()->where('subscriptable_id',$billboarddata->id)->first()->allow==1)<span class="subtractionIcon">待批准</span>@elseif(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboarddata->id)->first()!=null)<span class="subtractionIcon">取消訂閱</span>@else<span class="plusIcon">訂閱</span>@endif</button><br>
        @endif
  </div>
    @if($billboarddata->admins()->where('user_id',Auth::id())->first() != null)
        <div class="card card-block" style="background-color: #FFFFFF;">
           <!--  <a href="{{ URL::route('get.discuss.billboard.edit',['id' => $billboarddata->id]) }}" class="btn btn-primary">修改</a>
            <button data-toggle="modal" data-target="#deleteItemModal" data-whatever="@mdo" type="button" class="btn btn-danger">刪除</button>
            <button data-toggle="modal" data-target="#blockPostModal" data-whatever="@mdo" type="button" class="btn btn-danger">封鎖</button>
            <a href="{{ URL::route('get.billboard.category',['id' => $billboarddata->id]) }}" class="btn btn-primary">類別管理</a> -->
            <a href="{{ URL::route('get.billboard.admin') }}" class="settingIcon btn btn-secondary btn-block">管理平台</a>
        </div>
    @endif
  @else
    <a href="{{ URL::route('get.discuss.post',['type' => 'link','domain' => 'all']) }} " class="linkIcon btn btn-secondary btn-block">新增連結</a>
    <a href="{{ URL::route('get.discuss.post',['type' => 'content','domain' => 'all']) }} " class="contentIcon btn btn-secondary btn-block">新增內容</a><br>
  @endif
</div>

    </div>

  </div>

@if($billboard!='all') 
  @if(Auth::check()) 
     <!--訂閱modal-->
       <div class="modal fade" id="subscriptionPostModal" tabindex="-1" role="dialog" aria-labelledby="subscriptionPostModal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="subscriptionPostModal">訂閱討論版</h4>
            </div>
        {!! Form::open(['action' => ['Discuss\BillboardController@postSubscription','id' => $billboarddata->id], 'method' => 'post', 'role' => 'form']) !!}
        <div class="modal-body">
          @if(Auth::user()->subscriptions()->where('subscriptable_id',$billboarddata->id)->first()==null)
          <h6>確定要訂閱該討論版？</h6>
          @if($billboarddata->type==1)
          <h6 class="help-block text-danger">※注意！討論版是私密，需經過版主批准才可訂閱</h6>
          @endif
          @else
          <h6>確定要取消訂閱該討論版？</h6>
          @endif
               <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
               </div>

              {!! Form::close() !!}
           </div> 
          </div>
        </div>
      </div>
    @endif
@endif

@endsection

