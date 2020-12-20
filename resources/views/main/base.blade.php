<!DOCTYPE html>
<html>
<head>
	<title>{{ $title ?? 'Welcome' }}</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Bharat Work" />
	<meta name="author" content="Bharat Work" />

	<!-- <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}"> -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ URL::asset('css/perfect.css') }}">
</head>
<body class="sb-nav-fixed">

	@include('main.header')

	<div id="layoutSidenav">

		@include('main.sliderbar')

		<div id="layoutSidenav_content">
        	<main>
                <div class="container-fluid">
					@yield('content')
				</div>
			</main>
		</div>
	</div>

	<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/perfect.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/vue.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/axios.min.js') }}"></script>

	@yield('javascript')

</body>
</html>