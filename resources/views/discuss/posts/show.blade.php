@extends('layouts.master')

@section('content')
  <div class="container">

  <div class="row">
<div class="col-md-12">
  @include('errors.list')
</div>

<div class="col-lg-3">
  <div class="list-group">
      <a href="{{ URL::route('get.post.index',['billboard' =>  'all','category' => 'all']) }}" class="list-group-item @if($billboard == 'all' )active @endif">全部<span class="label label-pill label-warning pull-xs-right">{{ $allcount }}</span></a>
      @foreach($subscriptions as $subscription)
        <a href="{{ URL::route('get.post.index',['billboard' => $subscription->domain,'category' => 'all']) }}" class="list-group-item @if($billboard == $subscription->domain )active @endif">{{ str_limit($subscription->name, $limit = 10, $end = '...') }}@if($subscription->type==1) <button class="secret" title="私密討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->anonymous==1)<button class="anonymous" title="匿名討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @elseif($subscription->anonymous==0)<button class="optanonymous" title="選擇性匿名討論版" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->target==1)<button class="male" title="限定男性" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @elseif($subscription->target==2)<button class="female" title="限定女性" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->adult==1) <button class="adult" title="18禁" style="padding: 10px 7px 5px 8px;width: 14px;background-size: 16px;"></button> @endif @if($subscription->limit_college==1) <button class="college" title="只限制{{ $subscription->college()->first()->name }}學生發言" style="padding: 10px 7px 5px 7px;width: 14px;background-size: 16px;"></button> @endif<span class="label label-pill label-warning pull-xs-right">{{ $allbillboard->where('id',$subscription->id)->first()->posts()->where('status',0)->where('created_at','<=',\Carbon\Carbon::now())->where('created_at','>=',\Carbon\Carbon::today())->count() }}</span></a>
      @endforeach
  </div>
  <a href="{{ URL::route('get.billboard.index') }}" class="moreIcon list-group-item">更多討論版</a>
</div>

<div class="col-lg-6 search discuss-padding">
  <div class="card-header">
        <h4 class="card-title" style="margin-bottom:0rem;">{{ $post->title }}</h4>
  </div>
  <div class="card card-block" style="background-color: #FFFFFF;">
      @if($post->billboard()->first()->status==1)
      <h4>此討論版已遭封鎖</h4>
      @elseif($post->status==1)
      <h4>此文章已遭封鎖</h4>
      @else
      <div class="small">
      @if($post->billboards()->first()!=null)<span class="label label-success">{{ $post->billboards()->first()->name }}</span>@endif
        @if($post->anonymous==1)匿名 @else{{ $post->user()->first()->username }} @endif
        {{ $post->user()->first()->college()->first()->name }}
        {{ $post->user()->first()->major()->first()->name }}
        {{ $post->created_at }}

      @if($post->status==0 && $post->billboard()->first()->status==0)
      <span id="post" class="pull-right-480">
        <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}">
          @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',1)->first()==null)<button class="upIcon"></button>@else<button class="upIcon2"></button>@endif</a> 
          {{ $post->votes()->where('vote',1)->count()-$post->votes()->where('vote',0)->count() }} 
          <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}">
            @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',0)->first()==null)<button class="downIcon"></button>@else<button class="downIcon2"></button>@endif</a>
        <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check() || Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)<button class="bookmarkIcon" title="我要收藏"></button>@else<button class="bookmarkIcon2" title="取消收藏"></button>@endif</a>{{ $post->bookmarks()->count() }}
        @if(Auth::check())
        <button data-toggle="modal" data-target="#reportPostModal" data-whatever="@mdo" type="button" class="reportIcon" title="舉報"></button>
        @endif
      </span>  
      @endif

      </div>
       <br />  
      @if($post->link!=null)
        <a title="link" href="{{ $post->link }}">{{ $post->link }}</a><br/><br/>
      @endif  
      {!! nl2br(e($post->content)) !!}
      @endif
  </div>

