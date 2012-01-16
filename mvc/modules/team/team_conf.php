<?php
	$teamActions = array(
		"listTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "listTeam",
			"results" => array(
				"error" => array ("view" => "general_error"),
				"display_team_list" => array("view"=>"listTeam")
			)
		),
		"newTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "newTeam",

			"results" => array (
				"display_new_form" => array ("view" => "newTeam" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		),
		"insertTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "insertTeam",

			"results" => array (
				"insert_data_error" => array ( "view" => "general_error" ),
				"insert_data_success" => array ( "action" => "listTeam" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		),
		
		"editTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "editTeam",
			"results" => array (
				"data_found" => array ( "view" => "editTeam"),
				"data_error" => array ( "view" => "general_error" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		),
		"updateTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "updateTeam",

			"results" => array (
				"update_error" => array ("view" => "general_error" ),
				"update_success" => array ("action" => "listTeam" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		),
		
		"deleteTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "deleteTeam",
			"results" => array (
				"delete_error" => array ("view" => "general_error" ),
				"delete_confirmation_no" => array ( "action" => "listTeam" ),
				"delete_success" => array ("action" => "listTeam" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		),
		
		"confirmDeleteTeam" => array (
			"file" => "modules/team/actions/team.php",
			"class" => "TeamAction",
			"method" => "confirmDelete",
			"results" => array (
				"show_confirmation_check" => array ( "view" => "deleteTeamConfirmationCheck" ),
				"confirm_delete_error" => array ( "view"=> "general_error" ),
				"user_not_logged_in" => array ( "action" => "logInForm" ),
				"user_not_validated" => array ( "action" => "default" ),
				"error" => array ( "view" => "general_error" )
			)
		)
	);
	
	$teamViews = array(
		"listTeam" => array(
			"file" => "modules/team/views/team.php",
			"class" => "TeamView",
			"method" => "listTeam"
		),
		
		"newTeam" => array (
			"file" => "modules/team/views/team.php",
			"class" => "TeamView",
			"method" => "newTeam"
		),
		
		"editTeam" => array (
			"file" => "modules/team/views/team.php",
			"class" => "TeamView",
			"method" => "editTeam"
		),
		
		"deleteTeamConfirmationCheck" => array (
			"file" => "modules/team/views/team.php",
			"class" => "TeamView",
			"method" => "confirmDelete"
		)
	);
?>