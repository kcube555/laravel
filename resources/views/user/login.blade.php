<!DOCTYPE html>
<html>
<head>
	<title>Login Form</title>

	<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">

	<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

	<style type="text/css">
		.center_div {
			width: 50%;
			margin: 10% auto;
		}
	</style>

</head>

<body>
	<div class="container center_div">
		<h2>LogIn form</h2>
		<div class="row">
			<div class="col-sm-12">
				<form method="post" action="{{ url('login') }}">					
					@csrf
					@if(Session::has('success'))
						<div class="alert alert-success">
							{{ Session::get('success') }}
						</div>
					@elseif(Session::has('error'))
						<div class="alert alert-danger">
							{{ Session::get('error') }}
						</div>						
					@endif
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" class="form-control" id="email" value="{{ old('email')}}" placeholder="Enter email" name="email">
						{!! $errors->first('email', '<small class="text-danger">:message</small>') !!}
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="text" class="form-control" id="password" placeholder="Enter password" name="password">
						{!! $errors->first('password', '<small class="text-danger">:message</small>') !!}
					</div>
					<div class="form-group form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" name="remember"> Remember me
						</label> 
					</div>
					<button type="submit" class="btn btn-primary">Login</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>