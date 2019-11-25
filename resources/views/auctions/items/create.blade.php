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
          {!! Form::open(['route' => ['post.auction.item', $auction], 'method' => 'post','files' => true] ) !!}

           @include('auctions.items.forms.form', ['submitButtonText' => '新增'])

          {!! Form::close() !!}
                </div>


            </div>
            </div>
        </div>

    </div> <!-- /container -->

@endsection