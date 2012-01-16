<?php
	$pickActions = array(
		"getPicksForUser" => array(
			"file" => "modules/pick/actions/pick.php",
			"class" => "PickAction",
			"method" => "getPicksForUser",
			"results" => array(
				"listPicks" => array( "view" => "listPicks" ),
				"testHttp"=>array('view' => 'testHttp'),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"getPrevPicks" => array(
			"file" => "modules/pick/actions/pick.php",
			"class" => "PickAction",
			"method" => "getPreviousPicks",
			"results" => array(
				"listPicks" => array( "view" => "listPrevPicks" ),
				"testHttp"=>array('view' => 'testHttp'),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"getPickGames" => array(
			"file" => "modules/pick/actions/getPickGames.php",
			"class" => "GetPickGamesAction",
			"method" => "getGamesForMatchType",
			"results" => array(
				"returnXmlData" => array( "view" => "returnXmlData" ),
				"error" => array ("view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"submitPicks" => array(
			"file" => "modules/pick/actions/pick.php",
			"class" => "PickAction",
			"method" => "submitPicks",
			"results" => array(
				"good_picks_submit" => array( "action" => "getPicksForUser" ),
				"picksAlreadyPlaced" => array( "action" => "getPicksForUser" ),
				"error" => array ("view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"deletePicks" => array(
			"file" => "modules/pick/actions/pick.php",
			"class" => "PickAction",
			"method" => "deletePicks",
			"results" => array(
				"picksDeleted" => array( "action" => "getPicksForUser" ),
				"error" => array ("view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"confirmDeletePicks" => array(
			"file" => "modules/pick/actions/pick.php",
			"class" => "PickAction",
			"method" => "confirmDeletePicks",
			"results" => array(
				"showConfirmDeletePicks" => array( "view" => "confirmDeletePicks" ),
				"error" => array ("view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		)
	);
	
	$pickViews = array(
		"listPicks" => array(
			"file" => "modules/pick/views/pick.php",
			"class" => "PickView",
			"method" => "listPick"
		),
		"listPrevPicks" => array(
			"file" => "modules/pick/views/pick.php",
			"class" => "PickView",
			"method" => "listPrevPick"
		),
		"testHttp" => array(
			'file' => 'modules/pick/views/pick.php',
			'class' => 'PickView',
			'method' => 'testHttp'
		),
		"returnXmlData" => array(
			"file" => "modules/pick/views/pick.php",
			"class" => "PickView",
			"method" => "returnXmlData"
		),
		"confirmDeletePicks" => array(
			"file" => "modules/pick/views/pick.php",
			"class" => "PickView",
			"method" => "confirmDeletePicks"
		)
	);
?>