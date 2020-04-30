<?php

class SiswasController extends \BaseController {

	/**
	 * Display a listing of siswas
	 *
	 * @return Response
	 */
	public function index()
	{

		$datatable = Datatable::table()
            ->addColumn('Name','NIS', 'Kelas', 'JK', 'TTL', 'Ayah', 'Ibu', 'Alamat', 'Wali Kelas', 'Aksi')       // these are the column headings to be shown
            ->setUrl(route('api.siswa'));   // this is the route where data will be retrieved

        return View::make('siswas.index', compact('datatable'));  
	}

	/**
	 * Show the form for creating a new siswa
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('siswas.create');
	}

	/**
	 * Store a newly created siswa in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Siswa::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$siswa = ['nis'=>Input::get('nis'),
				'nama_siswa'=>ucwords(Input::get('nama_siswa')), 
				'kelas'=>Input::get('idkelas'),
				'jk'=>Input::get('jk'),
				'ttl'=>ucwords(Input::get('ttl')), 
				'ayah'=>ucwords(Input::get('ayah')), 
				'ibu'=>ucwords(Input::get('ibu')), 
				'alamat'=>ucwords(Input::get('alamat')), 
				'wali_kls'=>Input::get('idguru'),
				'status'=>1
				];

		$cek = Siswa::create($siswa);

		if ($cek) {
			Session::flash('message', 'Data siswa berhasil masuk');
		} else {
			Session::flash('message', 'Data GAGAL masuk');
		}

		return Redirect::route('siswa.create');
	}

	/**
	 * Display the specified siswa.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$siswa = Siswa::findOrFail($id);

		return View::make('siswas.show', compact('siswa'));
	}

	/**
	 * Show the form for editing the specified siswa.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$siswa = Siswa::find($id);

		return View::make('siswas.edit', compact('siswa'));
	}

	/**
	 * Update the specified siswa in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// Siswa::updateRules($id);
		// echo $id;
		$siswa = Siswa::findOrFail($id);

		$validator = Validator::make(Input::all(), Siswa::$update);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$siswa_data = ['nis'=>Input::get('nis'),
				'nama_siswa'=>ucwords(Input::get('nama_siswa')), 
				'kelas'=>Input::get('idkelas'),
				'jk'=>Input::get('jk'),
				'ttl'=>ucwords(Input::get('ttl')), 
				'ayah'=>ucwords(Input::get('ayah')), 
				'ibu'=>ucwords(Input::get('ibu')), 
				'alamat'=>ucwords(Input::get('alamat')), 
				'wali_kls'=>Input::get('idguru'),
				'status'=>Input::get('status')
				];

		$cek = $siswa->update($siswa_data);

		if ($cek) {
			Session::flash('message', 'Data siswa berhasil diupdate');
		} else {
			Session::flash('message', 'Data GAGAL diupdate');
		}

		return Redirect::route('siswa.index');
	}

	/**
	 * Remove the specified siswa from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$cek = Siswa::destroy($id);

		if ($cek) {
			Session::flash('message', 'Data Siswa berhasil dihapus');
		} else {
			Session::flash('message', 'Data GAGAL dihapus');
		}

		return Redirect::route('siswa.index');
	}

	//for datatable
	public function getDatatable()
	{
        // return Datatable::collection(Siswa::all())
        return Datatable::collection(Siswa::where('status', '=', 1)->get())
        ->showColumns('nama_siswa', 'nis', 'kelas', 'jk', 'ttl', 'ayah', 'ibu', 'alamat', 'wali_kls')
        ->addColumn('nama_siswa',function($model)
        {
            return $model->nama_siswa;
        })
        ->addColumn('nis',function($model)
        {
            return $model->nis;
        })
        ->addColumn('kelas',function($model)
        {
            return $model->kela->nama_kls;
        })
        ->addColumn('jk',function($model)
        {
            return $model->jk;
        })
        ->addColumn('ttl',function($model)
        {
            return $model->ttl;
        })
        ->addColumn('ayah',function($model)
        {
            return $model->ayah;
        })
        ->addColumn('ibu',function($model)
        {
            return $model->ibu;
        })
        ->addColumn('alamat',function($model)
        {
            return $model->alamat;
        })
        ->addColumn('wali_kls',function($model)
        {
            return $model->guru->nama_guru;
        })
        ->addColumn('aksi',function($model)
        {
        	$html = '<a class="btn btn-warning btn-xs" title="Edit" href='.route('siswa.edit', ['siswa'=>$model->id]).'> Edit </a> ';
        	$html .= Form::open(array('url' => 'siswa/'.$model->id, 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
            $html .= Form::submit('Delete', array('class' => 'btn btn-danger btn-xs title="Hapus"')); 
            $html .= Form::close();
            return $html;
        })
        ->searchColumns('nama_siswa', 'nis', 'kelas', 'jk', 'ayah', 'ibu', 'wali_kls')
        ->orderColumns('nama_siswa', 'nis', 'kelas', 'jk', 'ayah', 'ibu', 'wali_kls')
        ->make();
	}

	//pilih siswa
	public function pilihSiswa()
	{
		$hitungs = Kela::all(['id', 'nama_kls']);

		return View::make('siswas.pilih', compact('hitungs'));
	}

}
