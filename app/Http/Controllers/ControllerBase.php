<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use PDF;

class ControllerBase extends Controller
{
	public function __construct()
	{
		$common = (Object)[];
		
		$menu = \App\Models\Main\Menus::all();
		$common->menu = $menu;

		$add_button = '<button class="btn btn-sm btn-danger" @click="removeItem(index)">
				<i style="font-size: 10px;" class="fa fa-minus"></i></button>
				<button class="btn btn-sm btn-info" @click="addItem(item)">
				<i style="font-size: 10px;" class="fa fa-plus"></i></button>';
		$common->add_button = $add_button;

		$edit_button = '<button class="btn btn-sm btn-danger" @click="deleteItem(index, item.id)">
				<i style="font-size: 10px;" class="fa fa-minus"></i></button>
				<button class="btn btn-sm btn-info" @click="addItem(item)">
				<i style="font-size: 10px;" class="fa fa-plus"></i></button>';
		$common->edit_button = $edit_button;

		\View::share('common', $common);
	}

	public function index() {
		echo "welcome to index controller";
		exit;
	}
}
