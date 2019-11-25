@extends('layouts.master')

@section('content')


<div class="container" >
      <div class="row" >
	        <div class="col-sm-9">
          <h5>你的通知</h5>
          <hr>
            <ul class="list-group">
              @foreach($notifications as $notification)
               <a href="{{ $notification->link }}" class="list-group-item  list-group-item-action" @if($notification->is_read == 0)style="background-color:#f5f5f5;" @endif>
                  
                  <h7 class="list-group-item-heading">
                  @if($notification->notificatable_type != "App\Post" ) <b> @if($notification->sender()->first()->id != 1){{$notification->sender()->first()->lastname }}{{$notification->sender()->first()->firstname }} @endif</b> @endif
                  {{ $notification->content }}

                  </h7>
                  <p class="list-group-item-text">{{ $notification->created_at->diffForHumans() }}  </p>
                </a>
              @endforeach
            </ul>
        	</div>
          <div class="col-sm-3 col-sm-offset-0">
          
          </div>
      </div>
</div>

@endsection
