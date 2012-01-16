<?php
/*
 * This module only handles login forms.
 * The Authorisation filter manages all the rest.
 */
	$authActions = array (
		"logInForm" => array (
			"file" => "modules/auth/actions/authorisation.php",
			"class" => "AuthorisationAction",
			"method" => "logInForm",
			"results" => array (
				"display_login_form" => array ("view" => "loginForm" ),
				"user_already_logged_in" => array ("action" => "default" )
			)
		),
		"userRegistration" => array (
			"file" => "modules/auth/actions/userReg.php",
			"class" => "UserRegAction",
			"method" => "newRegistrationForm",
			"results" => array (
				"showNewUserRegistration" => array( "view" => "registerUserForm"),
				"error" => array( "view" => "general_error" )
			)
		),
		"insertNewUser" => array (
			"file" => "modules/auth/actions/userReg.php",
			"class" => "UserRegAction",
			"method" => "insertNewUser",
			"results" => array (
				"insertFail" => array( "action" => "userRegistration"),
				"error" => array( "view" => "general_error" ),
				"insertSuccess" => array ( "action" => "default" )
			)
		)
	);
	
	$authViews = array (
		"loginForm" => array(
			"file" => "modules/auth/views/authorisation.php",
			"class" => "AuthorisationView",
			"method" => "loginForm"
		),
		"registerUserForm" => array(
			"file" => "modules/auth/views/userReg.php",
			"class" => "UserRegView",
			"method" => "registrationForm"
		)
	);
?>