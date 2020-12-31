@extends('main.base')

@section('content')
<ol class="mt-2 breadcrumb mb-1">
	<li class="breadcrumb-item active"><strong> {{ $dir }}</strong></li>
	<li class="breadcrumb-item"><a href="{{ URL::to($dir.'/'.$model) }}">{{ $model }}</a></li>
	<li class="breadcrumb-item"><b class="text-success"> {{ ($id) ? 'Edit': 'Add' }} </b></li>
	<a class="btn btn-sm btn-info ml-auto" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Create New</a>
</ol>

<div id="app">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body card-block">
					<div class="row">
						@csrf
						<div class="col-sm-3">
							<div class="form-group">
								<label>Bill No</label>
								<div class="text-black readonly"><% row.number %></div> 
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="date">Date <span class="text-danger">*</span></label>
								<v-date-picker v-model="row.date">
									<template v-slot="{ inputValue, inputEvents }">
										<input class="form-control" class="bg-white border px-2 py-1 rounded" :value="inputValue" v-on="inputEvents" />
									</template>
								</v-date-picker>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Customer Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" :class="{'border-danger': errors['party']}" @keyup="getSimpleJson()" v-model="row.party_name">
								<div style="position:relative" class="panel-footer" v-if="Object.keys(customer_jsons).length">
									<ul class="list-group bg-transparent">
										<li class="list-group-item bg-transparent" v-for="(json, id) in customer_jsons" @click="putSingleId(id, json.name)"><% json.name %></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label for="date">Validity</label>
								<v-date-picker v-model="row.validity">
									<template v-slot="{ inputValue, inputEvents }">
										<input class="form-control" class="bg-white border px-2 py-1 rounded" :value="inputValue" v-on="inputEvents" />
									</template>
								</v-date-picker>
							</div>
						</div>				
					</div>					
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Terms & Conditions</label>
								<textarea class="form-control" rows="3" v-model="row.terms"></textarea>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Remarks</label>
								<textarea class="form-control" rows="3" v-model="row.remarks"></textarea>
							</div>
						</div>
					</div>					
					<div class="row">
						<table class="table table-sm table-bordered">
							<thead>
							<tr class="bg-light">
								<th>Sr</th>
								<th width="40%">Name <span class="text-danger">*</span></th>
								<th>Price</th>
								<th>Qty</th>
								<th>Gst %</th>
								<th>Total</th>
								<th width="10%">Action</th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="(item, index) in row.update_items">
								<td><small><% index + 1 %></small></td>
								<td width="40%"><span class="input-group-text"><small><b> <% item.item_name %> </b></small></span></td>
								<td><input type="text" class="form-control form-control-sm" v-model="item.price"></td>
								<td><input type="text" class="form-control form-control-sm" v-model="item.quentity"></td>
								<td><div class="input-group input-group-sm mb-2">
										<input type="text" class="form-control" v-model="item.gst_per">
										<div class="input-group-append">
											<span class="input-group-text"><% item.gst_amt %></span>
										</div>
									</div></td>
								<td><span class="input-group-text"><small><% itemTotal(item) %></small></span></td>
								<td>{!! $common->edit_button !!}</td>
							</tr>

							<tr v-for="(item, index) in row.insert_items">
								<th></th>
								<td width="40%"><input type="text" class="form-control form-control-sm" @keyup="getMultipleJson(item, index)" v-model="item.item_name">
								<div class="panel-footer" v-if="(Object.keys(item_jsons).length) && (index == item_index)">
									<ul class="list-group">
										<li class="list-group-item" v-for="(json, id) in item_jsons" @click="putMultipleId(item, json)"><% json.name %></li>
									</ul>
								</div>
								</td>
								<td><input type="text" class="form-control form-control-sm" v-model="item.price"></td>
								<td><input type="text" class="form-control form-control-sm" v-model="item.quentity"></td>
								<td>
									<div class="input-group input-group-sm mb-3">
										<input type="text" class="form-control" v-model="item.gst_per">
										<div class="input-group-append">
											<span class="input-group-text"><% item.gst_amt %></span>
										</div>
									</div>
								</td>
								<td><span class="input-group-text"><small><% itemTotal(item) %></small></span></td>
								<td>{!! $common->add_button !!}</td>
							</tr>
							</tbody>
							<tfoot>
							<tr>
								<td colspan="2" class="text-right"> Total Amount : </td>
								<td><% net_total_price %></td>
								<td><% net_total_qty %></td>
								<td></td>
								<td><% row.invoice_value %></td>
								<td></td>
							</tr>
							</tfoot>
						</table>
					</div>
					<button type="button" @click="saveForm" class="btn btn-sm btn-success">Save</button>
					@if($id)
						<a class="btn btn-sm btn-secondary" target="_blank" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt($id).'/export?format=pdf') }}" role="button">PDF</a>
						<a class="btn btn-sm btn-primary" target="_blank" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt($id).'/export?format=pdf&email=1') }}" role="button">Mail</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('javascript')
