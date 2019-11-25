@extends('layouts.master')

@section('content')
<div class="container-fluid">
	<div class="row">

<div class="col-md-6 col-centered card card-block" style="background-color: #FFFFFF;">
			<div class="panel panel-default">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="control-label">E-Mail</label>
							<div class="">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="">
								<button type="submit" class="btn btn-secondary">
									寄送密碼連結
								</button>
							</div>
						</div>
					</form>
				
			</div>
		</div>
	</div>
</div>
@endsection
