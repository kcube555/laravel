<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model {	
	public function __construct() {
		
	}

	public function simpleJson($post) {
		$select_field = isset($post['select_fields']) ? implode(',', $post['select_fields']): 'id, name';

		$data = [];
		$class =  get_called_class();

		$rows = $class::select(DB::raw($select_field))
			->where('name', 'like', $post['query']."%")
			->get();

		foreach ($rows as $r) {
			$data[$r->id] = $r;
		}

		return response()->json([
			'data' => $data,
		]);
	}
}
