<?php
	$matchTypeActions = array(
	
		"listMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "listMatchType",
			"results" => array (
				"listMatchType" => array( "view"=>"listMatchType" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"newMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "newMatchType",
			"results" => array (
				"newMatchTypeForm" => array( "view"=>"newMatchTypeForm" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"insertMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "insertMatchType",
			"results" => array (
				"insertMatchTypeSuccess" => array( "action"=>"listMatchType" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"confirmDeleteMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "confirmDeleteMatchType",
			"results" => array (
				"confirmDeleteMatchTypeForm" => array( "view"=>"confirmDeleteMatchTypeForm" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"deleteMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "deleteMatchType",
			"results" => array(
				"deleteMatchTypeSuccess" => array( "action" => "listMatchType"),
				"error" => array( "view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"editMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "editMatchType",
			"results" => array(
				"editMatchTypeForm" => array("view" => "editMatchTypeForm"),
				"error" => array( "view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
				)
			),
		"updateMatchType" => array(
			"file" => "modules/matchType/actions/matchType.php",
			"class" => "MatchTypeAction",
			"method" => "updateMatchType",
			"results" => array(
				"updateMatchTypeSuccess" => array("action" => "listMatchType"),
				"error" => array( "view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
				)
			)
	);
	
	$matchTypeViews = array(
		"listMatchType" => array(
			"file" => "modules/matchType/views/matchType.php",
			"class" => "MatchTypeView",
			"method" => "listMatchType"
		),
		"newMatchTypeForm" => array(
			"file" => "modules/matchType/views/matchType.php",
			"class" => "MatchTypeView",
			"method" => "newMatchTypeForm"
		),
		"confirmDeleteMatchTypeForm" => array(
			"file" => "modules/matchType/views/matchType.php",
			"class" => "MatchTypeView",
			"method" => "confirmDeleteMatchTypeForm"
		),
		"editMatchTypeForm" => array(
			"file" => "modules/matchType/views/matchType.php",
			"class" => "MatchTypeView",
			"method" => "editMatchTypeForm"
		)
	);
?>