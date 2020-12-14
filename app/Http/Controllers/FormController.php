<?php

namespace App\Http\Controllers;

class FormController extends ControllerBase
{
	public function indexList($dir, $model) {
		$rows = [];
		$model_name = "\App\Models"."\\".$dir."\\".$model;
		$class = new $model_name();
		$rows = $class->getRows();
		$rows['dir'] = $dir;
		$rows['model'] = $model;
		return view('main.list', $rows);
	}

	public function edit($dir, $model, $id) {
		$id         = \Crypt::decrypt($id);
		$model_name = "\App\Models"."\\".$dir."\\".$model;

		if($id > 0) {
			$row = $model_name::find($id);
		} else {
			$row = new $model_name();
		}

		if(request()->method() == 'GET' AND request()->get('is_json_request')) {
			if($id > 0) {
				$row = $model_name::find($id);
			} else {
				$row = new $model_name();
			}

			$data = $row->fetchRow($id);

			return response()->json([
				'data' => $data,
				'decrypt_id' => $id,
				'id' => \Crypt::encrypt($id),
			]);
		}

		if(request()->isMethod('POST')) {
			$postAll = request()->all();
			$post    = json_decode($postAll['row']);
			$files   = $postAll['files'];
			foreach ($files as $key => $file) {
				$file_ext = $file->getClientOriginalExtension();
				$unique_id = uniqid();
				// echo $file_ext."<br>";
				$contents = file_get_contents($file->path());
				// \Storage::disk('local')->put('example.txt', 'Contents');
				// $path = $file->store('avatars', 's3');
				\Storage::disk('local')->put($unique_id.'.'.$file_ext, $contents);
				// $newPath  = $file->store('local', 's3');
				// echo $newPath; exit;
			}

			// $file = request()->file->path();
			// $file = request()->file('file');
			// echo $file; exit;
			// $contents = file_get_contents($file);

			echo "<pre>"; print_r($post); exit;
			$id = $row->saveForm($id, $post);

			return response()->json([
				'data'       => $model_name::find($id),
				'id'         => \Crypt::encrypt($id),
				'decrypt_id' => $id,
				'status'     => 'success',
			]);
		}

		$data['id']    = $id;
		$data['dir']   = $dir;
		$data['model'] = $model;

		return view(strtolower($dir).'.'.strtolower($model).'_edit', $data);
	}

	public function simpleJson($dir, $model)
	{
		$model_name = "\App\Models"."\\".ucfirst($dir)."\\".ucfirst($model);
		if(request()->isMethod('POST')) {
			$post = request()->all();

			$class = new $model_name();
			return $class->simpleJson($post);
		}
	}
}
