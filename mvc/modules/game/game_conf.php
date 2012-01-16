<?php
	$gameActions = array(
	
		"listAllGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "listAllGame",
			"results" => array(
				"listAllGame" => array( "view" => "listAllGame" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"newGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "newGame",
			"results" => array(
				"editGameForm" => array( "view" => "newGameForm" ),
				"error" => array( "view" => "general_error"),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"insertGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "insertGame",
			"results" => array(
				"insertSuccess" => array( "action" => "listAllGame" ),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"editGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "editGame",
			"results" => array(
				"editGameForm" => array( "view" => "editGameForm" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"updateGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "updateGame",
			"results" => array(
				"updateGameSuccess" => array( "action" => "listAllGame" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		),
		"confirmDeleteGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "confirmDeleteGame",
			"results" => array(
				"confirmDeleteGameForm" => array( "view" => "confirmDeleteGame" ),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"deleteGame" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "deleteGame",
			"results" => array(
				"deleteGameSuccess" => array( "action" => "listAllGame" ),
				"didNotConfirmDelete" => array( "action" => "listAllGame" ),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"updateGameScores" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "updateGameScores",
			"results" => array(
				"updateGameScoreSuccess" => array( "action" => "listAllGame" ),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		
		//PDF TEST
		"listAllGamePdf" => array(
			"file" => "modules/game/actions/game.php",
			"class" => "GameAction",
			"method" => "listAllGame",
			"results" => array(
				"listAllGame" => array( "view" => "listAllGamePdf" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module"=>"auth" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "auth" )
			)
		)
	);
	
	$gameViews = array(
		"listAllGame" => array(
			"file" => "modules/game/views/game.php",
			"class" => "GameView",
			"method" => "listAllGame",
		),
		"editGameForm" => array(
			"file" => "modules/game/views/game.php",
			"class" => "GameView",
			"method" => "editGame"
		),
		"newGameForm" => array(
			"file" => "modules/game/views/game.php",
			"class" => "GameView",
			"method" => "newGame"
		),
		"confirmDeleteGame" => array(
			"file" => "modules/game/views/game.php",
			"class" => "GameView",
			"method" => "confirmDeleteGameForm"
		),
		//PDF TEST
			"listAllGamePdf" => array(
			"file" => "modules/game/views/game_pdf.php",
			"class" => "GameViewPdf",
			"method" => "listAllGamePdf",
		)
	);
?>