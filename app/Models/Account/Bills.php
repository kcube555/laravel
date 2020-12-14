<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Bills extends \App\Models\BaseModel {

	protected $primaryKey = 'id';

	protected $fillable = array('type', 'sub_type','number', 'date', 'party_id', 'total_amount', 'terms', 'remarks');

	public static function boot()
	{
		parent::boot();

		self::creating(function($model){
			// ... code here
		});

		self::created(function($model){
			$model->number = 'INV/20-21/'.str_pad($model->id, 4, '0', STR_PAD_LEFT);
			$model->save();
		});

		self::updating(function($model){
			// ... code here
		});

		self::updated(function($model){
			// ... code here
		});

		self::deleting(function($model){
			// ... code here
		});

		self::deleted(function($model){
			// ... code here
		});
	}	

	public function party() {
		return $this->belongsTo('\App\Models\Master\Parties');
	}

	public function billItems() {
		return $this->hasMany('\App\Models\Account\BillItems', 'bill_id');
	}

	public function getRows() {
		$data['rows'] = \DB::table('bills')
							->join('parties', 'parties.id', '=', 'bills.party_id')
							->select('bills.*', 'parties.name as party_name')
							->get();

		$data['headers'] = ['Number', 'Date', 'Customer Name', 'Total Amount'];
		$data['data'] = ['number', 'date', 'party_name', 'total_amount'];

		$data['link'] = 0;

		return $data;
	}

	public function fetchRow() {
		$data             = $this;
		$data->party      = $this->party;
		$data->bill_items = $this->billItems;

		foreach ($this->billItems as $key => $item) {
			$data->billItems->$key = $item;
			$data->billItems->$key->item_name = (isset($item->item->name)) ? $item->item->name : null;
		}

		return $data;
	}

	public function saveForm($id, $post) {
		$post = $post['row'];

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

		$child_model_name = "\App\Models"."\\".$post['item_dir']."\\".$post['item_model'];
		$parent_id  = $post['parent_id'];

		if(isset($post['insert_items'])) {

			foreach ($post['insert_items'] as $items) {
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

		if(isset($post['update_items'])) {
			foreach ($post['update_items'] as $items) {
				$row = $child_model_name::whereId($items['id'])->first();

				foreach ($items as $key => $value) {
					if(\Schema::hasColumn($row->getTable(), $key)) {
						$row->$key = $value;
					}
				}
				$row->save();
			}
		}

		if(isset($post['delete_items'])) {
			foreach (array_unique($post['delete_items']) as $id) {				
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
}
