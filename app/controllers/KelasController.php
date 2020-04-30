<?php

class KelasController extends \BaseController {

	/**
	 * Display a listing of kelas
	 *
	 * @return Response
	 */
	public function index()
	{

		return View::make('kelas.index');
	}

	/**
	 * Show the form for creating a new kela
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('kelas.create');
	}

	/**
	 * Store a newly created kela in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Kela::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data_kls = ['nama_kls'=>strtoupper(Input::get('nama_kls')),
						'wali_kls'=>Input::get('idguru')
					];

		$cek = Kela::create($data_kls);

		if ($cek) {
			Session::flash('message', 'Data Kelas berhasil masuk');
		} else {
			Session::flash('message', 'Data GAGAL masuk');
		}

		return Redirect::route('kelas.create');
	}

	/**
	 * Display the specified kela.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$kela = Kela::findOrFail($id);

		return View::make('kelas.show', compact('kela'));
	}

	/**
	 * Show the form for editing the specified kela.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$kela = Kela::find($id);

		return View::make('kelas.edit', compact('kela'));
	}

	/**
	 * Update the specified kela in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$kela = Kela::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Kela::$update);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data_kls = ['nama_kls'=>strtoupper(Input::get('nama_kls')),
						'wali_kls'=>Input::get('idguru')
					];

		$cek = $kela->update($data_kls);

		if ($cek) {
			Session::flash('message', 'Data Kelas berhasil diupdate');
		} else {
			Session::flash('message', 'Data GAGAL diupdate');
		}

		return Redirect::route('kelas.index');
	}

	/**
	 * Remove the specified kela from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$cek = Kela::destroy($id);

		if ($cek) {
			Session::flash('message', 'Data Kelas berhasil dihapus');
		} else {
			Session::flash('message', 'Data GAGAL dihapus');
		}

		return Redirect::route('kelas.index');
	}

	//for datatable
	public function getDatatable()
	{

        return Datatable::collection(Kela::all())
        ->showColumns( 'nama_kls', 'wali_kls')
        ->addColumn('nama_kls',function($model)
        {
            return $model->nama_kls;
        })
        ->addColumn('wali_kls',function($model)
        {
            return $model->guru->nama_guru;
        })
        ->addColumn('aksi',function($model)
        {
        	$html = '<a class="btn btn-warning btn-sm" title="Edit" href='.route('kelas.edit', ['kelas'=>$model->id]).'> Edit </a> ';
        	$html .= Form::open(array('url' => "kelas/".$model->id, 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
            $html .= Form::submit('Delete', array('class' => 'btn btn-danger btn-sm title="Hapus"')); 
            $html .= Form::close();
            return $html;
        })
        ->searchColumns('nama_kls', 'wali_kls')
        ->orderColumns('nama_kls', 'wali_kls')
        ->make();
	}

}
