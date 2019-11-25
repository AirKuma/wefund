@extends('layouts.master')

@section('content')


<div class="container" >
   <div class="row" >
	   <div class="col-sm-12">
        	<div class="card card-block" style="background-color: #FFFFFF;">

<table class="table">
  <thead>
    <tr>
      <th>討論版</th>
      <th>管理</th>
    </tr>
  </thead>
  <tbody>
@foreach ($billboards as $index => $billboard)

    <tr>
      <td>{{ $billboard->name }}</td>
      <td>
          <a href="{{ URL::route('get.discuss.billboard.edit',['id' => $billboard->id]) }}" class="btn btn-primary">修改</a>
            <button data-toggle="modal" data-target="#deleteItemModal-{{ $billboard->id }}" data-whatever="@mdo" type="button" class="btn btn-danger">刪除</button>
            <button data-toggle="modal" data-target="#blockPostModal-{{ $billboard->id }}" data-whatever="@mdo" type="button" class="btn btn-danger">@if($billboard->status==1)開啟@else關閉@endif</button>
            <a href="{{ URL::route('get.billboard.category',['id' => $billboard->id]) }}" class="btn btn-primary">類別管理</a> 
            <a href="{{ URL::route('get.billboard.subscriber',['id' => $billboard->id]) }}" class="btn btn-primary">訂閱者</a>
            <a href="{{ URL::route('get.billboard.applysubscriber',['id' => $billboard->id]) }}" class="btn btn-primary">申請訂閱者</a>
      </td>
    </tr>

<!--刪除modal-->
  <div class="modal fade" id="deleteItemModal-{{ $billboard->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteItemModal-{{ $billboard->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="deleteItemModal">刪除討論版</h4>
        </div>
    {!! Form::open(['action' => ['Discuss\BillboardController@destroyBillboard', 'id' => $billboard->id], 'method' => 'delete', 'role' => 'form']) !!}
    <div class="modal-body">
      <h6>確定要刪除該討論版？</h6>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            {!! Form::hidden('returnback', 0) !!}
            {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>

  <!--封鎖modal-->
  <div class="modal fade" id="blockPostModal-{{ $billboard->id }}" tabindex="-1" role="dialog" aria-labelledby="blockPostModal-{{ $billboard->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="deleteItemModal">@if($billboard->status==1)開啟@else關閉@endif討論版</h4>
        </div>
    {!! Form::open(['action' => ['Discuss\BillboardController@postBlock','id' => $billboard->id], 'method' => 'post', 'role' => 'form']) !!}
    <div class="modal-body">
      <h6>確定要@if($billboard->status==1)開啟@else關閉@endif該討論版？</h6>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
           {!! Form::hidden('returnback', 0) !!}
            {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>

@endforeach

  </tbody>
</table>
			</div>

    </div>

  </div>

</div>


@endsection

