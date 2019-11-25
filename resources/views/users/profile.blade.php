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
                  <h3 class="card-title">個人資料</h3>
                        {!! Form::model($profile, ['method' => 'PATCH', 'action' => ['Auth\ProfileController@patchUpdateProfile']]) !!}
                        
                        <div class="form-group">
                        {!! Form::label('lastname', '姓氏') !!}
                        {!! Form::text('lastname', null, ['class'=> 'form-control']) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('firstname', '名字') !!}
                        {!! Form::text('firstname', null, ['class'=> 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('gender', '性別') !!}
                            <label class="c-input c-radio">
                            {!! Form::radio('gender', '1', true, ['id' => 'male', 'name' => 'gender','onclick' => 'javascript: return false;']) !!}
                            <span class="c-indicator"></span>
                            男
                            </label>
                            <label class="c-input c-radio">
                            {!! Form::radio('gender', '0', null, ['id' => 'female', 'name' => 'gender','onclick' => 'javascript: return false;']) !!}
                            <span class="c-indicator"></span>
                            女
                            </label> 
                        </div> 

                        <div class="form-group">
                        {!! Form::label('major', '主修') !!}
                        @if($profile->major()->first()->id > 0)
                        {!! Form::select('major_id',$majors, null, ['class' => 'form-control', 'placeholder' => '請選擇學系']) !!}
                        @endif
                        </div>                          
                        <div class="form-group">
                        {!! Form::label('colleage', '就讀學校') !!}
                        {!! Form::text('colleage', $profile->college()->first()->name, ['class'=> 'form-control', 'disabled' => 'disabled']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('birthday', '生日') !!}
                            {!! Form::date('birthday', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('username', '使用者名稱') !!}
                        @if($profile->username==null)
                        {!! Form::text('username', null, ['class'=> 'form-control']) !!}
                        @else
                        {!! Form::hidden('user_name', 'none') !!}
                        {!! Form::text('username', null, ['class'=> 'form-control', 'disabled' => 'disabled']) !!}
                        @endif
                        <p class="help-block text-danger">※注意！使用者名稱填寫完後不能再進行修改。</p>
                        </div>
                        <div class="form-group">
                        {!! Form::label('phone', '電話') !!}
                        {!! Form::text('phone', null, ['class'=> 'form-control']) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('line_username', 'Line ID') !!}
                        {!! Form::text('line_username', null, ['class'=> 'form-control']) !!}
                        <p class="help-block text-danger">※注意！請將LIne裡面設置允許利用ID加入好友。</p>
                        </div>
                        <div class="form-group">
                        {!! Form::label('telegram_username', 'Telegram') !!}
                        {!! Form::text('telegram_username', null, ['class'=> 'form-control']) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('other_email', '其他電郵') !!}
                        {!! Form::text('other_email', null, ['class'=> 'form-control']) !!}
                        </div>

                        {!! Form::submit('更改' , ['class' => 'btn btn-secondary']) !!}
                        {!! Form::close() !!} 
                </div>
        </div>
    </div>

</div>










@endsection