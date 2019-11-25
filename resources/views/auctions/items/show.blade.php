@extends('layouts.master')

@section('content')


<div class="container" >





      <div class="row" >


        <!-- /.blog-sidebar -->

        	<div class="col-lg-12">
        	<!-- @if($owner->id == Auth::id())
        	

				<div class="alert alert-warning alert-dismissible fade in" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				  <strong>Holy guacamole!</strong> You should check in on some of those fields below.
				</div>

				

			
        	@endif -->
        	@include('errors.list')
        	</div>
        	
	        <div class="col-lg-9">
			<div class="card" style="background-color: #FFFFFF;">
				@if($item->disabled==1)
         <h4 class="card-block">此項目已遭封鎖</h4>
        @else
          <h4 class="card-title card-block" style="margin-bottom:0px;"><span class="label label-danger">@if($auction=='bid')拍賣@else競投@endif</span> @if($item->free==1)<span class="freeIcon"></span> @endif @if($item->new==1)<span class="newIcon"></span>@endif {{ $item->name }}</h4>

        @if($images->count()==1)
        <img src="/images/auctions/{{ $images->first()->file_name }}" class="img-rounded" style="max-width:100%;" alt="">
        @elseif($images->count()>1)
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              @foreach ($images as $key => $image)
              <li data-target="#carousel-example-generic" data-slide-to="{{ $key }}" @if($key==0)class="active" @endif></li>
              @endforeach
            </ol>
            <div class="carousel-inner" role="listbox" style="">
              @foreach ($images as $key => $image)
              <div class="itemimg carousel-item @if($key==0)active @endif">
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
  		
  				<p class="card-text card-block">
            分類：{{ $item->category()->first()->name }}<button data-toggle="modal" data-target="#reportItemModal" data-whatever="@mdo" type="button" class="pull-xs-right reportIcon" title="舉報"></button><br />
  				{!! nl2br(e($item->description)) !!}
  				</p>
        @endif
			</div>

      @if($item->disabled==0)
			<div id="comment" class="card card-block" style="background-color: #FFFFFF;">

    {!! Form::open(['action' => ['Auction\ItemController@postComment', 'id' => $item->id], 'method' => 'post', 'role' => 'form','@submit.prevent' =>'AddItemcomment']) !!}

                <div class="form-group">
               
              	{!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '', 'size' => '5x3','v-model' => 'newComment.content']) !!}

                </div>
                <!-- {!! Form::submit('發表評論', array('class' => 'btn btn-secondary')) !!}			 -->
                <button :disabled="!isValid" @click="AddItemcomment({{ $item->id }})" class="btn btn-secondary" type="submit">留言</button>
				{!! Form::close() !!} 
			</div>
      @endif

			
			 
			
			@if(count($item->comments()->get()) != 0)
			
			<div class="card card-block" style="background-color: #FFFFFF;">
			  <h4 class="card-title">留言</h4>
			  <p class="card-text">
			  @foreach ($item->comments()->orderby('created_at','desc')->get() as $key => $comments)
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
            </div>
          </div>
        @endif
					<div class="media">
					<div class="media-left">
					<img style="height: 40px;" class="media-object img-circle " src="@if($comments->user()->first()->facebook()->first()==null && $comments->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif($comments->user()->first()->facebook()->first()==null && $comments->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $comments->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
					</div>
					<div class="media-body">
						<h4 class="media-heading">{{ $comments->user()->first()->lastname. '' .$comments->user()->first()->firstname }}</h4>
						<p>
              @if($comments->status==1)
              此留言已遭封鎖
            @else
              {!! nl2br(e($comments->content)) !!}
            @endif
            <br />
						<font color="#7e7e7e">{{ $comments->created_at->diffForHumans() }}</font></p>
						   
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
                  <h4 class="modal-title" id="deleteCommentModal">刪除留言</h4>
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
                  <h4 class="modal-title" id="blockCommentModal">封鎖留言</h4>
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

        	</div>


        <div class="col-lg-3 col-sm-offset-0">
