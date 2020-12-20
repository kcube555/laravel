<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Items extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $fillable = [
						'name',
						'category',
						'rate',
						'gst',
						'hsn_code'
					];

	public function getRows() {
		$data['rows']    = self::all();
		$data['headers'] = ['Name', 'Categoty', 'Rate', 'GST', 'Hsn Code'];
		$data['data']    = ['name', 'category', 'rate', 'gst', 'hsn_code'];
		$data['link']    = 0;

		return $data;
	}
}
