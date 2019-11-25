@extends('layouts.master')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-sm-8">
      One of three columns
    </div>
    <div class="col-sm-4">
                <h2> 註冊為會員 </h2>

                @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                @endif

                {!! Form::open(['route' => 'users.register', 'method' => 'post', 'role' => 'form']) !!}

                <div class="form-group">
                    
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '學校電子郵件']) !!}
                </div>

                <div class="form-group">
                    
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '密碼']) !!}
                </div>

                <div class="form-group">
                    
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '重新輸入密碼']) !!}
                </div>

                <div class="form-group">
                   
                    {!! Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => '姓氏']) !!}
                </div>

                <div class="form-group">
                  
                    {!! Form::text('firstname',null, ['class' => 'form-control', 'placeholder' => '名字']) !!}
                </div>



                <div class="form-group">
                    {!! Form::select('major_id',$majors, null, ['class' => 'form-control', 'placeholder' => '請選擇學系']) !!}         
                </div>

                        <div class="form-group">
                            {!! Form::label('gender', '性別') !!}
                            <label class="c-input c-radio">
                            {!! Form::radio('gender', '1', true, ['id' => 'male', 'name' => 'gender']) !!}
                            <span class="c-indicator"></span>
                            男
                            </label>
                            <label class="c-input c-radio">
                            {!! Form::radio('gender', '0', null, ['id' => 'female', 'name' => 'gender']) !!}
                            <span class="c-indicator"></span>
                            女
                            </label> 
                        </div> 


{!! Form::label('birthday', '出生日期') !!}
                <div class="form-group">
                    
                    <!-- {!! Form::date('birthday', null, ['class' => 'form-control']) !!} -->
<!--                     http://stackoverflow.com/questions/30866079/how-to-validate-an-input-date-from-individual-day-month-year-input-in-laravel
 -->
                    
                    {!! Form::selectRange('day', 1, 31, null, ['class' => 'form-control']) !!}
                                  
                   
                    {!! Form::selectMonth('month', null, ['class' => 'form-control']) !!}
                    

                    
                    {!! Form::selectYear('year', Carbon\Carbon::now()->year, (new Carbon\Carbon('100 years ago'))->year, null , ['class' => 'form-control']) !!}
                    
                </div>

<!-- $user = new User(Input::all());
$user->password = md5(Input::get('password'));

$birthday = Input::get('birthdayYear')."-".Input::get('birthdayMonth')."-".Input::get('birthdayDay');

$user->birthday = date('Y-m-d H:i:s', strtotime($birthday));
$user->save(); -->



                <div class="form-group">
                <br />
                {!! Form::submit('註冊', ['class' => 'btn btn-primary']) !!}
                </div>
                <!-- {!! Form::reset('Clear', ['class' => 'btn btn-danger']) !!} -->
                {!! Form::close() !!}
    </div>
  </div>
</div>

@endsection