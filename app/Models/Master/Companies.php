<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Companies extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $fillable = [
						'code',
						'name',
						'email',
						'mobile',
						'pan',
						'gstin',
						'address',
						'state_id',
					];

	public function getRows() {
		$data['rows'] = self::all();
		$data['headers'] = ['Code', 'Name', 'Email', 'Mobile'];
		$data['data'] = ['code', 'name', 'email', 'mobile'];
		$data['link'] = 1;

		return $data;
	}
}