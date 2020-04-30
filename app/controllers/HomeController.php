<?php

class HomeController extends BaseController {

	public $param;
	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function dash()
	{
		$guru = Guru::count();
		$admin = Admin::count();
		$kelas = Kela::count();
		$siswa = Siswa::where('status', '=', 1)->count();
		
		//progres bar
		$users = DB::table('siswas')
					->leftJoin('kelas', 'siswas.kelas', '=', 'kelas.id')
                    ->select('kelas.nama_kls as abc',DB::raw('count(*) as jumlah, kelas'))
                    ->where('status', '=', 1)
                    ->groupBy('kelas')
                    ->get();
		
		return View::make('pages.dashboard', compact('guru', 'kelas', 'siswa', 'users', 'admin'));
	}

}
