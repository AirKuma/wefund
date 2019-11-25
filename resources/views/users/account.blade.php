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
                  <h3 class="card-title">個人帳號</h3>
                        {!! Form::model($profile, ['method' => 'PATCH', 'action' => ['Auth\ProfileController@patchUpdateProfilePassword']]) !!}
                        

                        <div class="form-group">
                        {!! Form::label('email', '學校電郵') !!}
                        @if($profile->email != null)
                        {!! Form::text('email', null, ['class'=> 'form-control', 'disabled' => 'disabled']) !!}
                        @else
                        {!! Form::text('email', null, ['class'=> 'form-control']) !!}
                        @endif
                        </div>

                        <div class="form-group">
                        {!! Form::label('old_password', '現在密碼') !!}
                        {!! Form::password('old_password', ['class' => 'form-control']) !!}
                        </div>                        


                        <div class="form-group">
                        {!! Form::label('password', '新密碼') !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('password_confirmation', '重輸新密碼') !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                        </div>
                        @if($profile->email == null)
                        {!! Form::submit('建立' , ['class' => 'btn btn-secondary']) !!}
                        @else
                        {!! Form::submit('更改' , ['class' => 'btn btn-secondary']) !!}
                        @endif
                        {!! Form::close() !!} 
                </div>
        </div>
    </div>

</div>










@endsection