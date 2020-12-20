<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class BillItems extends \App\Models\BaseModel {
	public $timestamps = false;

	protected $fillable = [
						'bill_id',
						'item_id',
						'price',
						'quentity',
						'gst_per',
						'gst_amt',
						'disc_per',
						'disc_amt',
						'total_amount'
					];

	public function getTotal() {
		$total = round($this->price * $this->quentity, 2);
		return $total;
	}

	public function bill() {
		return $this->belongsTo('\App\Models\Account\Bills');
	}

	public function item() {
		return $this->belongsTo('\App\Models\Master\Items', 'item_id');
	}

	public function validation() {
		$flag = true;

		if(intval($this->item_id) == 0) {
			$flag = false;
		}

		return $flag;
	}	
}
