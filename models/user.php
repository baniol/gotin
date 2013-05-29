<?php

/**
 * User Model
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Models
 */

class User extends Eloquent
{
	public static $timestamps = true;

	/**
	 *
	 * @return mixed
	 */
	public function roles() {
		return $this->has_many_and_belongs_to('Role');
	}

	/**
	 * Returns an array with user`s roles
	 * array[role_name] => bool
	 *
	 * @param object  $user
	 * @return array
	 */
	public static function get_roles($user=null) {
		$array = array();
		if ($user != null) {
			foreach ($user->roles as $u_role) {
				$array[] = $u_role->id;
			}
		}

		$out = array();
		foreach (Role::all() as $role) {
			$checked = in_array($role->id, $array) ? 1 : 0;
			$out[] = array('name'=>$role->name, 'id'=>$role->id, 'checked'=>$checked);
		}
		return $out;
	}


}
