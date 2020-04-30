<?php

class AdminsController extends \BaseController {

	/**
	 * Display a listing of admins
	 *
	 * @return Response
	 */
	public function index()
	{
		// $admins = Admin::all();
		$datatable = Datatable::table()
            ->addColumn('Nama','JK', 'Username', 'Password', 'Email', 'Aksi')       // these are the column headings to be shown
            ->setUrl(route('api.admin'));   // this is the route where data will be retrieved

		return View::make('admins.index', compact('datatable'));
	}

	/**
	 * Show the form for creating a new admin
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admins.create');
	}

	/**
	 * Store a newly created admin in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Admin::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$admin_data = ['nama'=>ucwords(Input::get('nama')),
					'jk'=>Input::get('jk'),
					'username'=>Input::get('username'),
					'password'=>Hash::make(Input::get('password')),
					'email'=>Input::get('email')
					];

		$cek = Admin::create($admin_data);

		if ($cek) {
			Session::flash('message', 'Data Admin berhasil masuk');
		} else {
			Session::flash('message', 'Data GAGAL masuk');
		}

		return Redirect::route('admin.create');
	}

	/**
	 * Display the specified admin.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$admin = Admin::findOrFail($id);

		return View::make('admins.show', compact('admin'));
	}

	/**
	 * Show the form for editing the specified admin.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$admin = Admin::find($id);

		return View::make('admins.edit', compact('admin'));
	}

	/**
	 * Update the specified admin in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$admin = Admin::findOrFail($id);

		$validator = Validator::make(Input::all(), Admin::$update);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$admin_data = ['nama'=>ucwords(Input::get('nama')),
					'jk'=>Input::get('jk'),
					'username'=>Input::get('username'),
					'password'=>Hash::make(Input::get('password')),
					'email'=>Input::get('email')
					];

		$cek = $admin->update($admin_data);

		if ($cek) {
			Session::flash('message', 'Data Admin berhasil diupdate');
		} else {
			Session::flash('message', 'Data GAGAL diupdate');
		}

		return Redirect::route('admin.index');
	}

	/**
	 * Remove the specified admin from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Admin::destroy($id);

		return Redirect::route('admin.index');
	}

	//for datatable
	public function getDatatable()
	{

        return Datatable::collection(Admin::all())
        ->showColumns( 'nama', 'jk', 'username', 'password', 'email')
        ->addColumn('nama',function($model)
        {
            return $model->nama;
        })
        ->addColumn('jk',function($model)
        {
            return $model->jk;
        })
        ->addColumn('username',function($model)
        {
            return $model->username;
        })
        ->addColumn('password',function($model)
        {
            return $model->password;
        })
        ->addColumn('aksi',function($model)
        {
        	$html = '<a class="btn btn-warning btn-xs" title="Edit" href='.route('admin.edit', ['admin'=>$model->id]).'> Edit </a> ';
        	$html .= Form::open(array('url' => "admin/".$model->id, 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
            $html .= Form::submit('Delete', array('class' => 'btn btn-danger btn-xs title="Hapus"')); 
            $html .= Form::close();
            return $html;
        })
        ->searchColumns('nama', 'username')
        ->orderColumns('nama', 'username')
        ->make();
	}

}
