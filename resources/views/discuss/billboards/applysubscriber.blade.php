@extends('layouts.master')

@section('content')


<div class="container" >
   <div class="row" >
	   <div class="col-md-12" style="background-color: #FFFFFF;">
        	<div class="card">

@if($subscribers->count()==0)
<div class="col-md-12 card-block">
<h4>暫無申請訂閱者</h4>
</div>
@else
@foreach ($subscribers as $key => $subscriber)
<div class="col-md-12 card-block">
<div class="col-md-6 card-block">

  <div class="media">
        <div class="media-left">
        <img style="height: 40px;" class="media-object img-circle " src="@if( ($subscriber->user()->first()->facebook()->first() == ''  || $billboard->anonymous==1) && $subscriber->user()->first()->gender==1){{ asset('images/default/male.png') }}@elseif(($subscriber->user()->first()->facebook()->first() == ''  || $billboard->anonymous==1) && $subscriber->user()->first()->gender==0){{ asset('images/default/female.png') }}@else{{ $subscriber->user()->first()->facebook->avatar }}@endif" alt="Photo of Pukeko in New Zealand">
        </div>
        <div class="media-body">
          @if($billboard->anonymous==0)
            {{ $subscriber->user()->first()->username }}<br>
          @endif
            @if($subscriber->user()->first()->gender==1)男 @else 女 @endif {{ $subscriber->user()->first()->college()->first()->name }} {{ $subscriber->user()->first()->major()->first()->name }}
        </div>
  </div>
</div>

<div class="col-md-6 card-block">
  <a data-toggle="modal" data-target="#agreeModal-{{ $subscriber->id }}" data-whatever="@mdo" class="btn btn-primary">答應</a> 
  <a data-toggle="modal" data-target="#rejectModal-{{ $subscriber->id }}" data-whatever="@mdo" class="btn btn-primary">拒絕</a> 
</div>

</div>

<!--答應modal-->
  <div class="modal fade" id="agreeModal-{{ $subscriber->id }}" tabindex="-1" role="dialog" aria-labelledby="agreeModal-{{ $subscriber->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="agreeModal">答應申請者</h4>
        </div>
    {!! Form::open(['action' => ['Discuss\BillboardController@postRespond','id' => $subscriber->id], 'method' => 'post', 'role' => 'form']) !!}
    <div class="modal-body">
      <h6>確定要答應？</h6>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
           {!! Form::hidden('allow', 0) !!}
            {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>

  <!--拒絕modal-->
  <div class="modal fade" id="rejectModal-{{ $subscriber->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal-{{ $subscriber->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="rejectModal">拒絕申請者</h4>
        </div>
    {!! Form::open(['action' => ['Discuss\BillboardController@postRespond','id' => $subscriber->id], 'method' => 'post', 'role' => 'form']) !!}
    <div class="modal-body">
      <h6>確定要拒絕？</h6>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            {!! Form::hidden('allow', 1) !!}
            {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>
@endforeach
@endif

			</div>

    </div>

  </div>

</div>


@endsection

