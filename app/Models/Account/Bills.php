<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Bills extends \App\Models\BaseModel {

	protected $primaryKey = 'id';

	protected $fillable = [
						'type',
						'sub_type',
						'number',
						'date', 
						'party_id',
						'invoice_value',
						'terms',
						'remarks'
					];

	public static function boot() {
		parent::boot();

		self::creating(function($model){
			$model->date     = date('Y-m-d', strtotime('+0 day', strtotime($model->date)));
			$model->validity = date('Y-m-d', strtotime('+0 day', strtotime($model->validity)));
		});

		self::created(function($model){
			$model->number = 'INV/20-21/'.str_pad($model->id, 4, '0', STR_PAD_LEFT);
			$model->save();
		});

		self::updating(function($model){
			$model->date     = date('Y-m-d', strtotime('+0 day', strtotime($model->date)));
			$model->validity = date('Y-m-d', strtotime('+0 day', strtotime($model->validity)));
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

	public function isIgst() {
		return false;
	}

	public function getRows() {
		$data['rows'] = \DB::table('bills')
							->join('parties', 'parties.id', '=', 'bills.party_id')
							->select('bills.*', 'parties.name as party_name')
							->get();

		$data['headers'] = ['Number', 'Date', 'Customer Name', 'Total Amount'];
		$data['data'] = ['number', 'date', 'party_name', 'invoice_value'];

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
				$file_ext = $file->getClientOriginalExtension();
				$unique_id = uniqid();
				$contents = file_get_contents($file->path());
				\Storage::disk('local')->put($class_name.'/'.$this->id.'/'.$unique_id.'.'.$file_ext, $contents);
				$this->$field = $unique_id.'.'.$file_ext;
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

	public function export() {
		$get = request()->all();

		if('pdf' == $get['format']) {
			$email = (isset($get['email']) ? $get['email'] : 0);	
			$this->pdf($email);
		}
	}

	public function pdf($email) {
		$file_name = str_replace('/', '-', $this->number).'_'.uniqid().'.pdf';
		$is_igst   = $this->isIgst();

		$pdf = new \TCPDF();

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Kanji Kangad');
		$pdf->SetTitle($file_name);
		$pdf->SetSubject('Bill PDF Design');
		$pdf->SetKeywords('TCPDF, PDF');

		// set margins
		$pdf->SetMargins(10, 30, 10);

		$pdf->SetFont('times', '', 10);

		// Set Background color
		$pdf->SetFillColor(255, 255, 255);

		$pdf->AddPage();

		$pdf->SetFont('times', '', 10);
		$pdf->Cell(95, 5, 'Invoice No.', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(95, 5, 'Date', 'LTR', 1, '', 0, '', 1);

		// $pdf->SetTextColor(0,0,0);
		$pdf->SetFont('times', 'B', 11);
		$pdf->Cell(95, 6, $this->number, 'L', 0, '', 0, '', 1);
		$pdf->Cell(95, 6, $this->date, 'LR', 1, '', 0, '', 1);

		$pdf->SetFont('times', '', 10);
		$pdf->Cell(95, 5, 'To : ', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(95, 5, '', 'LTR', 1, '', 0, '', 1);

		$pdf->SetFont('times', 'B', 11);
		$pdf->Cell(95, 6, $this->party->name, 'L', 0, '', 0, '', 1);
		$pdf->Cell(95, 6, '', 'LR', 0, '', 0, '', 1);

		$pdf->MultiCell(95, 20, $this->party->address, 'L', 'L', 1, 0, 10, 52, true, 0, false, true, 20, 'T', true);
		$pdf->MultiCell(95, 20, '', 'LTR', 'L', 1, 1, 105, 52, true, 0, false, true, 20, 'T', true);

		$pdf->SetFont('times', '', 10);
		$pdf->Cell(7, 5, 'No.', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(78, 5, 'Particular', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(20, 5, 'Rate', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(15, 5, 'Quentity', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(20, 5, 'Amount', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(15, 5, 'GST %', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(15, 5, 'GST Amt', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(20, 5, 'Total Amt', 'LTR', 1, '', 0, '', 1);

		$pdf->SetFont('times', '', 11);
		$total = [
			'amount'     => 0.00,
		];
		foreach ($this->billItems as $key => $r) {
			$amount = $r->getTotal();
			$total['amount'] += $amount;
			if($pdf->getY() > 219 + 10) {
				$pdf->Cell(190, 6, 'Page', 'T', 0, 'R', 0, '', 1);
				$pdf->AddPage();

				$pdf->SetFont('times', '', 10);
				$pdf->Cell(95, 5, 'Invoice No.', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(95, 5, 'Date', 'LTR', 1, '', 0, '', 1);

				// $pdf->SetTextColor(0,0,0);
				$pdf->SetFont('times', 'B', 11);
				$pdf->Cell(95, 6, $this->number, 'L', 0, '', 0, '', 1);
				$pdf->Cell(95, 6, $this->date, 'LR', 1, '', 0, '', 1);

				$pdf->SetFont('times', '', 10);
				$pdf->Cell(95, 5, 'To : ', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(95, 5, '', 'LTR', 1, '', 0, '', 1);

				$pdf->SetFont('times', 'B', 11);
				$pdf->Cell(95, 6, $this->party->name, 'L', 0, '', 0, '', 1);
				$pdf->Cell(95, 6, '', 'LR', 0, '', 0, '', 1);

				$pdf->MultiCell(95, 20, $this->party->address, 'L', 'L', 1, 0, 10, 52, true, 0, false, true, 20, 'T', true);
				$pdf->MultiCell(95, 20, '', 'LTR', 'L', 1, 1, 105, 52, true, 0, false, true, 20, 'T', true);

				$pdf->SetFont('times', '', 10);
				$pdf->Cell(7, 5, 'No.', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(78, 5, 'Particular', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(20, 5, 'Rate', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(15, 5, 'Quentity', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(20, 5, 'Amount', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(15, 5, 'GST %', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(15, 5, 'GST Amt', 'LT', 0, '', 0, '', 1);
				$pdf->Cell(20, 5, 'Total Amt', 'LTR', 1, '', 0, '', 1);
			}

			$pdf->SetFont('times', 'B', 10);
			$pdf->Cell(7, 6, ++$key, 'LT', 0, '', 0, '', 1);
			$pdf->Cell(78, 6, $r->item->name, 'LT', 0, '', 0, '', 1);
			$pdf->Cell(20, 6, $r->price, 'LT', 0, 'R', 0, '', 1);
			$pdf->Cell(15, 6, $r->quentity, 'LT', 0, 'R', 0, '', 1);
			$pdf->Cell(20, 6, $amount, 'LT', 0, 'R', 0, '', 1);
			$pdf->Cell(15, 6, $r->gst_per, 'LT', 0, 'R', 0, '', 1);
			$pdf->Cell(15, 6, $r->gst_amt, 'LT', 0, 'R', 0, '', 1);
			$pdf->Cell(20, 6, $r->total_amount, 'LTR', 1, 'R', 0, '', 1);
		}

		$blank_space = 230 - $pdf->getY();
		$pdf->MultiCell(190, $blank_space, '', 'LTBR', 'L', 1, 1, 10, $pdf->getY(), true, 0, false, true, $blank_space, 'T', true);

		$pdf->Cell(120, 5, 'Total', 'LT', 0, 'R', 0, '', 1);
		$pdf->Cell(20, 5, $total['amount'], 'LT', 0, 'R', 0, '', 1);
		$pdf->Cell(30, 5, '', 'LT', 0, '', 0, '', 1);
		$pdf->Cell(20, 5, $this->invoice_value, 'LTR', 1, 'R', 0, '', 1);

		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(95, 5, 'Remarks : ', 'LT', 0, 'L', 0, '', 1);
		$pdf->Cell(95, 5, 'Terms : ', 'LTR', 1, 'L', 0, '', 1);

		$pdf->SetFont('times', '', 10);
		$pdf->MultiCell(95, 36, $this->terms, 'LB', 'L', 1, 0, 10, 240, true, 0, false, true, 40, 'T', true);
		$pdf->MultiCell(95, 36, $this->remarks, 'LRB', 'L', 1, 1, 105, 240, true, 0, false, true, 40, 'T', true);

		if($email) {
			$pdf->Output(base_path("storage/tmp/".$file_name), 'F');

			$data = ['name' => $this->party->name];
		    \Mail::send('mail', $data, function($message) use ($file_name) {
		        $message->to($this->party->email_id, 'Tutorials Point')->subject
		            ('Invoice');
		        $message->attach(base_path('storage/tmp/'.$file_name));
		        $message->from('kanjikangad63@gmail.com','Virat Gandhi');
		    });

	        echo "Email Sent with attachment. Check your inbox.";
	        unlink(base_path("storage/tmp/".$file_name));
	        exit;

		} else {
			$pdf->Output($file_name, 'I');
		}
	}
}
