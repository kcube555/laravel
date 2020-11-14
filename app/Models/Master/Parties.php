<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Parties extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $fillable = array('name','address','city', 'pincode', 'mobile_no', 'email_id');

	public function getRows() {
		$data['rows'] = Parties::all();
		$data['headers'] = ['Name', 'Address', 'City', 'Pincode', 'Moble No.', 'Email Id'];
		$data['data'] = ['name', 'address', 'city', 'pincode', 'mobile_no', 'email_id'];
		$data['link'] = 1;
		// echo "<pre>"; print_r($data['rows']); exit();
		return $data;
	}
}
