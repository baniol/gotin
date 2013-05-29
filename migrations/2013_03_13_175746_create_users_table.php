<?php

/**
 * Create Users Table
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Migrations
 */

class Gotin_Create_Users_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->engine = 'InnoDB';
		    $table->create();
		    $table->increments('id');
		    $table->string('username',255);
		    $table->string('email',255);
		    $table->string('password',255);
		    $table->boolean('super')->default(0);
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
		Schema::drop('users');
	}

}