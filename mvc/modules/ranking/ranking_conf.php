<?php
	$rankingActions = array(
		"leaderboard" => array(
			"file" => "modules/ranking/actions/ranking.php",
			"class" => "RankingAction",
			"method" => "leaderboard",
			"results" => array (
				"displayLeaderboard" => array("view" => "displayLeaderboard"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		),
		"leaderboardPdf" => array(
			"file" => "modules/ranking/actions/ranking.php",
			"class" => "RankingAction",
			"method" => "leaderboard",
			"results" => array (
				"displayLeaderboard" => array("view" => "displayLeaderboardPdf"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
				"user_not_validated" => array("action" => "logInForm", "module" => "auth")
			)
		)
	);
	
	$rankingViews = array(
		"displayLeaderboard" => array(
			"file" => "modules/ranking/views/ranking.php",
			"class" => "RankingView",
			"method" => "displayLeaderboard"
		),
		"displayLeaderboardPdf" => array(
			"file" => "modules/ranking/views/ranking.php",
			"class" => "RankingView",
			"method" => "displayLeaderboardPdf"
		),
	);
?>