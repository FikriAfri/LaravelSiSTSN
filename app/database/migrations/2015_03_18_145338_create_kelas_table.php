<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nama_kls', 20);
			$table->integer('wali_kls');
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
		Schema::drop('kelas');
	}

}
