@extends('main.base')

@section('content')
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

	<div class="panel panel-default" id="app">
		<div class="panel-body">
			<div class="row">
				@csrf
				<div class="col-sm-3">
					<div class="form-group">
						<label>Bill No</label>
						<div class="readonly text-black">{{ $row->number }}</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="date">Date</label>
						<input type="date" class="form-control" id="dateFieldHtml5" v-model="post_data.date">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Customer Name</label>
						<input type="text" class="form-control" @keyup="getSimpleJson()" v-model="post_data.customer_name">
						<div class="panel-footer" v-if="Object.keys(customer_jsons).length">
							<ul class="list-group">
								<li class="list-group-item" v-for="(json, id) in customer_jsons" @click="putSingleId(id, json.name)"><% json.name %></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<table class="table table-sm table-bordered">
					<thead>
					<tr class="table-active">
						<th>Sr</th>
						<th>Name</th>
						<th>Price</th>
						<th>Qty</th>
						<th>Total</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(item, index) in post_data.items">
						<th scope="row"></th>
						<td><input type="text" class="form-control" @keyup="getMultipleJson(index)" v-model="item.item_name">
						<div class="panel-footer" v-if="(Object.keys(item_jsons).length) && (index == item_index)">
							<ul class="list-group">
								<li class="list-group-item" v-for="(json, id) in item_jsons" @click="putMultipleId(index, json)"><% json.name %></li>
							</ul>
						</div>
						</td>
						<td><input type="text" @keyup="calcTotal(index)" v-model="item.price"></td>
						<td><input type="text" @keyup="calcTotal(index)" v-model="item.quentity"></td>
						<td><input type="text" v-model="item.total_amount" readonly=""></td>
						<td><button @click="deleteItem(index)" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i></button>
							<button @click="addItem(item.item_name)" class="btn btn-sm btn-info"><i class="fa fa-plus-circle"></i></button></td>
					</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" class="text-right"> Total Amount : </td>
							<td><% net_total_price %></td>
							<td><% net_total_qty %></td>
							<td><% net_total_total %></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div class="panel-footer">
			<button @click="saveForm" type="button" class="btn btn-sm btn-primary">Save</button>
		</div>
	</div>

@stop

@section('javascript')
<script type="text/javascript">

// var csrf_token = $('[name="_token"]').val();
// console.log(csrf_token);

var app = new Vue({
	el: '#app',
	delimiters: ["<%","%>"],
	data: {
		customer_jsons: {},
		item_jsons: {},
		item_index:0,
		net_total_price:0,
		net_total_qty:0,
		net_total_total:0,
		post_data: {
			id: "{{ Crypt::encrypt($row->id) }}",
			type: 'Invoice',
			number: "{{ ($row->id > 0) ? $row->number : 'INV/20-21/0003' }}",
			date: "{{ $row->date }}",
			customer_id: "{{ ($row->id > 0) ? $row->party->id : 0 }}",
			customer_name: "{{ ($row->id > 0) ? $row->party->name : '' }}",
			items: [{
				item_name : '',
				item_id : 0,
				price : 0.00,
				quentity : 0.00,
				total_amount : 0.00,
			}],
		},
	},

	// created() {
	//   const article = { title: "Vue POST Request Example" };
	//   axios.post("/{{ $dir }}/{{ $model }}/" + this.post_data.id, article)
	//     .then(response => this.articleId = response.data.id);
	// },

	methods: {
		addItem(item_name) {
			if(item_name.length > 0) {
				this.post_data.items.push({
					item_name : '',
					item_id : 0,
					price : 0.00,
					quentity : 0.00,
					total_amount : 0.00,
				})
			} else {
				alert('Item Name Is required');
			}
		},

		deleteItem(index) {
			if(this.post_data.items.length > 1){
				this.post_data.items.splice(index,1)
			} else {
				alert('1 Item Require');
			}
		},

		getSimpleJson() {
			this.customer_jsons = {};
			if(this.post_data.customer_name.length > 2) {
				const json_data = {
				  query:this.post_data.customer_name,
				}
				axios.post('/master/Parties/simple_json', json_data)
					.then(response => {
						this.customer_jsons = response.data.data;
				});
			}
		},

		getMultipleJson(index) {
			this.item_index = index;
			this.item_jsons = {};
			if(this.post_data.items[index].item_name.length > 2) {
				const json_data = {
				  query:this.post_data.items[index].item_name,
				  select_fields: ['id', 'name', 'rate'],
				}
				axios.post('/master/Items/simple_json', json_data)
					.then(response => {
						console.log(response.data.data);
						this.item_jsons = response.data.data;
				});
			}
		},

		putSingleId(id, name) {
			this.post_data.customer_id = id;
			this.post_data.customer_name = name;
			this.customer_jsons = {};	
		},

		putMultipleId(index, row) {
			this.post_data.items[index].item_id = row.id;
			this.post_data.items[index].item_name = row.name;
			this.post_data.items[index].price = row.rate;
			this.item_jsons = {};
		},

		calcTotal(index) {
			this.post_data.items[index].total_amount = parseFloat(this.post_data.items[index].price) * parseFloat(this.post_data.items[index].quentity);
			this.calcNetTotal();
		},

		calcNetTotal() {
			this.net_total_price = 0;
			this.net_total_qty = 0;
			this.net_total_total = 0;
			for (var key in this.post_data.items) {
				this.net_total_price = parseFloat(this.net_total_price) + parseFloat(this.post_data.items[key].price);
				this.net_total_qty = parseFloat(this.net_total_qty) + parseFloat(this.post_data.items[key].quentity);
				this.net_total_total = parseFloat(this.net_total_total) + parseFloat(this.post_data.items[key].total_amount);
			}
		},

		saveForm() {
			const form_data = {
				post_data: this.post_data,
			}

			axios.post("/{{ $dir }}/{{ $model }}/" + this.post_data.id, form_data)
			.then(response => {
				this.post_data.id = "{{Crypt::encrypt("+response.data.id+")}}";
				console.log(response.data.id);
			})
			.catch(error => {
				this.errorMessage = error.message;
				console.error("There was an error!", error);
			});
		},

		Fetch_saveForm() {
			const requestOptions = {
				method: "POST",
				headers: { 
					"Content-Type": "application/json",
					'X-CSRF-TOKEN': "{{ csrf_token() }}",
				},
				// body: JSON.stringify({ this.post_data }),
			};

			fetch("/{{ $dir }}/{{ $model }}/" + this.post_data.id, requestOptions)
			.then(response => {
				return response.json()
			})
			.then(data => {
				console.log(data);
			});
		}
	}
})

</script>
@stop