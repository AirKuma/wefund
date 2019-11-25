@extends('layouts.master')

@section('content')

<div class="container">

    <div class="row">
        <div class="col-lg-3">
        @include('users.profilemenu')
        </div>
        <div class="col-lg-9 search">
        @include('errors.list')            
                <div class="card card-block" style="background-color: #FFFFFF;">
                  <h3 class="card-title">社群設置</h3>
                  
                        {!! Form::open(['route' => 'post.facebook.integrate', 'method' => 'post', 'role' => 'form']) !!}
                        

                        <div class="form-group">
                        {!! Form::label('password', '密碼') !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>   
                    
                        {!! Form::submit('綁定 Facebook 帳號' , ['class' => 'facebookIcon btn btn-secondary']) !!}
                       
                        {!! Form::close() !!}
                    
                </div>
        </div>
    </div>

</div>










@endsection