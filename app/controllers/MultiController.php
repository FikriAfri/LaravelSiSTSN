<?php
use Khill\Lavacharts\Lavacharts;

class MultiController extends \BaseController {

	//login
	public function login()
	{
		return View::make('pages.login');
	}

	public function doLogin()
	{
		// validate the info, create rules for the inputs
		$rules = array(
	    	'username'    => 'required|min:4', // make sure the email is an actual email
	    	'password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
	    );

		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);

		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			//Session::flash('message', 'Error terjadi');
			return Redirect::to('login')
        	->withErrors($validator) // send back all errors to the login form
        	->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
   		} else {

	    // create our user data for the authentication
	    	$userdata = array(
	    		'username'     => Input::get('username'),
	    		'password'  => Input::get('password'),
	    		);
	    

	    // attempt to do the login
	    	if (Auth::attempt($userdata)) {

	        // validation successful!
	        // redirect them to the secure section or whatever
	        // return Redirect::to('secure');
	        // for now we'll just echo success (even though echoing in a controller is bad)
	    		// echo 'SUCCESS!';
	    		// echo "<a href='logout'>Logout</a>";
	    	Session::flash('message', 'Wellcome '. Auth::user()->nama);
	    	
	    	return Redirect::intended('dashboard');

	    	} else {        

	        // validation not successful, send back to form 
	    		Session::flash('message', 'Username atau Password salah');
	    		return Redirect::to('login');

	    	}

    	}
	}

	//logout
	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		Session::flash('message', 'Goodbye.!');
		return Redirect::to('dashboard'); // redirect the user to the login screen
	}

	//cetak pdf
	public function cetakPdf()
	{
		if (isset($_GET['data'])) {
			$param = $_GET['data'];
		}
		
		switch ($param) {
			case 'siswa':
				$kls = $_GET['kelas'];
					//generate pdf via view
					$data_kls = Siswa::where('kelas', '=', $kls)->get();
					$pdf = PDF::loadView('pdf.siswa', compact('data_kls'));
					return $pdf->stream('data_kelas.pdf');

			case 'guru':
				$data_guru = Guru::all();
				$pdf = PDF::loadView('pdf.guru', compact('data_guru'));
					return $pdf->stream('data_guru.pdf');
				break;

			case 'kelas':
				$data_kelas = Kela::all();
				$pdf = PDF::loadView('pdf.kelas', compact('data_kelas'));
					return $pdf->stream('data_kelas.pdf');
				break;

			case 'alumni':
				$data_alumni = Siswa::where('status', '=', '0')->get();
				$pdf = PDF::loadView('pdf.alumni', compact('data_alumni'));
					return $pdf->stream('data_alumni.pdf');
				break;
			default:
				echo '<script>alert("What are you doing dude ??")</script>';
				echo '<script>history.back()</script>';
				break;
		}
		
	}

	//function for chart
	public function chart()
	{
		$a = DB::table('siswas')->groupBy('kelas')->lists('nama_siswa');

		$users = DB::table('siswas')
					
                    ->select(DB::raw('YEAR(created_at) as tahun'), DB::raw('count(*) as jumlah'))
                    // ->where('status', '=', 1)
                    ->groupBy('tahun')
                    ->get();
        //$hasilJson = Response::json($users);

		return View::make('chart.chart', compact('a', 'users'));
	}

	//wali kelas
	public function getWali()
	{
		$get = Input::get('term');

		$results = array();
		$queries = DB::table('gurus')
		->where('nama_guru', 'LIKE', '%'.$get.'%')
		->take(5)->get();

		foreach ($queries as $query)
		{
			$results[] = [ 'id' => $query->id, 'value' => $query->nama_guru ];
		}

		return Response::json($results); 

	}

	//nama kelas
	public function getKelas()
	{
		$get = Input::get('term');

		$results = array();
		$queries = DB::table('kelas')
		->where('nama_kls', 'LIKE', '%'.$get.'%')
		->take(3)->get();

		foreach ($queries as $query)
		{
			$results[] = [ 'id' => $query->id, 'value' => $query->nama_kls ];
		}

		return Response::json($results); 

	}

	//abaut
	public function about()
	{
		return View::make('pages.about');
	}

}
