<?php
/**
 * Routing for Gotin bundle
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @copyright   Marcin Baniowski (http://baniowski.pl)
 * @subpackage  Routes
 * @author      Marcin Baniowski
 * @package     Gotin\Routes
 */

// register all controllers for the "gotin" bundle
Route::controller( Controller::detect( 'gotin' ) );

if ((Config::get('gotin::gotin.login_mode') == "classic")) {
	// redirects root url ("/") to login view
	Route::any( '/login', 'gotin::login@login' );
	Route::any( '/register', 'gotin::login@register' );
	Route::any( '/forgot-password', 'gotin::login@forgot' );
}else{
	Route::get( '(:bundle)', 'gotin::login@login' );
}


// logout route
Route::get( '(:bundle)/logout', 'gotin::login@logout' );

// edit profile
Route::any( '(:bundle)/editprofile', 'gotin::users@editprofile' );

// delete profile
Route::any( '(:bundle)/deleteaccount', 'gotin::users@deleteaccount' );

// sets ajax routes for main actions (login,register,forgot password)
if ( Request::ajax() ) {
	Route::post( 'gotin/loginajax', 'gotin::login@loginajax' );
	Route::post( 'gotin/registerajax', 'gotin::login@registerajax' );
	Route::post( 'gotin/forgotajax', 'gotin::login@forgotajax' );
}

Route::any( '(:bundle)/users/(:any?)', array( 'defaults' => 'index', 'uses' => '(:bundle):users@(:1)' ) );
Route::any( '(:bundle)/roles/(:any?)', array( 'defaults' => 'index', 'uses' => '(:bundle):roles@(:1)' ) );

// @todo gropuping: http://laravel.com/docs/routing#route-groups

Route::filter( 'before', function() {

	// add styles to layout
	Asset::container('header')->add('bootstrap_style','bundles/gotin/css/bootstrap.css');
	Asset::container('header')->add('bootstrap_res','bundles/gotin/css/bootstrap-responsive.css');
	Asset::container('header')->add('docs_style','bundles/gotin/css/gotin.css');
	Asset::container('header')->add('gotin_style','bundles/gotin/css/docs.css');

	// add scripts to layout
	Asset::container('header')->add('jquery','bundles/gotin/js/jquery.js');
	Asset::container('header')->add('bootstrap','bundles/gotin/js/bootstrap.min.js');
	if ((Config::get('gotin::gotin.login_mode') == "ajax"))
		Asset::container('header')->add('gotin','bundles/gotin/js/gotin.js');

});

/**
 * Filter admin access
 */
Route::filter( 'auth', function() {

		if(!Auth::gotin_check()) {
			Auth::logout();
			return Redirect::to_action( 'login' );
		}

		// Admin role protected routes
		$admin_protected = array( 'users', 'roles' );

		$gotin_route = Bundle::get( 'gotin' )['handles'];
		foreach ( $admin_protected as $ap ) {
			$r = $gotin_route . "\/" . $ap;
			$match = preg_match( "/".$r."/", URI::current() );
			if ( $match && !Auth::is( "Admin" ) )
			return Redirect::to_action( 'gotin::dashboard' );
		}

	} );
