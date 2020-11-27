<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends ControllerBase
{
	public function index() {
		return view('registration');
	}

	public function login(Request $request) {
		if ($request->isMethod('GET')) {
			return view('user.login');
		}

		if ($request->isMethod('POST')) {
			$request->validate([
				"email"		=>    "required|email",
				"password"  =>    "required|min:6"
			]);

			$credentials = $request->only('email', 'password');
			// $user = User::where('id', 2)->first();
			// $user->password = Hash::make($user->password);
			// $user->save();
			// dd($user->password);

			// dd(Auth::attempt($credentials));

			if (Auth::attempt($credentials)) {
				return redirect()->intended('dashboard');
			} else {
				return back()->with('error', 'Whoops! invalid username or password.')->withInput();
			}
		}
	}

	public function dashboard() {
		if(Auth::check()) {
			$data = [];
			$data['title'] = 'Dashboard';
			return view('user.dashboard', $data);
		}

		// return redirect::to("login")->withError('Oopps! You do not have access');
	}

	public function logout(Request $request) {
		$request->session()->flush();
		Auth::logout();
		return Redirect('login');
	}
}
