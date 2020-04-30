<?php

class AlumniController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$datatable = Datatable::table()
            ->addColumn('Nama','NIS', 'JK', 'TTL', 'Alamat', 'Aksi')       // these are the column headings to be shown
            ->setUrl(route('api.alumni'));   // this is the route where data will be retrieved

        return View::make('alumni.index', compact('datatable'));  
	}

	//for datatable
	public function getDatatable()
	{
        // return Datatable::collection(Siswa::all())
        return Datatable::collection(Siswa::where('status', '=', 0)->get())
        ->showColumns('nama_siswa', 'nis', 'jk', 'ttl', 'alamat')
        ->addColumn('nama_siswa',function($model)
        {
            return $model->nama_siswa;
        })
        ->addColumn('nis',function($model)
        {
            return $model->nis;
        })
        ->addColumn('jk',function($model)
        {
            return $model->jk;
        })
        ->addColumn('ttl',function($model)
        {
            return $model->ttl;
        })
        ->addColumn('alamat',function($model)
        {
            return $model->alamat;
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

}
