<?php

/**
 * Create Roles Table
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Migrations
 */

class Gotin_Create_Roles_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles', function($table)
		{
			$table->engine = 'InnoDB';
		    $table->create();
		    $table->increments('id');
		    $table->string('name',32);
		    $table->text('description')->nullable();
		    $table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}

}