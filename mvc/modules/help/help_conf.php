<?php
	$helpActions = array(
		"showHelp" => array(
			"file" => "modules/help/actions/helpAction.php",
			"class" => "HelpAction",
			"method" => "showHelp",
			"results" => array (
				"showHelp" => array("view" => "showHelp"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"pleasePay" => array(
			"file" => "modules/help/actions/helpAction.php",
			"class" => "HelpAction",
			"method" => "pleasePay",
			"results" => array(
				"pleasePay" => array("view" => "pleasePay"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		)
	);
	
	$helpViews = array(
		"showHelp" => array(
			"file" => "modules/help/views/helpView.php",
			"class" => "HelpView",
			"method" => "showHelp"
		),
		"pleasePay" => array(
			"file" => "modules/help/views/helpView.php",
			"class" => "HelpView",
			"method" => "pleasePay"
		)
	);
?>