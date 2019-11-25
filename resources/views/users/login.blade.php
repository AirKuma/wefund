@extends('layouts.master')

@section('content')

    <div class="container">

      <!-- Example row of columns -->
        <div class="row">
           <div class="col-md-6 col-centered card card-block" style="background-color: #FFFFFF;">

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
                    {!! Form::submit('登入', ['class' => 'btn btn-secondary']) !!}

                    <a class="facebookIcon btn btn-secondary" href="{!!URL::to('auth/facebook')!!}">Facebook 帳號登入</a>
                    <a class="btn btn-link" href="{{ url('/password/email') }}">忘記密碼?</a>
                {!! Form::close() !!}  
                </div>
                
            </div>
        </div>

    </div> <!-- /container -->

@endsection