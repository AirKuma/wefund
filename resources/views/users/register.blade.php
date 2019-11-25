@extends('layouts.master')

@section('content')

    <div class="container">
        <div class="row">



           <div class="col-md-6 col-centered card card-block" style="background-color: #FFFFFF;">
            <h4> 註冊為會員 </h4>

                @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                @endif

                {!! Form::open(['route' => 'users.register', 'method' => 'post', 'role' => 'form']) !!}

                <div class="form-group">
                    {!! Form::label('email', '學校電子郵件') !!}
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password', '密碼') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password_confirmation', '確認密碼') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('lastname', '姓氏') !!}
                    {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('firstname', '名字') !!}
                    {!! Form::text('firstname',null, ['class' => 'form-control']) !!}
                </div>

                <div class="radio">
                    <label class="c-input c-radio">
                        {!! Form::radio('gender', '1') !!} 
                        <span class="c-indicator"></span>男 
                    </label>
                    <label class="c-input c-radio">
                        {!! Form::radio('gender', '0') !!}
                        <span class="c-indicator"></span>女 
                    </label>
                </div>

                <div class="form-group">                  
                    {!! Form::select('major_id',$majors, null, ['class' => 'form-control', 'placeholder' => '請選擇學系']) !!}
                </div>


                <div class="form-group">
                    {!! Form::label('birthday', '生日') !!}
                    {!! Form::date('birthday', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('註冊', ['class' => 'btn btn-secondary']) !!}
                <!-- {!! Form::reset('Clear', ['class' => 'btn btn-danger']) !!} -->
                {!! Form::close() !!}
            </div>
        </div>

    </div>

@endsection