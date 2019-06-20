<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateContactsTable.
 */
class CreateCandidatesTable extends Migration
{
	protected $default_table_name = 'candidates';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		tap(config('ballot.table_names.candidates', $this->default_table_name), function($table_name) {
			Schema::create($table_name, function(Blueprint $table) {
	            $table->increments('id');
	            $table->string('code')->unique();
	            $table->string('name')->unique();
	            $table->integer('position_id')->unsigned()->index();
	            $table->integer('sequence')->unsigned()->index();
	            $table->timestamps();
	            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
	            $table->unique(['position_id', 'sequence']);
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
		tap(config('ballot.table_names.candidates', $this->default_table_name), function($table_name) {
			Schema::drop($table_name);
		});
	}
}
