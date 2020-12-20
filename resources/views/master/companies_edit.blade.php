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
							<div class="col-sm-2">
								<div class="form-group">
									<label>Code <span class="text-danger">*</span></label>
									<input type="text" class="form-control" :class="{'border-danger': errors['party']}" v-model="row.code">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label>Name </label>
									<input type="text" class="form-control" :class="{'border-danger': errors['name']}" v-model="row.name">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" :class="{'border-danger': errors['email']}" v-model="row.email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<div class="form-group">
									<label>State</label>
									<input type="text" class="form-control" v-model="row.state_id">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label>PAN</label>
									<input type="text" class="form-control" v-model="row.pan">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label>GSTIN</label>
									<input type="text" class="form-control" v-model="row.gstin">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Address</label>
									<textarea type="text" class="form-control" v-model="row.address"></textarea>
								</div>
							</div>
						</div>

						<button type="button" @click="saveForm" class="btn btn-sm btn-success">Save</button>
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

		is_edit: true,

		row: {
			id:       "{{ Crypt::encrypt($id) }}",
			code:     null,
			name:     null,
			email:    null,
			mobile:   0,
			pan:      null,
			gstin:    null,
			address:  null,
			state_id: 0,
		},
	},

	created() {
		const json_request = true;

		axios.get("/{{ $dir }}/{{ $model }}/" + this.row.id + '?' + "is_json_request=" + json_request)
			.then(response => {
				this.is_edit = (response.data.decrypt_id > 0) ? true: false;
				if(this.is_edit) {
					this.row.code     = response.data.data.code;
					this.row.name     = response.data.data.name;
					this.row.email    = response.data.data.email;
					this.row.mobile   = response.data.data.mobile;
					this.row.pan      = response.data.data.pan;
					this.row.gstin    = response.data.data.gstin;
					this.row.address  = response.data.data.address;
					this.row.state_id = response.data.data.state_id;
				}
			});
	},

	methods: {
		validation() {
			this.errors = [];
			if( ! this.row.code) {
				this.errors.push("Code required");
				this.errors['code'] = true;
			}

			if( ! this.row.name) {
				this.errors.push("Company name required");
				this.errors['name'] = true;
			}

			if( ! this.row.email) {
				this.errors.push("Email required");
				this.errors['email'] = true;
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