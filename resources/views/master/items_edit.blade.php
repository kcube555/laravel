@extends('main.base')

@section('content')

<ol class="mt-2 breadcrumb mb-1">
	<li class="breadcrumb-item active"><strong> {{ $dir }}</strong></li>
	<li class="breadcrumb-item"><a href="{{ URL::to($dir.'/'.$model) }}">{{ $model }}</a></li>
	<li class="breadcrumb-item"><b class="text-success"> {{ ($id) ? 'Edit': 'Add' }} </b></li>
	<a class="btn btn-sm btn-info ml-auto" href="{{ URL::to($dir.'/'.$model.'/'.Crypt::encrypt(0)) }}" role="button">Create New</a>
</ol>

<div class="content" id="app">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
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
							<div class="col-sm-3">
								<div class="form-group">
									<label>Rate</label>
									<input type="text" class="form-control text-right" v-model="row.rate">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>GST % </label>
									<input type="text" class="form-control text-right" v-model="row.gst">
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
			name:     null,
			category: null,
			hsn_code: null,
			rate:     0,
			gst:      0,
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
					this.row.gst      = response.data.data.gst;
				}
			});
	},

	computed: {

	},

	methods: {
		validation() {
			this.errors = [];
			if( ! this.row.name) {
				this.errors.push("Item name required");
				this.errors['name'] = true;
			}
		},

		saveForm() {
			this.validation();
			if(this.errors.length > 0) {
				console.log('Error');
				return false;
			}

			const row_data = JSON.stringify(this.row);

			let formData = new FormData()
			formData.append('row', row_data);

			axios.post("/{{ $dir }}/{{ $model }}/" + this.row.id, formData)
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