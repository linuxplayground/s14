<?php
	$scoreActions = array(
		"listScore" => array(
			"file" => "modules/score/actions/score.php",
			"class" => "ScoreAction",
			"method" => "listScore",
			"results" => array(
				"listScore" => array("view" => "listScore"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"editScore" => array(
			"file" => "modules/score/actions/score.php",
			"class" => "ScoreAction",
			"method" => "editScore",
			"results" => array(
				"editScoreForm" => array("view" => "editScoreForm"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"updateScore" => array(
			"file" => "modules/score/actions/score.php",
			"class" => "ScoreAction",
			"method" => "updateScore",
			"results" => array(
				"updateScoreSuccess" => array("action" => "listScore"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		)
	);
	
	$scoreViews = array(
		"listScore" => array(
			"file" => "modules/score/views/score.php",
			"class" => "ScoreView",
			"method" => "listScore"
		),
		"editScoreForm" => array(
			"file" => "modules/score/views/score.php",
			"class" => "ScoreView",
			"method" => "editScore"
		)
	);
?>