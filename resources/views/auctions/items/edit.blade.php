@extends('layouts.master')

@section('content')




    <div class="container">

      <!-- Example row of columns -->
        <div class="row">
           <div class="col-sm-12" >




            <div class="card card-block" style="background-color: #FFFFFF;">
                @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                
                @endif
                <div role="form">
          {!! Form::model($item, ['method' => 'PATCH', 'action' => ['Auction\ItemController@patchUpdateItem',$item->id]]) !!}


           @include('auctions.items.forms.form', ['submitButtonText' => '修改'])


          {!! Form::close() !!}
                </div>
            </div>

@if($item->albums()->first()->images()->count()!=0)
       <div class="card card-block" style="background-color: #FFFFFF;">
        @foreach ($item->albums()->first()->images()->get() as $image)
        
        <img src="/images/auctions/thumbs/{{ $image->file_name }}" class="img-responsive img-rounded" alt=""> 
    {!! Form::open(['action' => ['Auction\ItemController@destroyImage', 'id' => $image->id], 'method' => 'delete', 'class' => '']) !!}
    {!! Form::hidden('auction', $auction) !!}
    {!! Form::submit('刪除', array('class' => 'btn btn-secondary deleteIcon editimg')) !!}
    {!! Form::close() !!}
  
        @endforeach
        </div>
 @endif      

<div class="card card-block" style="background-color: #FFFFFF;">
          {!! Form::open(['route' => ['post.auction.item.image.upload', $item->id], 'method' => 'post','files' => true] ) !!}
            <div class="form-group">
              圖片 <br />
              <label class="file">
              {!! Form::file('image[]', ['multiple' => true], ['class'=> 'file-custom', 'id' => 'file']) !!}
              <span class="file-custom"></span>
              </label>
          {!! Form::submit('上傳圖片', ['class' => 'btn btn-secondary','style'=>'margin-top:11px;']) !!}

            </div>
          {!! Form::close() !!}
</div>    



            </div>


        </div>

    </div> <!-- /container -->





@endsection