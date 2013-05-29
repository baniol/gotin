<?php
/**
 * Start Controller
 * Serves start view after logging in
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Controllers
 */


class Gotin_Dashboard_Controller extends Gotin_Controller
{
	public $restful = true;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * View after login - dasboard
	 *
	 * @return string Start view
	 */
	public function get_index() {
		return View::make( 'gotin::dashboard', array(
				'flash'=>$this->flash,
				'active'=>'dashboard',
				'b_links'=> array()
			) );
	}


}
