@extends('main.base')

@section('content')

<div class="content" id="app">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><strong>{{ $dir }} / {{ $model }}</strong> <small class="text-success"><b> {{ ($id) ? 'Edit': 'Add' }} </b></small>
					<a class="btn btn-sm btn-info pull-right" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Add</a>
					</div>
					<div class="card-body card-block">
						<div class="row">
							@csrf
							<div class="col-sm-3">
								<div class="form-group">



							<div class="col-sm-6">
								<div class="form-group autocomplete">
									<label>Customer Name <span class="text-danger">*</span></label>
									<input type="text" class="form-control" :class="{'border-danger': errors['party']}" @keyup="getSimpleJson()" v-model="row.party_name">
										<div v-if="Object.keys(customer_jsons).length" id="myInputautocomplete-list" class="autocomplete-items">
											<div v-for="(json, id) in customer_jsons">
												<strong tabindex="-1"><% json.name %></strong>
											</div>
										</div>
								</div>
							</div>



								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('javascript')
<script>

new Vue({
  el: '#app',
  delimiters: ["<%","%>"],

	data: {
		errors: [],
		customer_jsons: {},
		item_jsons: {},
		
		item_index:0,
		net_total_price:0,
		net_total_qty:0,
		net_total_total:0,
		is_edit: true,

		row: {
			id: "{{ Crypt::encrypt($id) }}",
			user_id: "{{ Auth::user()->id }}",
			type: "Invoice",
			number: 'Generate After Save',
			date: "",
			party_id: 0,
			party_name: "",

			insert_items: [{
				item_name : '',
				item_id : 0,
				price : 0.00,
				quentity : 0.00,
				total_amount : 0.00,
			}],

			update_items: [],

			item_dir: 'Account',
			item_model: 'BillItems',
			parent_id: 'bill_id',
		},
	},
  methods: {
		getSimpleJson() {
			this.customer_jsons = {};
			if(this.row.party_name.length > 2) {
				const json_data = {
				  query:this.row.party_name,
				}
				axios.post('/master/Parties/simple_json', json_data)
					.then(response => {
						this.customer_jsons = response.data.data;
				});
			}
		},
		putSingleId(id, name) {
			this.row.party_id = id;
			this.row.party_name = name;
			this.customer_jsons = {};
		},  	
  }
})

</script>
@stop