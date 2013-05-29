<?php

/**
 * Create Role User Assiciative Aable
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Migrations
 */

class Gotin_Create_Role_User_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create Role_User pivot table
	    Schema::table('role_user', function($table)
	    {
	      $table->engine = 'InnoDB';
	      $table->create();
	      $table->increments('id');
	      $table->timestamps();

	      $table->integer('user_id')->unsigned();
	      $table->integer('role_id')->unsigned();

	      $table->foreign('role_id')->references('id')->on('roles')->on_delete('cascade');
	      $table->foreign('user_id')->references('id')->on('users')->on_delete('cascade');
	    });

	    $this->insert_roles();
	    $this->insert_demo_user();
	}

	/**
	 * Insert roles: Login & Admin
	 *
	 * @return void
	 */
	public function insert_roles()
	{
		DB::table('roles')->insert(
	        array(
	            'name' => 'Login',
	            'created_at' => '2013-03-08 11:02:23',
	            'updated_at' => '2013-03-08 11:02:23'
	        )
	    );
	    DB::table('roles')->insert(
	        array(
	            'name' => 'Admin',
	            'created_at' => '2013-03-08 11:02:23',
	            'updated_at' => '2013-03-08 11:02:23'
	        )
	    );
	}

	/**
	 * Insert demo user with roles
	 *
	 * @return void
	 */
	public function insert_demo_user()
	{
	    DB::table('users')->insert(
	        array(
	        	'username' => 'demo',
	            'email' => 'demo@user.go',
	            'super' => 1,
	            'password' => '$2a$08$NW.QeGHyrHIBWiR3FUCWHuaWJ5Pp5yskNSVfsoupMDUR99jzt62jC', // "demo"
	            'created_at' => '2013-03-08 11:02:23',
	            'updated_at' => '2013-03-08 11:02:23'
	        )
	    );
	    DB::table('role_user')->insert(
	        array(
	            'user_id' => 1,
	            'role_id' => 1,
	            'created_at' => '2013-03-08 11:02:23',
	            'updated_at' => '2013-03-08 11:02:23'
	        )
	    );
	    DB::table('role_user')->insert(
	        array(
	            'user_id' => 1,
	            'role_id' => 2,
	            'created_at' => '2013-03-08 11:02:23',
	            'updated_at' => '2013-03-08 11:02:23'
	        )
	    );
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_user');
	}

}