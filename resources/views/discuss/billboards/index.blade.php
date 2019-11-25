@extends('layouts.master')

@section('content')
  <div class="container">

  <div class="row">

<div class="col-lg-9">
  <div>
    @if(count($billboards) == 0)
    <h4><center>暫無討論版</center></h4>
    @else

<ul class="list-group">
        @foreach($billboards as $billboard)

           <li class="list-group-item">
        <h5 class="list-group-item-heading">
              <a href="{{ URL::route('get.post.index',['billboard' => $billboard->domain,'category' => 'all']) }}"><h5 class="list-group-item-heading" style="display: inline;">{{ $billboard->name }}</h5></a>
              <span class="pull-right-780">  
            @if($billboard->type==1)<button class="secret" title="私密討論版"></button> @endif @if($billboard->anonymous==1)<button class="anonymous" title="匿名討論版"></button> @elseif($billboard->anonymous==0)<button class="optanonymous" title="選擇性匿名討論版"></button> @endif @if($billboard->target==1)<button class="male" title="限定男性"></button> @elseif($billboard->target==2)<button class="female" title="限定女性"></button> @endif @if($billboard->adult==1) <button class="adult" title="18禁"></button> @endif @if($billboard->limit_college==1)<button class="college" title="只限制{{ $billboard->college()->first()->name }}學生發言"></button> @endif
          </span> 
      </h5>
<button @if(!Auth::check() || ($billboard->target==1 && Auth::user()->gender!=1) || ($billboard->target==2 && Auth::user()->gender!=0))disabled="disabled" title="無法訂閱該版" @endif class="btn btn-secondary" style="padding: 0px 0px 0px 6px;" data-toggle="modal" data-target="#subscriptionPostModal-{{ $billboard->id }}" data-whatever="@mdo">@if(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()!=null && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()->allow==1)<span class="subtractionIcon">待批准</span>@elseif(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()!=null)<span class="subtractionIcon">取消訂閱</span>@else<span class="plusIcon">訂閱</span>@endif</button>
<a href="{{ URL::route('get.billboard.subscriber',['id' => $billboard->id]) }}" class="personIcon btn btn-secondary">{{ $billboard->subscriptions()->where('allow',0)->count() }}</a>
            </li>
        
          <!-- <div class="media">
          <button @if(!Auth::check() || ($billboard->target==1 && Auth::user()->gender!=1) || ($billboard->target==2 && Auth::user()->gender!=0))disabled="disabled" title="無法訂閱該版" @endif class="col-lg-1 label label-default label-pill" style="margin-right:10px" data-toggle="modal" data-target="#subscriptionPostModal-{{ $billboard->id }}" data-whatever="@mdo">@if(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()!=null && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()->allow==1)待批准@elseif(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()!=null)取消訂閱@else訂閱@endif</button>
          <div class="media-body">
            <a href="{{ URL::route('get.post.index',['billboard' => $billboard->domain,'category' => 'all']) }}"><h4 class="media-heading" style="display: inline;">{{ $billboard->name }}</h4></a>
          <span class="pull-xs-right small">  
            @if($billboard->type==1)<button class="secret" title="私密討論版"></button> @endif @if($billboard->anonymous==1)<button class="anonymous" title="匿名討論版"></button> @elseif($billboard->anonymous==0)<button class="optanonymous" title="選擇性匿名討論版"></button> @endif @if($billboard->target==1)<button class="male" title="限定男性"></button> @elseif($billboard->target==2)<button class="female" title="限定女性"></button> @endif @if($billboard->adult==1) <button class="adult" title="18禁"></button> @endif @if($billboard->limit_college==1)<button class="college" title="只限制{{ $billboard->college()->first()->name }}學生發言"></button> @endif
          </span>  
     </p>
             <a href="{{ URL::route('get.post.index',['billboard' => $billboard->domain,'category' => 'all']) }}" style="padding-right:10px;">{{ $billboard->posts()->where('status',0)->count() }}則貼文</a>  
             {{ $billboard->subscriptions()->where('allow',0)->count() }}人訂閱
          </div>
        </div> -->

    @if(Auth::check())
         <!--訂閱modal-->
          <div class="modal fade" id="subscriptionPostModal-{{ $billboard->id }}" tabindex="-1" role="dialog" aria-labelledby="subscriptionPostModal-{{ $billboard->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="subscriptionPostModal">訂閱討論版</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\BillboardController@postSubscription','id' => $billboard->id], 'method' => 'post', 'role' => 'form']) !!}
            <div class="modal-body">
              @if(Auth::user()->subscriptions()->where('subscriptable_id',$billboard->id)->first()==null)
              <h6>確定要訂閱該討論版？</h6>
              @if($billboard->type==1)
              <h6 class="help-block text-danger">※注意！討論版是私密，需經過版主批准才可訂閱</h6>
              @endif
              @else
              <h6>確定要取消訂閱該討論版？</h6>
              @endif
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                    <!-- <button @click="Addsubscription({{ $billboard->id }})" type="submit" class="btn btn-danger">確定</button> -->
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>
        @endif

      @endforeach
    </ul>
   @endif   
  </div>
  <center>{!! $billboards->render() !!}</center>
