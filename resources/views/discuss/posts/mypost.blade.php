@extends('layouts.master')

@section('content')
	<div class="container">

	<div class="row">

<div class="col-lg-3">
  <div class="list-group">
      <a href="{{ URL::route('get.post.mypost','bookmark') }}" class="list-group-item {{ set_active(['post/mypost/bookmark','post/mypost/bookmark']) }}">我的收藏<span class="label label-pill label-warning pull-xs-right">{{ Auth::user()->user_bookmarks()->where('bookmarkable_type','App\Post')->count() }}</span></a>
      <a href="{{ URL::route('get.post.mypost','my') }}" class="list-group-item {{ set_active(['post/mypost/my','post/mypost/my']) }}">我的發文<span class="label label-pill label-warning pull-xs-right">{{ Auth::user()->post()->count() }}</span></a>
    </div>
</div>

<div class="col-lg-9 search">
  <div>
    @if(count($posts) == 0)
    <h4><center>@if($type=='bookmark')暫無收藏@else暫無貼文@endif</center></h4>
    @else
    <ul class="list-group" id="post">
        @foreach($posts as $key => $post)
        <!-- Split button -->
         <!--  <div class="btn-group pull-xs-right @if($key!=0)toggle_margin @endif">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle fix-height" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
              @endif
            </div>
          </div> -->

           <li class="list-group-item">
              <img class="avatar pull-xs-left " src="@if( ($post->user()->first()->facebook()->first() == ''  || $post->anonymous==1) && $post->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif(($post->user()->first()->facebook()->first() == ''  || $post->anonymous==1) && $post->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $post->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
              <h5 class="list-group-item-heading">
              <a href="@if($post->link!=null){{ $post->link }} @else {{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }} @endif" @if($post->link!=null)target="_blank"@endif>{{ str_limit($post->title, $limit = 50, $end = '...') }}</a>
              @if($post->link!=null)<button class="linkSmallIcon pull-xs-right"></buton>@endif
              </h5>
              <p class="list-group-item-text">
              <a href="{{ URL::route('get.discuss.post.show',['billboard' => $post->billboard()->first()->domain,'id' => $post->id]) }}"><button class="commentIcon"></button> {{ $post->comments()->count() }}</a>  
              <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check() || Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)<button class="bookmarkIcon" title="我要收藏"></button>@else<button class="bookmarkIcon2" title="取消收藏"></button>@endif</a>{{ $post->bookmarks()->count() }}
               @if($post->billboards()->first()!=null)<a href="{{ URL::route('get.post.index',['billboard' =>  $post->billboard()->first()->domain,'category' => $post->billboards()->first()->id]) }}" class="label label-success">{{ $post->billboards()->first()->name }}</a> @endif
                · <font class="small">{{ $post->created_at->diffForHumans() }}</font>  
              <span class="pull-right-480">
                <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}">
                  @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',1)->first()==null)<button class="upIcon"></button>@else<button class="upIcon2"></button>@endif</a> 
                  {{ $post->votes()->where('vote',1)->count()-$post->votes()->where('vote',0)->count() }} 
                  <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}">
                  @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',0)->first()==null)<button class="downIcon"></button>@else<button class="downIcon2"></button>@endif</a></span>
             </p>
            </li>

         <!--檢舉modal-->
        <!-- <div class="modal fade" id="reportPostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="reportPostModal-{{ $post->id }}" aria-hidden="true">
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
          <!-- <div class="modal fade" id="deleteItemModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteItemModal-{{ $post->id }}" aria-hidden="true">
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
                  {!! Form::hidden('returnback', 0) !!}
                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div> -->
            <!--封鎖modal-->
          <!-- <div class="modal fade" id="blockPostModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="blockPostModal-{{ $post->id }}" aria-hidden="true">
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
                  {!! Form::hidden('returnback', 0) !!}
                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div> -->
  
      @endforeach
    </ul>
   @endif   

   <center>{!! $posts->render() !!}</center>
  </div>
</div>

 </div>
</div>

@endsection
