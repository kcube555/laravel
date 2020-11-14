<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ControllerBase extends Controller
{
	public function __construct()
	{
		// if(true) {
		// 	Redirect::to('login')->send();
		// }
		$menu = \App\Models\Main\Menus::all();
		\View::share('menu', $menu);
	}

	public function index() {
		echo "welcome to index controller";
		exit;
	}
}