@if($item->disabled==0)
    <!--拍賣結束前後-->
    @if($item->end_time > $now)
        @if($owner->id == Auth::id())
			<div class="card card-block" style="background-color: #FFFFFF;">
					<a href="{{ URL::route('get.auction.item.edit',['auction' => $auction,'id' => $item->id]) }} " class="btn btn-secondary editIcon">修改</a>
					<button data-toggle="modal" data-target="#deleteItemModal" data-whatever="@mdo" type="button" class="btn btn-secondary deleteIcon">刪除</button>

			</div>
		    @endif
			<div id="bid" class="card card-block" style="background-color: #FFFFFF;">
        <h1 class="card-title" >@if($item->free==1)免費@elseif(count($item->users()->get()) == 0)${{ $item->price }} @else ${{ $item_user->first()->pivot->price }} @endif</h1>
			  <p class="card-text">
          @if($item->free==0)
            <div class="form-group">
              <label for="message-text" class="control-label sr-only">名稱:</label>
		{!! Form::open(['action' => ['Auction\ItemController@postBidItem', 'id' => $item->id], 'method' => 'post', 'class' => '']) !!}
              @if($owner->id != Auth::id())

        <!-- <span v-for="error in newbid.Errors" class="text-danger"><li>@{{error}}</li></span> -->

			  <div class="form-group">
              {!! Form::text('price', null, ['class'=> 'form-control', 'placeholder' => '價格','v-model' => 'newbid.price']) !!}
			 </div>
            <div class="form-group">
            <button type="submit" class="btn btn-secondary bidIcon">我要出價</button>
            </div>
            @endif
		</div>
		{!! Form::close() !!}
            
        @else
          @if(Auth::user()->facebook()->first()!=null)
          {!! Form::open(['action' => ['Auction\ItemController@postFreeItem', 'id' => $item->id], 'method' => 'post', 'class' => '','@submit.prevent'=>'Addfree']) !!}
          @else
          {!! Form::open(['action' => ['Auction\ItemController@postFreeItem', 'id' => $item->id], 'method' => 'post']) !!}
          @endif
              @if($owner->id != Auth::id())
  
            <div class="form-group text-center">
            <button @if(Auth::user()->facebook()->first()!=null)@click="Addfree({{ $item->id }})" @endif type="submit" class="btn btn-secondary btn-block wantIcon">我想要</button>
            </div>
            @endif
    {!! Form::close() !!}
        @endif
<b><div data-countdown="{{ $item->end_time }}"></div></b>
          
            
			  </p>
			
			</div>
      @else
      <div class="card card-block" style="background-color: #FFFFFF;">
        @if($item_user->first()!=null)
        @if($owner->id == Auth::id())
          <h5>@if($item->type==0)拍賣@else競投@endif已經結束，此項目由<b>{{ $item_user->first()->lastname. '' .$item_user->first()->firstname }}</b>@if($item->free==1)獲得@else得標@endif！</h5>
          買家聯絡資訊如下，請找時間與買家聯繫。</br>
          <center>
          @if($item_user->first()->facebook()->first()!=null)
          Facebook：<br>
            <a href="https://www.facebook.com/{{ $item_user->first()->facebook()->first()->facebook_id }}" target="_blank">
              {{ $item_user->first()->facebook()->first()->name }}</br> 
              <img src="{{ $item_user->first()->facebook()->first()->avatar }}" alt="facebook_avatar" class="img-reponsive img-rounded"><br>
            </a><br>
            @endif
            <button data-toggle="modal" data-target="#contectModal" data-whatever="@mdo" type="button" class="btn btn-warning btn-sm">更多聯絡資訊</button>
          </center>
        @elseif($item_user->first()->id == Auth::id()) 
          <h5>@if($item->type==0)拍賣@else競投@endif已經結束，恭喜@if($item->free==1)獲得@else標得@endif<b>{{ $item->name }}</b>！</h5>
          賣家聯絡資訊如下，請找時間與賣家聯繫。</br>

          <center>
          @if($item->user()->first()->facebook()->first()!=null)
          Facebook：<br>
            <a href="https://www.facebook.com/{{ $item->user()->first()->facebook()->first()->facebook_id }}" target="_blank">
              {{ $item->user()->first()->facebook()->first()->name }}</br> 
              <img src="{{ $item->user()->first()->facebook()->first()->avatar }}" alt="facebook_avatar" class="img-reponsive img-rounded"><br>
            </a><br>
            @endif
            <button data-toggle="modal" data-target="#contectModal" data-whatever="@mdo" type="button" class="btn btn-warning btn-sm">更多聯絡資訊</button>
          </center>
          @else
            <h5>@if($item->type==0)拍賣@else競投@endif已經結束，此項目由<b>{{ $item_user->first()->lastname. '' .$item_user->first()->firstname }}</b>@if($item->free==1)獲得@else得標@endif！</h5>
        @endif  
       @else
        <h5>@if($item->type==0)拍賣@else競投@endif已經結束。</h5> 
      @endif
      </div>
      @endif
  @endif    