</div>

<div class="col-lg-3">
  <div class="card card-block" style="background-color: #FFFFFF;">
      這裡共有{{ count($allbillboards) }}個討論版，歡迎大家來訂閱！
  </div>

<!--   <div class="card card-block" style="background-color: #FFFFFF;">
      <a href="{{ URL::route('get.discuss.billboard') }} " class="btn btn-primary btn-block">新增討論版</a>
  </div>
 -->
@if(Auth::check())
  <div style="background-color: #FFFFFF;">
      <div class="list-group">
       <li class="list-group-item active">
          您所訂閱的看板：
      </li> 
      @foreach($subscriptions as $subscription)
        <li class="list-group-item">
          <button @if(!Auth::check() || ($subscription->target==1 && Auth::user()->gender!=1) || ($subscription->target==2 && Auth::user()->gender!=0))disabled="disabled" title="無法訂閱該版" @endif class="btn btn-secondary" style="padding: 0px 0px 0px 6px;" data-toggle="modal" data-target="#subscriptionPostModal-{{ $subscription->id }}" data-whatever="@mdo">@if(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$subscription->id)->first()!=null && Auth::user()->subscriptions()->where('subscriptable_id',$subscription->id)->first()->allow==1)<span class="subtractionIcon">待批准</span>@elseif(Auth::check() && Auth::user()->subscriptions()->where('subscriptable_id',$subscription->id)->first()!=null)<span class="subtractionIcon">取消訂閱</span>@else<span class="plusIcon">訂閱</span>@endif</button>
          <a href="{{ URL::route('get.post.index',['billboard' =>  $subscription->domain,'category' => 'all']) }}">{{ $subscription->name }}</a>
        </li>


        @if(Auth::check())
         <!--訂閱modal-->
          <div class="modal fade" id="subscriptionPostModal-{{ $subscription->id }}" tabindex="-1" role="dialog" aria-labelledby="subscriptionPostModal-{{ $subscription->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="subscriptionPostModal">訂閱討論版</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\BillboardController@postSubscription','id' => $subscription->id], 'method' => 'post', 'role' => 'form']) !!}
            <div class="modal-body">
              @if(Auth::user()->subscriptions()->where('subscriptable_id',$subscription->id)->first()==null)
              <h6>確定要訂閱該討論版？</h6>
              @if($subscription->type==1)
              <h6 class="help-block text-danger">※注意！討論版是私密，需經過版主批准才可訂閱</h6>
              @endif
              @else
              <h6>確定要取消訂閱該討論版？</h6>
              @endif
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                    <!-- <button @click="Addsubscription({{ $subscription->id }})" type="submit" class="btn btn-danger">確定</button> -->
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>
        @endif

      @endforeach
    </div>
  </div>
@endif

</div>

    </div>

  </div>
@endsection

