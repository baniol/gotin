<?php

/**
 * Gotin auth library
 * Serves start view after logging in
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Libraries
 */

use Laravel\Auth\Drivers\Eloquent as Eloquent;

class GotinAuth extends Eloquent {

	/**
	 * Check if a user has the input role
	 *
	 * @param str     role
	 * @param unknown $role
	 * @return boolean
	 * */
	public static function is($role) {

		if (Auth::user() == null)
			return false;

		$user_id = Auth::user()->id;

		$user_roles = User::find($user_id)->roles;
		foreach ($user_roles as $r) {
			if ($r->name == $role) {
				return true;
			}
		}
		return false;
	}


	/**
	 * checks if user is logged in && has login role
	 *
	 * @return bool
	 * */
	public function gotin_check() {
		return (!Auth::is('Login') || is_null($this->user())) ? false : true;
	}


	/**
	 * Attempt to log a user into the application.
	 *
	 * @param array   $arguments
	 * @return void
	 */
	public function attempt_gotin($arguments = array()) {

		/**
		 *
		 */
		$user = $this->model()->where(function($query) use($arguments) {

				// check username || email

				switch (Config::get('gotin::gotin.user_check')) {
				case "both":
					$query->where('username', '=', $arguments['username'])->or_where('email', '=', $arguments['username']);
					break;
				case "username":
					$query->where('username', '=', $arguments['username']);
					break;
				case "email":
					$query->where('email', '=', $arguments['username']);
					break;
				}

				foreach (array_except($arguments, array('username', 'password', 'remember')) as $column => $val) {
					$query->where($column, '=', $val);
				}
			})->first();

		// If the credentials match what is in the database we will just
		// log the user into the application and remember them if asked.
		$password = $arguments['password'];

		$password_field = Config::get('auth.password', 'password');

		if ( ! is_null($user) and Hash::check($password, $user->{$password_field})) {
			return $this->login($user->get_key(), array_get($arguments, 'remember'));
		}

		return false;
	}


}
