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

		foreach ($files as $field => $file) {
			if(\Schema::hasColumn($this->getTable(), $field)) {
				if($file != 'null') {
					$file_ext = $file->getClientOriginalExtension();
					$unique_id = uniqid();
					$contents = file_get_contents($file->path());
					\Storage::disk('local')->put('Companies/'.$this->id.'/'.$unique_id.'.'.$file_ext, $contents);
					$this->$field = $unique_id.'.'.$file_ext;
				}
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

	public function getRows() {
		$data['rows'] = self::all();
		$data['headers'] = ['Code', 'Name', 'Email', 'Mobile'];
		$data['data'] = ['code', 'name', 'email', 'mobile'];
		$data['link'] = 1;

		return $data;
	}
}