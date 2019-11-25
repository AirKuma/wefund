@extends('layouts.master')

@section('content')

    <div class="container">

      <!-- Example row of columns -->
        <div class="row">
           <div class="col-sm-12">

                @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                
                @endif
                <div role="form">
                {!! Form::open(['route' => 'users.login', 'method' => 'post']) !!}
                    <div class="form-group">
                    {!! Form::label('email', '電子郵件') !!}
                    {!! Form::email('email', null, ['class'=> 'form-control']) !!}
                    </div>
                    <div class="form-group">
                    {!! Form::label('password', '密碼') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                    <div class="checkbox">
                        <label>
                        {!! Form::checkbox('remember')!!} 保持登入
                        </label>
                    </div>
                    {!! Form::submit('登入', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}  
                </div>
                <br />
                <a href="{!!URL::to('auth/facebook')!!}">使用 Facebook 帳號登入</a>

            </div>
        </div>

    </div> <!-- /container -->

@endsection