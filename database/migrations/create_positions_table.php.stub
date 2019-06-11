<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateContactsTable.
 */
class CreatePositionsTable extends Migration
{
	protected $default_table_name = 'positions';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		tap(config('ballot.table_names.positions', $this->default_table_name), function($table_name) {
			Schema::create($table_name, function(Blueprint $table) {
	            $table->increments('id');
	            $table->string('name')->unique();
	            $table->tinyInteger('seats')->default(1);
	            $table->tinyInteger('level')->unsigned()->index();
	            $table->timestamps();
			});			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		tap(config('ballot.table_names.positions', $this->default_table_name), function($table_name) {
			Schema::drop($table_name);
		});
	}
}
