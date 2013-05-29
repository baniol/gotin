<?php
/**
 * Users management
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Controllers
 */


class Gotin_Users_Controller extends Gotin_Controller
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
	 * Construct
	 */
	public function __construct() {
		parent::__construct();
		$this->flash = Session::get( 'form_errors' );

		// empty default user object to pass to the adduser view
		$this->data = new stdClass();
		$this->data->email = "";
		$this->data->username = "";
	}


	/**
	 * Shows users list
	 *
	 * @return str
	 */
	public function get_index() {
		$users = User::order_by( 'id', 'asc' )->get();
		return View::make( 'gotin::users', array(
				'users'=>$users,
				'flash'=>$this->flash,
				'active'=>'users',
				'active'=>'users',
				'b_links'=> array('Users')
			) );
	}


	/**
	 * Add user template
	 *
	 * @return str
	 */
	public function get_new() {

		$roles = User::get_roles();

		return View::make( 'gotin::adduser', array(
				'flash'=>$this->flash,
				'roles'=>$roles,
				'user_id'=>false,
				'active'=>'users',
				// breadcrumb link
				'b_links'=> array(HTML::link_to_action('gotin::users', 'Users'), 'Add user'),
				// values to repopulate the form
				'values' => array(
						'email' => !Input::old('email') ? $this->data->email : Input::old('email'),
						'username' => !Input::old('username') ? $this->data->username : Input::old('username')
					)
			) );
	}


	/**
	 * Serves new user post request
	 *
	 * @todo - code duplication, see post_edit
	 * @return Redirect
	 */
	public function post_new() {

		$rules = array(
			'username' => 'unique:users,username|required',
			'email' => 'unique:users,email|required|email',
			'password' => 'required|confirmed'
		);

		$validation = Validator::make( Input::all(), $rules );

		if ( $validation->fails() ) {

			// Flash::set( 'There are errors in the submitted form!', 'error' );
			return Redirect::to_action( 'gotin::users@new' )->with_errors( $validation )->with_input();

		}else {
			$user = new User;
			$user->username = Input::get( 'username' );
			$user->email = Input::get( 'email' );
			$user->password = Hash::make( Input::get( 'password' ) );
			$user->save();

			if ( $user ) {
				$insUser = User::find( $user->id );
				// save user`s roles
				$insUser->roles()->delete();
				if ( Input::get( 'roles' ) ) {
					foreach ( Input::get( 'roles' ) as $role ) {
						$insUser->roles()->attach( $role );
					}
				}
			}

			Flash::set( __('gotin::gotin.user_added'), 'success' );
			return Redirect::to_action( 'gotin::users' );
		}
	}


	/**
	 * Shows edit user template
	 *
	 * @param int     $id
	 * @return mixed View|Redirect
	 */
	public function get_edit( $id ) {
		$user = User::find( $id );
		if ( !$user )
			return Redirect::to_action( 'gotin::users' );
		$roles = User::get_roles( $user );
		return View::make( 'gotin::adduser', array(
				'flash'=>$this->flash,
				'roles'=>$roles,
				'user_id'=>$id,
				'active'=>'users',
				'b_links'=> array(HTML::link_to_action('gotin::users', 'Users'), 'Edit user <b>'.$user->username.'</b>'),
				// values to repopulate the form
				'values' => array(
						'email' => !Input::old('email') ? $user->email : Input::old('email'),
						'username' => !Input::old('username') ? $user->username : Input::old('username')
					)
			) );
	}


	/**
	 * Serves edit user post request
	 *
	 * @param int     $id
	 * @return Redirect
	 */
	public function post_edit( $id ) {
		// @todo check methode from get_delete (exists) - check others
		if ( !$id ) {
			Flash::set( 'Error!', 'error' );
			return Redirect::to_action( 'gotin::users' );
		}
		$rules = array(
			'username' => 'unique:users,username,'.$id.'|required',
			'email' => 'unique:users,email,'.$id.'|required|email',
			'password' => 'confirmed'
		);

		$validation = Validator::make( Input::all(), $rules );

		if ( $validation->fails() ) {

			// Flash::set( 'There are errors in the submitted form!', 'error' );
			return Redirect::to_action( 'gotin::users@edit', array( $id ) )->with_errors( $validation )->with_input();

		}else {
			$user = User::find( $id );
			$user->username = Input::get( 'username' );
			$user->email = Input::get( 'email' );

			if($user->super != 1)
				$user->roles()->delete();

			if ( Input::get( 'roles' ) && $user->super != 1 ) {
				// save user`s roles
				foreach ( Input::get( 'roles' ) as $role ) {
					$user->roles()->attach( $role );
				}
			}
			$user->save();

			Flash::set( __('gotin::gotin.data_changed'), 'success' );
			return Redirect::to_action( 'gotin::users@edit', array( $id ) );
		}
	}


	/**
	 * Serves delete user get request
	 *
	 * @param ing     $id
	 * @param bool $account if true delete account of the current user
	 * @return Redirect
	 */
	public function get_delete( $id, $account = false ) {
		$user = User::find( $id );
		if ( !$user ) {
			Flash::set( 'Error!', 'error' );
			return Redirect::to_action( 'gotin::users' );
		}
		if ( $user->super == 1 ) {
			Flash::set( __('gotin::gotin.cannot_remove_super'), 'error' );
			return Redirect::to_action( 'gotin::users' );
		}
		$user->delete();

		if($account)
			Flash::set( __('gotin::gotin.account_removed'), 'success' );
		else
			Flash::set( __('gotin::gotin.user_removed'), 'success' );
			
		return Redirect::to_action( 'gotin::users' );
	}

	/**
	 * Removes the logged in user accoung
	 *
	 * @return void
	 */
	public function get_deleteaccount() {
		$this->get_delete(Auth::user()->id,true);
		return Redirect::to('/login');
	}


	/**
	 * Shows edit the logged user profile view
	 *
	 * @return mixed View|Recirect
	 */
	public function get_editprofile() {

		$id = Auth::user()->id;
		$user = User::find( $id );
		if ( !$user )
			return Redirect::to_action( 'gotin::users' );
		$roles = User::get_roles( $user );
		// @todo - avoid passing default values to a view (flash)
		return View::make( 'gotin::editprofile', array(
				'flash'=>$this->flash,
				'data'=>$user,
				'roles'=>$roles,
				'user_id'=>'profile',
				'active'=>'users',
				'b_links'=> array('My Profile')
			) );
	}


	/**
	 * Serves edit logged user profile post request
	 *
	 * @return Redirect
	 */
	public function post_editprofile() {

		$id = Auth::user()->id;

		$rules = array(
			'username' => 'required|unique:users,username,'.$id.'',
			'email' => 'unique:users,email,'.$id.'|required|email',
			'password' => 'confirmed'
		);
		$validation = Validator::make( Input::all(), $rules );
		if ( $validation->fails() ) {
			// Flash::set( 'There are errors in the submitted form!', 'error' );
			return Redirect::to_action( 'gotin::users@editprofile', array( $id ) )->with_errors( $validation )->with_input();
		}else {
			$user = User::find( $id );
			$user->username = Input::get( 'username' );
			$user->email = Input::get( 'email' );
			if ( Input::has( 'password' ) ) {
				$user->password = Hash::make( Input::get( 'password' ) );
			}
			$user->save();
			Flash::set( __('gotin::gotin.data_changed'), 'success' );
			return Redirect::to_action( 'gotin::users@editprofile', array( $id ) );
		}
	}


}
