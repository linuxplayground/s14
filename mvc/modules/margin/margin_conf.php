<?php
	$marginActions = array(
		"listMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "listMargin",
			"results" => array (
				"listMargin" => array("view" => "listMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"newMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "newMargin",
			"results" => array (
				"insertMargin" => array("view" => "editMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"insertMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "insertMargin",
			"results" => array (
				"insertMarginSuccess" => array("action" => "listMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"editMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "editMargin",
			"results" => array (
				"editMarginForm" => array("view" => "editMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"updateMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "updateMargin",
			"results" => array(
				"updateMarginSuccess" => array("action" => "listMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"confirmDeleteMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "confirmDeleteMargin",
			"results" => array(
				"confirmDeleteMarginForm" => array("view" => "confirmDeleteMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"deleteMargin" => array(
			"file" => "modules/margin/actions/margin.php",
			"class" => "MarginAction",
			"method" => "deleteMargin",
			"results" => array(
				"deleteMarginSuccess" => array("action" => "listMargin"),
				"didNotConfirmDelete" => array ("action" => "listMargin"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		)		
	);
	
	$marginViews = array(
		"listMargin" => array(
			"file" => "modules/margin/views/margin.php",
			"class" => "MarginView",
			"method" => "listMargin"
		),
		"editMargin" => array(
			"file" => "modules/margin/views/margin.php",
			"class" => "MarginView",
			"method" => "editMargin"
		),
		"confirmDeleteMargin" => array(
			"file" => "modules/margin/views/margin.php",
			"class" => "MarginView",
			"method" => "confirmDeleteMargin"
		)
	);
?>