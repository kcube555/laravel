<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Bills extends \App\Models\BaseModel {

	// protected $table = 'parties';

	protected $fillable = array('type', 'sub_type','number', 'date', 'party_id', 'total_amount', 'terms', 'remarks');


    public function party() {
         return $this->belongsTo('\App\Models\Master\Parties');
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

	public function saveForm($id, $post) {
		$post = $post['post_data'];
		$this->type = $post['type'];
		$this->number = $post['number'];
		$this->user_id = 1;
		$this->party_id = $post['customer_id'];
		// $this->total_amount = $post['customer_id']
		$this->save();
		return $this->id;
	}
}
