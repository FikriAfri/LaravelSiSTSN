<?php

class GurusController extends \BaseController {

	/**
	 * Display a listing of guru
	 *
	 * @return Response
	 */
	public function index()
	{

		return View::make('guru.index');
	}

	/**
	 * Show the form for creating a new guru
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('guru.create');
	}

	/**
	 * Store a newly created guru in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Guru::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data_guru = ['niy'=>Input::get('niy'),
						'nuptk'=>Input::get('nuptk'),
						'ttl'=>ucwords(Input::get('ttl')),
						'jk'=>Input::get('jk'),
						'pendidikan'=>strtoupper(Input::get('pendidikan')),
						'nama_guru'=>ucwords(Input::get('nama_guru'))
						];

		$cek = Guru::create($data_guru);
		
		if ($cek) {
			Session::flash('message', 'Data Guru berhasil masuk');
		} else {
			Session::flash('message', 'Data GAGAL masuk');
		}

		return Redirect::route('guru.create');
	}

	/**
	 * Display the specified guru.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$guru = Guru::findOrFail($id);

		return View::make('guru.show', compact('guru'));
	}

	/**
	 * Show the form for editing the specified guru.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$guru = Guru::find($id);

		return View::make('guru.edit', compact('guru'));
	}

	/**
	 * Update the specified guru in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$guru = Guru::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Guru::$update);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data_guru = ['niy'=>Input::get('niy'),
						'nuptk'=>Input::get('nuptk'),
						'ttl'=>ucwords(Input::get('ttl')),
						'jk'=>Input::get('jk'),
						'pendidikan'=>strtoupper(Input::get('pendidikan')),
						'nama_guru'=>ucwords(Input::get('nama_guru'))
						];

		$cek = $guru->update($data_guru);
		
		if ($cek) {
			Session::flash('message', 'Data Guru berhasil masuk');
		} else {
			Session::flash('message', 'Data GAGAL masuk');
		}

		return Redirect::route('guru.index');
	}

	/**
	 * Remove the specified guru from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$cek = Guru::destroy($id);

		if ($cek) {
			Session::flash('message', 'Data Guru berhasil dihapus');
		} else {
			Session::flash('message', 'Data GAGAL dihapus');
		}

		return Redirect::route('guru.index');
	}

	//for datatable
	public function getDatatable()
	{
		$no = 1;

        return Datatable::collection(Guru::all())
        ->showColumns('nama_guru', 'niy', 'nuptk', 'ttl', 'jk', 'pendidikan')
        ->addColumn('nama_guru',function($model)
        {
            return $model->nama_guru;
        })
        ->addColumn('niy',function($model)
        {
            return $model->niy;
        })
        ->addColumn('nuptk',function($model)
        {
            return $model->nuptk;
        })
        ->addColumn('ttl',function($model)
        {
            return $model->ttl;
        })
        ->addColumn('jk',function($model)
        {
            return $model->jk;
        })
        ->addColumn('pendidikan',function($model)
        {
            return $model->pendidikan;
        })
        ->addColumn('aksi',function($model)
        {
        	$html = '<a class="btn btn-warning btn-sm" title="Edit" href='.route('guru.edit', ['guru'=>$model->id]).'> Edit </a> ';
        	$html .= Form::open(array('url' => "guru/".$model->id, 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
            $html .= Form::submit('Delete', array('class' => 'btn btn-danger btn-sm title="Hapus"')); 
            $html .= Form::close();
            return $html;
        })
        ->searchColumns('nama_guru', 'niy')
        ->orderColumns('nama_guru', 'niy')
        ->make();
	}

}
