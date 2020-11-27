@extends('main.base')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Dashboard</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group mr-2">
			<a class="btn btn-sm btn-primary" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Add New</a>
			<button class="btn btn-sm btn-outline-secondary">Add New</button>
		</div>
		<button class="btn btn-sm btn-outline-secondary dropdown-toggle">
			<span data-feather="calendar"></span>
			This week
		</button>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
		<tr>
		 @foreach($headers as $header)
			<th>{{ $header }}</th>
		 @endforeach
		</tr>
		</thead>
		<tbody>
		 @foreach($rows as $row)
			<tr>
			@foreach($data as $key => $d)
				@isset($link)
					@if($link == $key)
						<?php $url_id = Crypt::encrypt($row->id) ?>
						<td><a href="{{ URL::to($dir.'/'.$model.'/'.$url_id) }}">{{ $row->$d }}</a></td>
						@continue
					@endif
				@endisset
				<td>{{ $row->$d }}</td>
		 	@endforeach
			</tr>
		 @endforeach
		</tbody>
	</table>
</div>
@stop