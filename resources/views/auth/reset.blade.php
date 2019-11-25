@extends('layouts.master')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-centered card card-block" style="background-color: #FFFFFF;">
			<div class="panel panel-default">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<label class="control-label">E-Mail</label>
							<div class="">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">密碼</label>
							<div class="">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">確認密碼</label>
							<div class="">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="">
								<button type="submit" class="btn btn-secondary">
									重設密碼
								</button>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>
</div>
@endsection
