@extends('layouts.master')

@section('content')

<style type="text/css">
    .table-fixed thead {
  width: 97%;
}
.table-fixed tbody {
  height: 230px;
  overflow-y: auto;
  width: 100%;
}
.table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
  display: block;
}
.table-fixed tbody td, .table-fixed thead > tr> th {
  float: left;
  border-bottom-width: 0;
}
</style>

<div class="container">
  <div class="row">

        <div class="col-sm-3">
        @include('messages.contact')
        </div>
        <div class="col-sm-9">
        @include('errors.list')

                <div class="card card-block" style="background-color: #FFFFFF;">
                <div role="form">
            {!! Form::open(['action' => ['Message\MessageController@postSendMessage', 'id' => $receiver_id], 'method' => 'post', 'class' => 'pull-right']) !!}

            <div class="form-group">
              {!! Form::textarea('body', null, [ 'size' => '30x3', 'class'=> 'form-control', 'placeholder' => '輸入回應內容']) !!}
            </div>
            {!! Form::submit('傳送', ['class' => 'btn btn-primary']) !!}

          {!! Form::close() !!}
                </div>
                </div>
        
    <div class="card card-block" style="background-color: #FFFFFF;">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>
            Messages
          </h4>
        </div>
        <table class="table table-fixed">
        <tbody>
        @foreach($messages as $message)  
            <tr>
              <td class="col-xs-2">{{ $message->user()->first()->firstname }} {{ $message->user()->first()->lastname }}</td><td class="col-xs-10">{{ $message->body }}</td>
            </tr>
        @endforeach
            <div autofocus>
                
            </div>
          </tbody>
        </table>
      </div>
</div>

                @foreach($messages as $message)  
                <div class="card card-block" style="background-color: #FFFFFF;">
                <h4 class="card-title">{{ $message->user()->first()->firstname }} {{ $message->user()->first()->lastname }}</h4>
                        <p class="card-text">{{ $message->body }}</p>
                </div>
                @endforeach 
                <div class="pagination">{!! $messages->render() !!}</div>

                <div class="card card-block" style="background-color: #FFFFFF;">
                <div role="form">
    		{!! Form::open(['action' => ['Message\MessageController@postSendMessage', 'id' => $receiver_id], 'method' => 'post', 'class' => 'pull-right']) !!}

            <div class="form-group">
              {!! Form::textarea('body', null, [ 'size' => '30x3', 'class'=> 'form-control', 'placeholder' => '輸入回應內容']) !!}
            </div>
            {!! Form::submit('傳送', ['class' => 'btn btn-primary']) !!}

          {!! Form::close() !!}
                </div>
                </div>
        </div>
    </div>

</div>










@endsection