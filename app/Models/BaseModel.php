<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseModel extends Model {
	protected static function boot()
	{
		parent::boot();
	}

	public function fetchRow() {
		$data = $this;

		return $data;
	}

	public function saveForm($id, $post, $files) {
		foreach ($post as $key => $value) {
			if(\Schema::hasColumn($this->getTable(), $key)) {
				if($this->getKeyName() == $key) {
					$this->$key = \Crypt::decrypt($value);
					continue;
				}
				$this->$key = $value;
			}
		}

		$this->save();

		if(isset($post->item_dir)) {
			$child_model_name = "\App\Models"."\\".$post->item_dir."\\".$post->item_model;
			$parent_id        = $post->parent_id;
		}

		if(isset($post->insert_items)) {
			foreach ($post->insert_items as $items) {
				$row = new $child_model_name();

				foreach ($items as $key => $value) {
					if(\Schema::hasColumn($row->getTable(), $key))
						$row->$key = $value;
				}
				$row->$parent_id = $this->id;
				
				if($row->validation())
					$row->save();
			}
		}

		if(isset($post->update_items)) {
			foreach ($post->update_items as $items) {
				$row = $child_model_name::whereId($items->id)->first();

				foreach ($items as $key => $value) {
					if(\Schema::hasColumn($row->getTable(), $key)) {
						$row->$key = $value;
					}
				}
				$row->save();
			}
		}

		if(isset($post->delete_items)) {
			foreach (array_unique($post->delete_items) as $id) {
				try {
					$row = $child_model_name::findOrFail($id);
					$row->delete();
				} catch(ModelNotFoundException $e) {
					\Log::info(get_class_methods($e));
				}
			}
		}

		return $this->id;
	}

	public function simpleJson($post) {
		$select_field = isset($post['select_fields']) ? implode(',', $post['select_fields']): 'id, name';

		$data  = [];
		$class = get_called_class();

		$rows = $class::select(DB::raw($select_field))
			->where('name', 'like', "%".$post['query']."%")
			->get();

		foreach ($rows as $r) {
			$data[$r->id] = $r;
		}

		return response()->json([
			'data' => $data,
		]);
	}
}
