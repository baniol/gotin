<?php

/**
 * Library for displaying flash messages
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Libraries
 */
class Flash {

	/**
	 * Sets flash session variable with html
	 *
	 * @return void
	 * @param str     $message
	 * @param str     $type    (optional) info|success|error  (optional)
	 */
	public static function set($message, $type = 'info') {

		$output = '<div class="alert alert-'.$type.'"><a class="close" data-dismiss="alert">×</a>';
		$output .= '<p>'.$message.'</p>';
		$output .= '</div>';
		Session::flash('form_errors', $output);
	}


}
