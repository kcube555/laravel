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

	public function edit(Request $request, $dir, $model, $id) {
		$id = \Crypt::decrypt($id);

		$model_name = "\App\Models"."\\".$dir."\\".$model;

		if($id > 0) {
			$row = $model_name::find($id);
		} else {
			$row = new $model_name();
		}

		if($request->isMethod('POST')) {
			$post = $request->all();

			$id = $row->saveForm($id, $post);

			return response()->json([
				'data' => $model_name::find($id),
				'id' => $id,
				'status' => 'success',
			]);
		}

		$data['row'] = $row;
		$data['dir'] = $dir;
		$data['model'] = $model;

		return view(strtolower($dir).'.'.strtolower($model).'_edit', $data);
	}

	public function simpleJson(Request $request, $dir, $model)
	{
		$model_name = "\App\Models"."\\".ucfirst($dir)."\\".ucfirst($model);
		if($request->isMethod('POST')) {
			$post = $request->all();

			$class = new $model_name();
			return $class->simpleJson($post);
		}



	}
}
