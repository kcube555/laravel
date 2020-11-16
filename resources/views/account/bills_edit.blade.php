@extends('main.base')

@section('content')

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	<div class="panel-heading">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h6>{{ $dir }} / {{ $model }}</h6>
			<div class="btn-toolbar mb-2 mb-md-0">
				<div class="btn-group mr-2">
					<a class="btn btn-sm btn-info" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Add New</a>
				</div>
			</div>
		</div>
	</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label>Bill No</label>
					<div class="readonly text-black">{{ $row->number }}</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="date">Date</label>
					<input type="date" class="form-control" id="dateFieldHtml5" value="{{ $row->date }}">
			</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>Customer Name</label>
					<input type="text" class="form-control" id="date" value="{{ $row->party->name }}">
				</div>
			</div>
		</div>
		<div class="">
			<table class="table">
				<thead>
				<tr>
					<th scope="col">Sr</th>
					<th scope="col">Name</th>
					<th scope="col">Price</th>
					<th scope="col">Qty</th>
					<th scope="col">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th scope="row"></th>
					<td><input type="text" name="item_name[]"></td>
					<td><input type="text" name="price[]"></td>
					<td><input type="text" name="qty[]"></td>
					<td><input type="text" name="total[]" readonly=""></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

</main>

@stop