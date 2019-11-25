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



@if(Session::has('flash_message'))
    <div class="alert alert-success"> {!! session('flash_message') !!}</div>
@endif


                <div class="alert alert-danger">
                檢查您的電郵信箱，點擊通知書上的 "確認帳號鏈結" 以啟動您的帳號。<br />
                如收件箱中找不到激活郵件，可查看一下垃圾郵件或訂閱郵件等文件夾。<br />
                或在以下地方輸入電郵信箱以重發激活鏈結。
                </div>
                <center>
                <div role="form" class="form-inline" >
                {!! Form::open(['method' => 'POST', 'route' => ['activate.send', Auth::id()],'role' => 'form']) !!}

                    <div class="form-group">
                    {!! Form::label('email', '電子郵件') !!}
                    {!! Form::email('email', null, ['class'=> 'form-control', 'placeholder' => '請輸入學校電子郵件']) !!}
                    </div> 
                    <div class="form-group">
                    {!! Form::submit('重發激活鏈結', ['class' => 'btn btn-secondary']) !!}
                    </div>
                {!! Form::close() !!}  
                </div>
                </center>
                <br />
                

            </div>
        </div>

    </div> <!-- /container -->

@endsection