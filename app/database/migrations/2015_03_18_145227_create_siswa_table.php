<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siswas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('nis');
			$table->string('nama_siswa', 50);
			$table->string('kelas', 10);
			$table->enum('jk', ['L', 'P']);
			$table->string('ttl', 100);
			$table->string('ayah', 50);
			$table->string('ibu', 50);
			$table->string('alamat', 70);
			$table->integer('wali_kls');
			$table->boolean('status');
			// $table->foreign('wali_kls')->references('id')->on('gurus')->onDelete('cascade')->onUpdate('cascade');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('siswas');
	}

}