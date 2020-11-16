<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Items extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $fillable = array('name','category','rate', 'hsn_code');

	public function getRows() {
		$data['rows'] = self::all();
		$data['headers'] = ['Name', 'Categoty', 'Rate', 'Hsn Code'];
		$data['data'] = ['name', 'category', 'rate', 'hsn_code'];
		$data['link'] = 0;

		return $data;
	}
}
