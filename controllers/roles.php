<?php
/**
 * Managing roles
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Controllers
 */


class Gotin_Roles_Controller extends Gotin_Controller
{
	public $restful = true;

	/**
	 * Flash message
	 *
	 * @var string
	 * */
	public $flash;

	/**
	 * data passed to view
	 *
	 * @var object
	 * */
	public $data;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->flash = Session::get( 'form_errors' );
		$this->data = new stdClass();
		$this->data->name = "";
		$this->data->description = "";
	}


	/**
	 * Displays list of roles
	 *
	 * @return str
	 */
	public function get_index() {
		$roles = Role::order_by( 'id', 'asc' )->get();
		return View::make( 'gotin::roles', array(
				'roles'=>$roles,
				'flash'=>$this->flash,
				'active'=>'roles',
				'b_links'=> array("Roles")
			) );
	}


	/**
	 * Displays template for a new role creation
	 *
	 * @return string
	 */
	public function get_new() {
		return View::make( 'gotin::addrole', array(
				'flash'=>$this->flash,
				'role'=>$this->data,
				'role_id'=>false,
				'active'=>'roles',
				'b_links'=> array(HTML::link_to_action('gotin::roles','Roles'),'Add role')
			) );
	}


	/**
	 * Serves post request for a new role creation
	 *
	 * @return Redirect
	 */
	public function post_new() {
		$rules = array(
			'name' => 'unique:roles,name|required'
		);
		$validation = Validator::make( Input::all(), $rules );
		if ( $validation->fails() ) {
			// Flash::set( 'There are errors in the submitted form!', 'error' );
			return Redirect::to_action( 'gotin::roles@new' )->with_errors( $validation )->with_input();
		}else {
			$role = new Role;
			$role->name = Input::get( 'name' );
			$role->description = Input::get( 'description' );
			$role->save();
			Flash::set( __('gotin::gotin.role_added'), 'success' );
			return Redirect::to_action( 'gotin::roles' );
		}
	}


	/**
	 * Edit role template
	 *
	 * @param int     $id role id
	 * @return mixed str|Redirect
	 */
	public function get_edit( $id ) {
		$role = Role::find( $id );
		if ( !$role )
			return Redirect::to_action( 'gotin::roles' );
		return View::make( 'gotin::addrole', array(
				'flash'=>$this->flash,
				'role'=>$role,
				'role_id'=>$id,
				// @todo - pass actvie globally - the same for users
				'active'=>'roles',
				'b_links'=> array(HTML::link_to_action('gotin::roles','Roles'),'Edit role <b>'.$role->name.'</b>')
			) );
	}


	/**
	 * Serves post request for a role edition
	 *
	 * @param int $id role id
	 * @return Redirect
	 */
	public function post_edit( $id ) {
		if ( !$id ) {
			Flash::set( 'Error!', 'error' );
			return Redirect::to_action( 'gotin::roles' );
		}
		$rules = array(
			'name' => 'unique:roles,name,'.$id.'|required',
		);

		$validation = Validator::make( Input::all(), $rules );

		if ( $validation->fails() ) {

			// Flash::set( 'There are errors in the submitted form!', 'error' );
			return Redirect::to_action( 'gotin::roles@edit', array( $id ) )->with_errors( $validation )->with_input();

		}else {
			$role = Role::find( $id );
			$role->name = Input::get( 'name' );
			$role->description = Input::get( 'description' );
			$role->save();

			Flash::set( __('gotin::gotin.data_changed'), 'success' );
			return Redirect::to_action( 'gotin::roles@edit', array( $id ) );
		}
	}


	/**
	 * Delete role get request
	 *
	 * @param int $id role id
	 * @return Redirect
	 */
	public function get_delete( $id ) {

		// roles with id 1 & 2 are essential (logi & admi), so block their removal
		if ( $id == 1 || $id == 2 ) {
			Flash::set( 'You cannot delete this role', 'error' );
			return Redirect::to_action( 'gotin::roles' );
		}

		$role = Role::find( $id );
		$role->delete();
		Flash::set( __('gotin::gotin.role_removed'), 'success' );
		return Redirect::to_action( 'gotin::roles' );
	}


}