<script type="text/javascript">

var app = new Vue({
	el: '#app',
	delimiters: ["<%","%>"],

	data: {
		errors:         [],
		customer_jsons: {},
		item_jsons:     {},
		
		item_index:      0,
		net_total_price: 0,
		net_total_qty:   0,
		is_edit:         true,

		row: {
			id:            "{{ Crypt::encrypt($id) }}",
			user_id:       "{{ Auth::user()->id }}",
			type:          "Invoice",
			number:        'Generate After Save',
			date:          null,
			validity:      null,			
			party_id:      0,
			party_name:    null,
			invoice_value: 0.00,
			terms:         null,
			remarks:       null,			

			files: [],

			insert_items: [{
				item_name:    null,
				item_id:      0,
				price:        0.00,
				quentity:     0.00,
				// file:      null,
				gst_per:      0.00,
				gst_amt:      0.00,
				total_amount: 0.00,
			}],

			update_items: [],
			
			delete_items: [],

			item_dir: 'Account',
			item_model: 'BillItems',
			parent_id: 'bill_id',
		},
	},

	created() {
		const json_request = true;

		axios.get("/{{ $dir }}/{{ $model }}/" + this.row.id + '?' + "is_json_request=" + json_request)
			.then(response => {
				this.is_edit = (response.data.decrypt_id > 0) ? true: false;
				if(this.is_edit) {
					this.row.user_id      = response.data.data.user_id;
					this.row.type         = response.data.data.type;
					this.row.number       = response.data.data.number;
					this.row.date         = response.data.data.date;
					this.row.validity     = response.data.data.validity;
					this.row.terms        = response.data.data.terms;
					this.row.remarks      = response.data.data.remarks;
					this.row.party_id     = response.data.data.party_id;
					this.row.party_name   = response.data.data.party.name;
					this.row.update_items = response.data.data.bill_items;
					this.calcNetTotal();
				}
			});
	},

	computed: {

	},

	methods: {
		handleFileObject(event) {
			this.row.files.splice(this.row.files.indexOf(event), 1);
			this.row.files = [...this.row.files, event.target.files[0]];
		},

		ItemhandleFile(event, index) {
			this.row.insert_items[index].file = event.target.files[0];
		},
		
		itemTotal(item) {
			let item_total = 0.00;
			let gst_amt    = 0.00;

			item_total = parseFloat(item.price) * parseFloat(item.quentity);
			if(item_total > 0) {
				item.gst_amt = ((item_total * item.gst_per)  / 100);
				item_total = item_total + item.gst_amt;
			}

			item.total_amount = item_total;
			this.calcNetTotal();
			return item_total;
		},

		addItem(item) {
			if(item.item_name != null) {
				if(item.item_id > 0) {
					this.row.insert_items.push({
						item_name : '',
						item_id : 0,
						price : 0.00,
						quentity : 0.00,
						total_amount : 0.00,
					});
				} else {
					alert('Item name must be in valid selection');	
				}
			} else {
				alert('Item name is required');
			}
		},

		deleteItem(index, item_id) {
			if(this.itemValidation()) {
				this.row.update_items.splice(index, 1);
				this.row.delete_items.push(item_id);
			} else {
				alert('Min 1 Item Require');
			}
		},

		removeItem(index) {
			if(this.itemValidation()) {
				this.row.insert_items.splice(index,1);
			} else {
				alert('Min 1 Item Require');
			}
		},

		getSimpleJson() {
			this.customer_jsons = {};
			if(this.row.party_name != null && this.row.party_name.length > 2) {
				const json_data = {
				  query:this.row.party_name,
				}
				axios.post('/master/Parties/simple_json', json_data)
					.then(response => {
						this.customer_jsons = response.data.data;
				});
			}
		},

		getMultipleJson(item, index) {
			this.item_index = index;
			this.item_jsons = {};
			if(item.item_name != null && item.item_name.length > 2) {
				const json_data = {
				  query:item.item_name,
				  select_fields: ['id', 'name', 'rate'],
				}
				axios.post('/master/Items/simple_json', json_data)
					.then(response => {
						this.item_jsons = response.data.data;
				});
			}
		},

		putSingleId(id, name) {
			this.row.party_id = id;
			this.row.party_name = name;
			this.customer_jsons = {};
		},

		putMultipleId(item, row) {
			item.item_id = row.id;
			item.item_name = row.name;
			item.price = row.rate;
			this.item_jsons = {};
		},

		calcNetTotal() {
			let net_total_price = 0;
			let net_total_qty   = 0;
			let net_total_total = 0;
			let item = 0;

			for (var key in this.row.insert_items) {
				item = this.row.insert_items[key];
				net_total_price += parseFloat(item.price);
				net_total_qty   += parseFloat(item.quentity);
				net_total_total += parseFloat(item.price * item.quentity);
				net_total_total += parseFloat(item.gst_amt);
			}

			if(this.is_edit) {
				for (var key in this.row.update_items) {
					item = this.row.update_items[key];
					net_total_price += parseFloat(item.price);
					net_total_qty   += parseFloat(item.quentity);
					net_total_total += parseFloat(item.price * item.quentity);
					net_total_total += parseFloat(item.gst_amt);
				}
			}

			this.net_total_price   = net_total_price;
			this.net_total_qty     = net_total_qty;
			this.row.invoice_value = net_total_total;
		},

		itemValidation() {
			const flag        = false;
			const total_items = this.row.insert_items.length + this.row.update_items.length;

			if (total_items > 1) {
				return true;
			}

			return flag;
		},

		validation() {
			this.errors = [];
			if( ! this.row.date) {
				this.errors.push("Date required");
				this.errors['date'] = true;
			}

			if( ! this.row.party_name) {
				this.errors.push("Customer name required");
				this.errors['party'] = true;
			}

			if(this.row.party_id == 0) {
				this.errors.push("Customer name must be in valid selection");
				this.errors['party'] = true;
			}
		},

		saveForm() {
			this.validation();
			if(this.errors.length > 0) {
				console.log('Error');
				return false;
			}

			const config = {
				headers: { 'content-type': 'multipart/form-data' }
			}

			const row_data = JSON.stringify(this.row);

			let formData = new FormData()
			formData.append('row', row_data);
			for (var i = this.row.files.length - 1; i >= 0; i--) {
				formData.append('files[]', this.row.files[i]);
			}
			axios.post("/{{ $dir }}/{{ $model }}/" + this.row.id, formData, config)
			.then(response => {
				this.row.id = response.data.id;
				window.location.href = "/{{ $dir }}/{{ $model }}/" + this.row.id;
			})
			.catch(error => {
				this.errorMessage = error.message;
				console.error("There was an error!", error);
			});
		}
	}
})

</script>
@stop