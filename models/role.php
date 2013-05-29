<?php

/**
 * Role Model
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Models
 */

class Role extends Eloquent
{
	public static $timestamps = true;

	
	public function users()
	{
		return $this->has_many_and_belongs_to('User','role_user');
	}
}