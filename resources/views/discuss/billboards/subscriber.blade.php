@extends('layouts.master')

@section('content')


<div class="container" >
   <div class="row" >
	   <div class="col-md-12" style="background-color: #FFFFFF;">
        	<div class="card">


@foreach ($subscribers as $key => $subscriber)
<div class="col-md-6 card-block">

  @if($billboard->admins()->where('user_id',Auth::id())->first() != null)
  <div class="btn-group pull-xs-right">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle fix-height" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" style="left:-117px;">
              <a class="dropdown-item" data-toggle="modal" data-target="#deleteSubscriberModal-{{ $subscriber->id }}" data-whatever="@mdo">移除訂閱者</a>
              <a class="dropdown-item" data-toggle="modal" data-target="#setAdminModal-{{ $subscriber->id }}" data-whatever="@mdo">@if($billboard->admins()->where('user_id',$subscriber->user_id)->first()!=null)移除@else指定@endif管理員</a>
            </div>
    </div>
  @endif

  <div class="media">
        <div class="media-left">
        <img style="height: 40px;" class="media-object img-circle " src="@if( ($subscriber->user()->first()->facebook()->first() == ''  || $billboard->anonymous==1) && $subscriber->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif(($subscriber->user()->first()->facebook()->first() == ''  || $billboard->anonymous==1) && $subscriber->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $subscriber->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
        </div>
        <div class="media-body">
          @if($billboard->admins()->where('user_id',$subscriber->user_id)->first() != null)
            [版主]
          @endif
          @if($billboard->anonymous==0)
            @if($subscriber->user()->first()->username=="")尚未設定使用者名稱 @else {{ $subscriber->user()->first()->username }}@endif<br>
          @endif
            @if($subscriber->user()->first()->gender==1)男 @else 女 @endif {{ $subscriber->user()->first()->college()->first()->name }} {{ $subscriber->user()->first()->major()->first()->name }}
        </div>
  </div>
</div>

<!--移除訂閱者modal-->
          <div class="modal fade" id="deleteSubscriberModal-{{ $subscriber->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteSubscriberModal-{{ $subscriber->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteSubscriberModal">移除訂閱者</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\BillboardController@destroySubscriber','id' => $subscriber->id], 'method' => 'delete', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要移除該訂閱者？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>

           <!--管理員modal-->
          <div class="modal fade" id="setAdminModal-{{ $subscriber->id }}" tabindex="-1" role="dialog" aria-labelledby="setAdminModal-{{ $subscriber->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="setAdminModal">@if($billboard->admins()->where('user_id',$subscriber->user_id)->first()!=null)移除@else指定@endif管理員</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\BillboardController@postSetadmin','id' => $subscriber->id], 'method' => 'post', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要@if($billboard->admins()->where('user_id',$subscriber->user_id)->first()!=null)移除@else指定@endif該管理員？</h6>
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

			</div>

    </div>

  </div>

</div>


@endsection

