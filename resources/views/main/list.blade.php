@extends('main.base')

@section('content')
<ol class="mt-2 breadcrumb mb-1">
	<li class="breadcrumb-item active">{{ $dir }}</li>
	<li class="breadcrumb-item">{{ $model }}</li>
	<form class="mx-auto d-none d-md-inline-block form-inline mr-0 mr-md-3 my-2 my-md-0">
		<div class="input-group">
			<input class="form-control form-control-sm" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
			<div class="input-group-append">
				<button class="btn btn-sm btn-primary" type="button"><i class="fa fa-search"></i></button>
			</div>
		</div>
	</form>    
	<a class="btn btn-sm btn-info ml-auto" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Create New</a>
</ol>

<div class="row">
	<div class="col-md-12">
		<table class="table table-sm table-bordered" width="100%" cellspacing="0">
			<thead>
				<tr>
				 @foreach($headers as $header)
					<th>{{ $header }}</th>
				 @endforeach
				</tr>
			</thead>
			<tbody>
				@if ( count($rows) > 0 )
					@foreach ( $rows as $row )
						<tr>
						@foreach ( $data as $key => $d )
							@isset ( $link )
								@if ( $link == $key )
									<?php $url_id = Crypt::encrypt($row->id) ?>
									<td><a href="{{ URL::to($dir.'/'.$model.'/'.$url_id) }}">{{ $row->$d }}</a></td>
									@continue
								@endif
							@endisset
							<td>{{ $row->$d }}</td>
						@endforeach
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="{{ count($headers) }}" class="text-center"> There are no row to display </td>
					<tr>	
				@endif	
			</tbody>
		</table>
	</div>
</div>


<!-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
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
</div> -->
@stop