<div id="comment">
@if($post->status==0 && $post->billboard()->first()->status==0)
  <div class="card card-block" style="background-color: #FFFFFF;">
    @if(!Auth::user() || Auth::user()->username==null)
    {!! Form::open(['action' => ['Discuss\PostController@postComment', 'id' => $post->id], 'method' => 'post', 'role' => 'form']) !!}
    @else
     {!! Form::open(['action' => ['Discuss\PostController@postComment', 'id' => $post->id], 'method' => 'post', 'role' => 'form','@submit.prevent'=>'Addcomment']) !!}
     @endif
         <div class="form-group">
            {!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '', 'size' => '5x3','v-model' => 'newComment.content']) !!}
             @if($post->billboard()->first()->anonymous==0)
                    {!! Form::label('anonymous', '選擇是否匿名') !!}
                    <label class="c-input c-radio">
                      {!! Form::radio('anonymous', '0', true, ['id' => 'public', 'name' => 'anonymous','v-model' => 'newComment.anonymous']) !!}
                      <span class="c-indicator"></span>
                      不匿名
                    </label>
                    <label class="c-input c-radio">
                      {!! Form::radio('anonymous', '1', null, ['id' => 'private', 'name' => 'anonymous','v-model' => 'newComment.anonymous']) !!}
                      <span class="c-indicator"></span>
                      匿名
                    </label> 
           @endif
         </div>
         <div class="form-group">
         @if(!Auth::check() || ($post->billboard()->first()->limit_college==1 && Auth::user()->college()->first()->id != $post->billboard()->first()->college_id && $post->billboard()->first()->admins()->where('user_id',Auth::id())->first()==null))
            {!! Form::submit('發表留言', array('class' => 'btn btn-secondary','disabled' => 'disabled','title' =>!Auth::check() ? '請先登入' : '無法留言')) !!}     
        @elseif(Auth::user()->username==null)
            {!! Form::submit('發表留言', array('class' => 'btn btn-secondary')) !!}
        @else
            <button :disabled="!isValid" @click="Addcomment({{ $post->id }})" class="btn btn-secondary" type="submit" >發表留言</button>
              <!-- {!! Form::submit('發表留言', array('class' => 'btn btn-secondary')) !!}      -->
         @endif
       </div>
     {!! Form::close() !!} 
  </div>
 
  @if(count($post->comments()->get()) != 0) 
    <div class="card card-block" style="background-color: #FFFFFF;">
        <h4 class="card-title">留言</h4>
        <p class="card-text">
        @foreach ($post->comments()->orderby('created_at','desc')->get() as $key => $comments)
        <!-- Split button -->
        @if(Auth::check())
          <div class="btn-group pull-xs-right @if($key!=0)toggle_margin @endif">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle fix-height" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" style="left:-117px;">
              <a class="dropdown-item" class="dropdown-item" data-toggle="modal" data-target="#reportCommentModal-{{ $comments->id }}" data-whatever="@mdo">舉報</a>
              @if($comments->user()->first()->id == Auth::id())
              @if($comments->status!=1)
              <a class="dropdown-item" data-toggle="modal" data-target="#editCommentModal-{{ $comments->id }}" data-whatever="@mdo">編輯</a>
              @endif
              <a class="dropdown-item" data-toggle="modal" data-target="#deleteCommentModal-{{ $comments->id }}" data-whatever="@mdo">刪除</a>
              @endif
              @if($post->billboard()->first()->admins()->where('user_id',Auth::id())->first() != null)
              <a class="dropdown-item" data-toggle="modal" data-target="#blockCommentModal-{{ $comments->id }}" data-whatever="@mdo">封鎖</a>
              @endif
            </div>
          </div>
        @endif
          <div class="media">
          <div class="media-left">
          <img style="height: 40px;" class="media-object img-circle " src="@if( ($comments->user()->first()->facebook()->first() == ''  || $comments->anonymous==1) && $comments->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif(($comments->user()->first()->facebook()->first() == ''  || $comments->anonymous==1) && $comments->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $comments->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
          </div>
          <div class="media-body">
            @if($comments->anonymous==0)
              <h4 class="media-heading" style="display:inline">{{ $comments->user()->first()->username }}</h4>
            @endif
            <p>
            @if($comments->status==1)
              此留言已遭封鎖
            @else
              {!! nl2br(e($comments->content)) !!}
            @endif
            <br />
            <font color="#7e7e7e">{{ $comments->created_at->diffForHumans() }}</font>
            <span class="pull-xs-right">
              <a href="{{ URL::route('get.comment.vote',['id' => $comments->id,'votetype' => 1]) }}">
              @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Comment')->where('votable_id',$comments->id)->where('vote',1)->first()==null)<button class="upIcon"></button>@else<button class="upIcon2"></button>@endif</a> 
              {{ $comments->votes()->where('vote',1)->count()-$comments->votes()->where('vote',0)->count() }} 
              <a href="{{ URL::route('get.comment.vote',['id' => $comments->id,'votetype' => 0]) }}">
              @if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Comment')->where('votable_id',$comments->id)->where('vote',0)->first()==null)<button class="downIcon"></button>@else<button class="downIcon2"></button>@endif</a></span></p>
          </div>
        </div>

         <!--檢舉modal-->
        <div class="modal fade" id="reportCommentModal-{{ $comments->id }}" tabindex="-1" role="dialog" aria-labelledby="reportCommentModal-{{ $comments->id }}" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">檢舉留言</h4>
              </div>
              {!! Form::open(['action' => ['Comment\CommentController@postReport', 'id' => $comments->id], 'method' => 'post', 'role' => 'form']) !!}

                 @include('auctions.items.forms.report', ['submitButtonText' => '檢舉'])

                {!! Form::close() !!}
              
            </div>
          </div>
        </div>
        <!--編輯modal-->
        <div class="modal fade" id="editCommentModal-{{ $comments->id }}" tabindex="-1" role="dialog" aria-labelledby="editCommentModal-{{ $comments->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">編輯留言</h4>
              </div>
              {!! Form::model($comments, ['method' => 'PATCH', 'action' => ['Comment\CommentController@patchUpdateComment',$comments->id]]) !!}

              @include('common.comment', ['submitButtonText' => '儲存'])
              

              {!! Form::close() !!}
              
            </div>
          </div>
        </div>
         <!--刪除modal-->
          <div class="modal fade" id="deleteCommentModal-{{ $comments->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCommentModal-{{ $comments->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteItemModal">刪除留言</h4>
                </div>
            {!! Form::open(['action' => ['Comment\CommentController@destroyComment', 'id' => $comments->id], 'method' => 'delete', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要刪除該留言？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>
            <!--封鎖modal-->
          <div class="modal fade" id="blockCommentModal-{{ $comments->id }}" tabindex="-1" role="dialog" aria-labelledby="blockCommentModal-{{ $comments->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteItemModal">封鎖留言</h4>
                </div>
            {!! Form::open(['action' => ['Comment\CommentController@postBlock','id' => $comments->id], 'method' => 'post', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要封鎖該留言？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>
      @endforeach
      </p>
  </div>
  @endif
 @endif   
</div>
</div>

<div class="col-lg-3">
  <div class="card card-block" style="background-color: #FFFFFF;">
        <a href="{{ URL::route('get.discuss.post',['type' => 'link','domain' => $billboard]) }} " class="linkIcon btn btn-secondary btn-block">新增連結</a>
        <a href="{{ URL::route('get.discuss.post',['type' => 'content','domain' => $billboard]) }} " class="contentIcon btn btn-secondary btn-block">新增內容</a>
  </div>

  @if(false)
  @if($post->status==0 && $post->billboard()->first()->status==0)
    <div class="card card-block" style="background-color: #FFFFFF;">
        <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '1']) }}">@if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',1)->first()==null)<button class="upIcon"></button>@else<button class="upIcon2"></button>@endif</a> {{ $post->votes()->where('vote',1)->count()-$post->votes()->where('vote',0)->count() }} <a href="{{ URL::route('get.post.vote',['id' => $post->id,'votetype' => '0']) }}">@if(!Auth::check() || Auth::user()->votes()->where('votable_type','App\Post')->where('votable_id',$post->id)->where('vote',0)->first()==null)<button class="downIcon"></button>@else<button class="downIcon2"></button>@endif</a>
        <a href="{{ URL::route('get.post.bookmark',['id' => $post->id]) }}">@if(!Auth::check() || Auth::user()->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$post->id)->first()==null)<button class="bookmarkIcon" title="我要收藏"></button>@else<button class="bookmarkIcon2" title="取消收藏"></button>@endif</a>{{ $post->bookmarks()->count() }}
        @if(Auth::check())
        <button data-toggle="modal" data-target="#reportPostModal" data-whatever="@mdo" type="button" class="reportIcon" title="舉報"></button>
        @endif
    </div>
  @endif
  @endif  

   @if($post->user()->first()->id == Auth::id() || $post->billboard()->first()->admins()->where('user_id',Auth::id())->first() != null)
      <div class="card card-block" style="background-color: #FFFFFF;">
          <a href="{{ URL::route('get.discuss.post.edit',['id' => $post->id,'domain' => $post->billboard()->first()->domain]) }}" class="btn btn-secondary editIcon">修改</a>
          <button data-toggle="modal" data-target="#deletePostModal" data-whatever="@mdo" type="button" class="btn btn-secondary deleteIcon">刪除</button>
          @if($post->billboard()->first()->admins()->where('user_id',Auth::id())->first() != null)
          <button data-toggle="modal" data-target="#blockPostModal" data-whatever="@mdo" type="button" class="btn btn-secondary blockIcon">封鎖</button>
          <button data-toggle="modal" data-target="#setPriorityPostModal" data-whatever="@mdo" type="button" class="btn btn-secondary priorityIcon">頂置</button>
          @endif
      </div>
 @endif
</div>

    </div>
    </div>


   <!--刪除modal-->
  <div class="modal fade" id="deletePostModal" tabindex="-1" role="dialog" aria-labelledby="deletePostModal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="deletePostModal">刪除貼文</h4>
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
  </div>

   <!--檢舉modal-->
  <div class="modal fade" id="reportPostModal" tabindex="-1" role="dialog" aria-labelledby="reportPostModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
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
  </div>

  <!--封鎖modal-->
  <div class="modal fade" id="blockPostModal" tabindex="-1" role="dialog" aria-labelledby="blockPostModal" aria-hidden="true">
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
  </div>


<!--頂置貼文設定modal-->
  <div class="modal fade" id="setPriorityPostModal" tabindex="-1" role="dialog" aria-labelledby="setPriorityPostModal" aria-hidden="true">
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
  </div>
@endsection

 @push('scripts')
  <script type="text/javascript" src="{{ asset('js/comment.js') }}"></script>
@endpush