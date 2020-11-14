<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends ControllerBase
{
	public function list($dir, $model) {
		$rows = [];
		$model_name = "\App\Models"."\\".$dir."\\".$model;
		$class = new $model_name();
		$rows = $class->getRows();
		$rows['dir'] = $dir;
		$rows['model'] = $model;
		return view('main.list', $rows);
	}

	public function edit($dir, $model, $id) {
		$id = \Crypt::decrypt($id);

		$model_name = "\App\Models"."\\".$dir."\\".$model;

		if($id > 0) {
			$row = $model_name::find($id);
		} else {
			$row = new $model_name();
		}

		echo "<pre>"; print_r($row); exit();
	}
}
