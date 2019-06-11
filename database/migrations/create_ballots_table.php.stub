<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateContactsTable.
 */
class CreateBallotsTable extends Migration
{
	protected $default_table_name = 'ballots';

	protected $default_pivot_name = 'ballot_candidate';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		tap(config('ballot.table_names.ballots', $this->default_table_name), function($table_name) {
			Schema::create($table_name, function(Blueprint $table) {
	            $table->increments('id');
	            $table->string('code')->unique()->nullable();
	            $table->string('image')->nullable();
	            $table->timestamps();
			});			
		});

        tap(config('ballot.table_names.ballot_candidate', $this->default_pivot_name), function($table_name) {
            Schema::create($table_name, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('ballot_id')->unsigned()->index();
                $table->integer('position_id')->unsigned()->nullable()->index();
                $table->integer('candidate_id')->unsigned()->nullable()->index();
                $table->enum('votes', [1])->nullable();
                $table->timestamps();
                $table->foreign('ballot_id')->references('id')->on('ballots')->onDelete('cascade');
                $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
                $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
                // $table->unique(['ballot_id', 'position_id']);
                $table->unique(['ballot_id', 'candidate_id']);
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
		tap(config('ballot.table_names.ballot_candidate', $this->default_pivot_name), function($table_name) {
			Schema::drop($table_name);
		});
		tap(config('ballot.table_names.ballots', $this->default_table_name), function($table_name) {
			Schema::drop($table_name);
		});
	}
}
