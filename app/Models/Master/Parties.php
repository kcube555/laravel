<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Parties extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $primaryKey = 'id';

	protected $fillable = [
						'name',
						'code',
						'address',
						'city',
						'pincode',
						'mobile',
						'email'
					];

	public function getRows() {
		$data['rows'] = self::all();
		$data['headers'] = ['Code', 'Name', 'City', 'Pincode', 'Moble No.', 'Email Id'];
		$data['data'] = ['code', 'name', 'city', 'pincode', 'mobile', 'email'];
		$data['link'] = 1;
		return $data;
	}
}
