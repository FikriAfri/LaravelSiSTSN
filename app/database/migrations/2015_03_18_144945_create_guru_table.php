<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuruTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gurus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('niy');
			$table->integer('nuptk');
			$table->string('ttl', 100);
			$table->enum('jk', ['L', 'P']);
			$table->string('pendidikan', 5);
			$table->string('nama_guru', 50);
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
		Schema::drop('gurus');
	}

}