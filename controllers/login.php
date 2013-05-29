<?php
/**
 * Log in / out, register, forgot password email
 * Login controller
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Controllers
 */

class Gotin_Login_Controller extends Gotin_Controller
{

	public $restful = true;

	/**
	 * Sets login/register mode 
	 * classic|ajax
	 *
	 * @var string
	 * */
	private $ajax;

	/**
	 * validation rules for registration form
	 *
	 * @var array
	 * */
	private $register_rules;

	/**
	 * validation messages for registration form
	 *
	 * @var array
	 * */
	private $register_messages;

	/**
	 * Construct
	 * Sets login/register mode (classic|ajax)
	 * Sets register validation rules & messages
	 *
	 * @return void
	 */
	public function __construct() {
		if (Config::get('gotin::gotin.login_mode') == "ajax")
			$this->ajax = true;
		else
			$this->ajax = false;

		/**
		 *
		 */
		Validator::register( 'question', function( $attribute, $value ) {
				return Str::lower( $value ) == Session::get( "Register_question" );
			} );

		$this->register_rules = array(
			'username-register' => 'required|unique:users,username',
			'email-register' => 'unique:users,email|required|email',
			'password-register' => 'required|confirmed',
			'question' => 'required|question'
		);

		$this->register_messages = array(
			'question' => __('gotin::gotin.question_error'),
			'email-register_required' => __('gotin::gotin.email-register_required'),
			'email-register_unique' => __('gotin::gotin.email-register_unique'),
			'username-register_required' => __('gotin::gotin.username-register_required'),
			'username-register_unique' => __('gotin::gotin.username-register_unique'),
			'password-register_required' => __('gotin::gotin.password-register_required'),
			'password-register_confirmed' => __('gotin::gotin.password-register_confirmed')
		);
	}


	/**
	 * Login view
	 *
	 * @return mixed str|Redirect
	 */
	public function get_login() {

		if ( Auth::guest() ) {

			$flash = Session::get( 'form_errors' );
			if ($this->ajax) {
				$day_number = $this->generate_captcha();
				return View::make( 'gotin::login.login', array(
						'flash'=>$flash,
						'day_number'=>$day_number
					) );
			}
			else
				return View::make( 'gotin::login.login_classic', array(
						'flash'=>$flash
					) );

		}else {
			// redirect url once logged in
			return Redirect::to_action( 'gotin::dashboard' );
		}

	}


	/**
	 * Logout from admin
	 *
	 * @return Redirect
	 */
	public function get_logout() {

		Auth::logout();
		return Redirect::to_action( 'gotin::login@login' );

	}


	/**
	 * Register get
	 *
	 * @return mixed str|Recirect
	 */
	public function get_register() {

		if (Auth::guest()) {
			$data = new stdClass();
			$data->username = "";
			$data->email = "";
			$day_number = $this->generate_captcha();
			$flash = Session::get('form_errors');
			return View::make('gotin::login.register_classic', array('flash'=>$flash, 'data'=>$data, 'day_number'=>$day_number));
		}else {
			return Redirect::to_action('gotin::dashboard');
		}

	}


	/**
	 * Serves register post request (classic mode)
	 *
	 * @return Redirect
	 */
	public function post_register() {

		$validation = Validator::make(Input::all(), $this->register_rules, $this->register_messages);

		if ($validation->fails()) {

			// Flash::set('There are errors in the submitted form!', 'error');
			return Redirect::to('register')->with_errors($validation)->with_input();

		}else {

			$user = new User;
			$user->username = Input::get('username-register');
			$user->email = Input::get('email-register');
			$user->password = Hash::make(Input::get('password-register'));
			$user->save();

			if ($user) {
				$insUser = User::find($user->id);
				$insUser->roles()->delete();
				// attribute login role to the created user
				$insUser->roles()->attach(1);
			}

			// login in right after successful register
			$this->post_login(array(Input::get('username-register'), Input::get('password-register')));
			return Redirect::to_action('gotin::dashboard');
		}
	}


	/**
	 * Serves login post request
	 *
	 * @param array   $from_register (optional) credentials from register (right after registering)
	 * @return Redirect
	 */
	public function post_login($from_register=null) {

		if ($from_register != null) {

			$credentials = array(
				'username' => $from_register[0],
				'password' => $from_register[1],
			);

		}else {

			$credentials = array(
				'username' => Input::get('email-login'),
				'password' => Input::get('password-login'),
			);
		}

		if (Auth::attempt_gotin( $credentials )) {

			$remember = Input::get('remember');

			if (!empty($remember) && $remember != null) {
				Auth::login(Auth::user()->id, true);
			}else {
				Auth::login(Auth::user()->id, false);
			}

			return Redirect::to_action('gotin::dashboard');

		}else {
			Flash::set(__('gotin::gotin.wrong_credentials'), 'error');
			return Redirect::to('login');
		}
	}


