@extends('main.base')

@section('content')

<div class="content" id="app">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><strong>{{ $dir }} / <a href="{{ URL::to($dir.'/'.$model) }}">{{ $model }}</a></strong> <small class="text-success"><b> {{ ($id) ? 'Edit': 'Add' }} </b></small>
					<a class="btn btn-sm btn-info pull-right" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Add</a>
					</div>
					<div class="card-body card-block">
						<div class="row">
							@csrf
							<div class="col-sm-6">
								<div class="form-group">
									<label>Item Name <span class="text-danger">*</span></label>
									<input class="form-control" :class="{'border-danger': errors['name']}" v-model="row.name">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="date">Category</label>
									<input class="form-control"v-model="row.category">
								</div>
							</div>
						</div>
						<div class="row">							
							<div class="col-sm-6">
								<div class="form-group">
									<label>Rate</label>
									<input type="text" class="form-control text-right" v-model="row.rate">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>HSN Code</label>
									<input type="text" class="form-control" v-model="row.hsn_code">
								</div>
							</div>							
						</div>
						<button @click="saveForm" type="button" class="btn btn-sm btn-primary">Save</button>
					</div>
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
		errors: [],
		is_edit: true,

		row: {
			id:       "{{ Crypt::encrypt($id) }}",
			user_id:  "{{ Auth::user()->id }}",
			name:     "",
			category: "",
			hsn_code: "",
			rate:     0,
		},
	},

	created() {
		const json_request = true;

		axios.get("/{{ $dir }}/{{ $model }}/" + this.row.id + '?' + "is_json_request=" + json_request)
			.then(response => {
				this.is_edit = (response.data.decrypt_id > 0) ? true : false;
				if(this.is_edit) {
					this.row.user_id  = response.data.data.user_id;
					this.row.name     = response.data.data.name;
					this.row.category = response.data.data.category;
					this.row.hsn_code = response.data.data.hsn_code;
					this.row.rate     = response.data.data.rate;
				}
			});
	},

	computed: {

	},

	methods: {
		validation() {
			this.errors = [];
			if( ! this.row.name) {
				this.errors.push("Item Name Is Required");
				this.errors['name'] = true;
			}
		},

		saveForm() {
			this.validation();
			if(this.errors.length > 0) {
				console.log('Error');
				return false;
			}

			const form_data = { row: this.row }
			// console.log(this.row.id); return;
			axios.post("/{{ $dir }}/{{ $model }}/" + this.row.id, form_data)
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