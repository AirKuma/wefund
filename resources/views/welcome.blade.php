
@extends('layouts.master')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @include('errors.list')
            </div>

            <div class="col-lg-7">

<div id="carousel-example-generic" class="carousel slide hidden-xs" data-ride="carousel">
  <div class="carousel-inner">
@foreach($items as $key => $item)
@if($key%2==0)<div class="carousel-item @if($key==0)active @endif">@endif
  <div class="col-lg-6 itemcolum">
    <div class="card card-colsmall">
        <div>           
                @if($item->albums()->first()->images()->first()!=null)
                <div class="indeximg">
                  <img class="img-fluid " style="width: 100%;" src="/images/auctions/thumbs/{{ $item->albums()->first()->images()->first()->file_name }}" alt="Photo of sunset">
                </div>
                @endif  
                <a href="{{ URL::route('get.auction.item.show', ['auction' => $item->type==0 ? 'bid':'seek','id' => $item->id]) }}">
                    <div class="thumb-cover"></div>
                </a>            
            <div class="details"> 
                <div class="pull-right">
                    @if($item->free==1)<span class="freeIcon"></span> @endif @if($item->new==1)<span class="newIcon"></span>@endif
                </div>
                <div class="clearfix"></div>
            </div>            
        </div>
        <div class="card-info">
            <div class="moving">
                    <h3>
                      <a href="{{ URL::route('get.auction.index',['college' =>  $item->user()->first()->college()->first()->acronym,'auction' => $item->type==0 ? 'bid':'seek','type' => $item->category()->first()->en_name]) }}" class="label label-success">{{ $item->category()->first()->name }}</a>
                      <a href="{{ URL::route('get.auction.item.show', ['auction' =>  $item->type==0 ? 'bid':'seek','id' => $item->id]) }}">{{ str_limit($item['name'], $limit = 14, $end = '...') }}
                    </h3>
                    @if($item->type==1)<p>{!! str_limit(nl2br(e($item->description)), $limit = 80, $end = '...') !!}</p>@endif</a>
                    <div><font size="4" color="red">@if($item->free==1)免費@elseif(count($item->users()->get()) == 0)NT${{ $item->price }} @elseif($item->type==0) NT${{ $item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price }}@else NT${{ $item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price }} @endif</font>
                    @if($item->free==0)<span class="auctionNumIcon"></span> <span class="label label-pill label-warning">{{ $item->users()->count() }}</span>@endif</div>
                    <b><div class="clockIcon" data-countdown="{{ $item->end_time  }}"></div></b>
                    
                             
                <!-- <b class="actions">
                    <a href="#/product/awesome-landing-page">Details</a>
                    <b class="separator">|</b>
                    <a class="blue-text" href="#/live/awesome-landing-page" target="_blank">Live Preview</a>
                </b> -->
            </div>
        </div>
    </div>
  </div>


@if($key%2!=0 || $key==count($items)-1)</div>@endif
  @endforeach
<!--             <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="icon-prev" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="icon-next" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a> -->

  </div>
</div>

        <div style="margin-top:10px;">
            <center><br/><h3 style="line-height: 39px;">趕快加入輔大Loyaus, <br/>把自己二手品拍賣。</h3></center>
        </div>
    

         </div>

           <div class="col-lg-5 card card-block search" style="padding:36px;">
            <h4> 註冊為會員 </h4>

                <!-- @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                @endif -->

                {!! Form::open(['route' => 'users.register', 'method' => 'post', 'role' => 'form']) !!}

                <div class="form-group">
                    {!! Form::text('email', null, ['class' => 'form-control','placeholder' => '學校電子郵件']) !!}
                </div>

                <div class="form-group">
                    {!! Form::password('password', ['class' => 'form-control','placeholder' => '密碼']) !!}
                </div>

                <div class="form-group">
                    {!! Form::password('password_confirmation', ['class' => 'form-control','placeholder' => '確認密碼']) !!}
                </div>

                <div class="form-group">
                    {!! Form::text('lastname', null, ['class' => 'form-control','placeholder' => '姓氏']) !!}
                </div>

                <div class="form-group">
                    {!! Form::text('firstname',null, ['class' => 'form-control','placeholder' => '名字']) !!}
                </div>

                <div class="radio">
                    <label class="c-input c-radio">
                        {!! Form::radio('gender', '1') !!} 
                        <span class="c-indicator"></span>男 
                    </label>
                    <label class="c-input c-radio">
                        {!! Form::radio('gender', '0') !!}
                        <span class="c-indicator"></span>女 
                    </label>
                </div>

                <div class="form-group">                  
                    {!! Form::select('major_id',$majors, null, ['class' => 'form-control', 'placeholder' => '請選擇學系']) !!}
                </div>


                <div class="form-group">
                    {!! Form::date('birthday', null, ['class' => 'form-control','placeholder' => '生日']) !!}
                </div>

                {!! Form::submit('註冊', ['class' => 'btn btn-secondary']) !!}
                <!-- {!! Form::reset('Clear', ['class' => 'btn btn-danger']) !!} -->
                {!! Form::close() !!}
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


@endsection