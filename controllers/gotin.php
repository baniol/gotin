<?php
/**
 * Setup for Gogin bundle
 * Base controller
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @subpackage  Controllers
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin
 */


class Gotin_Controller extends Controller {

	/**
	 * Gotin base controller
	 * Sets the auth driver
	 * Array of not secured urls
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		Config::set('auth.driver', 'gotinauth');
		$this->filter('before', 'auth')->except((Config::get('gotin::gotin.not_secured_urls')));
	}


}