<!--拍賣結束前後-->

@if($item->free==0)
			<div class="card card-block" style="background-color: #FFFFFF;">
			  <h5 class="card-title" >出價記錄 <span class="label label-pill label-warning">{{ count($item_user) }}</h5>
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


        </div>



      </div>
<script type="text/javascript">

  $('[data-countdown]').each(function() {
    var $this = $(this),
        finalDate = $(this).data('countdown');

    $this.countdown(finalDate, function(event) {
      $this.html(event.strftime('%D 天 %H 時 %M 分 %S 秒結束'));
    });
  });
</script>

    </div>


<!-- !-->

<script type="text/javascript">
$('#reportItemModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
})
$('#deleteItemModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
})
$('#contectModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
})
</script>

    

 <!--檢舉modal-->
  <div class="modal fade" id="reportItemModal" tabindex="-1" role="dialog" aria-labelledby="reportItemModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel">檢舉項目</h4>
        </div>
    {!! Form::open(['action' => ['Auction\ItemController@postReport', 'id' => $item->id], 'method' => 'post', 'role' => 'form']) !!}

           @include('auctions.items.forms.report', ['submitButtonText' => '檢舉'])

          {!! Form::close() !!}
        
      </div>
    </div>
  </div>

   <!--刪除modal-->
  <div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-labelledby="deleteItemModal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="deleteItemModal">刪除項目</h4>
        </div>
    {!! Form::open(['action' => ['Auction\ItemController@destroyItem', 'id' => $item->id], 'method' => 'delete', 'role' => 'form']) !!}
		<div class="modal-body">
			<h6>確定要刪除該項目？</h6>
           <div class="modal-footer">
           	<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          	{!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>

   <!--聯絡訊息modal-->
  <div class="modal fade" id="contectModal" tabindex="-1" role="dialog" aria-labelledby="contectModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="contectModal">聯絡訊息</h4>
        </div>
    <div class="modal-body">
      @if($item_user->first()!=null)
      @if($owner->id == Auth::id())
          @if($item_user->first()->line_username != null)
          <b>Line:</b><br>
          <img src="http://chart.apis.google.com/chart?cht=qr&amp;chl=http://line.me/ti/p/~{{ $item_user->first()->line_username }}&amp;chs=200x200" alt="" class="center-block"><br>
          @endif
          @if($item_user->first()->telegram_username != null)
          <b>Telegram：</b>{{ $item_user->first()->telegram_username }}</br>
          @endif
          <b>學校信箱：</b>{{ $item_user->first()->email }}</br>
          @if($item_user->first()->other_email != null)
          <b>其他信箱：</b>{{ $item_user->first()->other_email }}</br>
          @endif
      @elseif($item_user->first()->id == Auth::id())
          @if($item->user()->first()->line_username != null)
          <b>Line:</b><br>
          <img src="http://chart.apis.google.com/chart?cht=qr&amp;chl=http://line.me/ti/p/~{{ $item->user()->first()->line_username }}&amp;chs=200x200" alt="" class="center-block"><br>
          @endif
          @if($item->user()->first()->telegram_username != null)
          <b>Telegram：</b>{{ $item->user()->first()->telegram_username }}</br>
          @endif
          <b>學校信箱：</b>{{ $item->user()->first()->email }}</br>
          @if($item->user()->first()->other_email != null)
          <b>其他信箱：</b>{{ $item->user()->first()->other_email }}</br>
          @endif
      @endif
      @endif
       </div> 
      </div>
    </div>
  </div>








@endsection
 @push('scripts')
  <script type="text/javascript" src="{{ asset('js/bid.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/comment.js') }}"></script>
@endpush