	/**
	 * Serves ajax login request
	 * 
	 * @return json
	 */
	public function action_loginajax() {
		if ( Request::ajax() ) {
			$credentials = array(
				'username' => Input::get( 'email-login' ),
				'password' => Input::get( 'password-login' ),
			);

			if ( Auth::attempt( $credentials ) ) {
				if ( Input::has( 'remember-login' ) ) {
					Auth::login( Auth::user()->id, true );
				}else {
					Auth::login( Auth::user()->id, false );
				}
				return Response::json( 'Authorized', 200 );
			}else {
				return Response::json( 'Unauthorized', 401 );
			}
		}
	}


	/**
	 * Generates question & answer for register captcha
	 *
	 * @return str
	 */
	public function generate_captcha() {
		$days_num = array
		(
			"first",
			"second",
			"third",
			"fourth",
			"fifth",
			"sixth",
			"seventh"
		);
		$days_names = array
		(
			"monday",
			"tuesday",
			"wednesday",
			"thursday",
			"friday",
			"saturday",
			"sunday"
		);
		$index = rand( 0, 6 );
		Session::put( "Register_question", $days_names[$index] );
		return $days_num[$index];
	}


	/**
	 * Serves ajax register request
	 *
	 * @return json
	 */
	public function action_registerajax() {
		if ( Request::ajax() ) {

			$validation = Validator::make( Input::all(), $this->register_rules, $this->register_messages );

			if ( $validation->fails() ) {
				return Response::json( $validation, 401 );
			}else {
				$user = new User;
				$user->email = Input::get( 'email-register' );
				$user->username = Input::get( 'username-register' );
				$user->password = Hash::make( Input::get( 'password-register' ) );
				$user->save();
				if ( $user ) {
					$insUser = User::find( $user->id );
					$insUser->roles()->delete();
					$insUser->roles()->attach( 1 );
				}
				return Response::json( __('gotin::gotin.successful_registration'), 200 );
			}
		}
	}


	/**
	 * Forgot password view
	 *
	 * @return mixed str|Register
	 */
	public function get_forgot() {

		if (Auth::guest()) {
			$data = new stdClass();
			$data->email = "";
			$flash = Session::get('form_errors');
			return View::make('gotin::login.forgot_classic', array('flash'=>$flash, 'data'=>$data));
		}else {
			return Redirect::to('login');
		}

	}


	/**
	 * Serves forgot password post request
	 *
	 * @return Redirect
	 */
	public function post_forgot() {

		$rules = array(
			'email-forgot' => 'required|email'
		);

		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails()) {
			Flash::set('Provide a valid email address!', 'error');
			return Redirect::to('forgot-password')->with_errors($validation)->with_input();
		}else {

			$email = Input::get( 'email-forgot' );
			$user = User::where( 'email', '=', $email )->first();
			if ( $user == null ) {
				Flash::set( 'Provide a valid email address!', 'error');
				return Redirect::to('forgot-password');
			}else {
				$ret = $this->new_password($user, $email);
				if ( $ret ) {
					Flash::set(__('gotin::gotin.email_sent'), 'success');
					return Redirect::to('login');
				}else {
					Flash::set( __('gotin::gotin.send_email_error'), 'error');
					return Redirect::to('forgot-password');
				}
			}

		}

	}


	/**
	 * Serves forgot password ajax request
	 *
	 * @return json
	 */
	public function action_forgotajax() {
		if ( Request::ajax() ) {
			$rules = array(
				'email-forgot' => 'required|email'
			);
			$validation = Validator::make( Input::all(), $rules );
			if ( $validation->fails() ) {
				return Response::json( __('gotin::gotin.invalid_email'), 401 );
			}else {
				$email = Input::get( 'email-forgot' );
				$user = User::where( 'email', '=', $email )->first();
				if ( $user == null ) {
					return Response::json( 'Email not found!', 401 );
				}else {
					$ret = $this->new_password($user, $mail);
					if ( $ret ) {
						return Response::json( __('gotin::gotin.email_sent'), 200 );
					}else {
						return Response::json( __('gotin::gotin.send_email_error'), 401 );
					}
				}
			}
		}
	}


	/**
	 * Generates a random password
	 * Sends the pasword to a user's email
	 *
	 * @param obj $user
	 * @param str $email
	 * @return bool
	 */
	private function new_password($user, $email) {
		$raw = Str::random( 8 );
		$user->password = Hash::make( $raw );
		$user->save();
		$html = "Your new password: " . $raw;
		return Mailer::send( $email, 'Title', $html );
	}


}
