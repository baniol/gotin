<?php

return array(

	/**
	 * classic|ajax
	 * */
	'login_mode' => 'classic',

	// login with username|email|both
	'user_check' => 'both',

	// smtp settings (forgot password email)
	'smtp_user' => '',
	'smtp_password' => '',
	'smtp_port' => '',

	'from_email' => '',
	'from_title' => 'gotin bundle',

	// non secured urls
	'not_secured_urls' => array('login', 'loginajax', 'registerajax', 'forgotajax')

);
