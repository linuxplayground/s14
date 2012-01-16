<?php
	$newsActions = array(
		"listNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "listNews",
			"results" => array(
				"display_news" => array( "view" => "listNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"newNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "newNews",
			"results" => array(
				"display_new_news_form" => array("view" => "newNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"insertNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "insertNews",
			"results" => array(
				"listNews" => array("action" => "listNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"editNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "editNews",
			"results" => array(
				"display_edit_news_form" => array("view" => "editNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"updateNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "updateNews",
			"results" => array(
				"listNews" => array("action" => "listNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"confirmDeleteNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "confirmDeleteNews",
			"results" => array(
				"show_delete_confirm_form" => array( "view" => "confirmDeleteNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		),
		"deleteNews" => array(
			"file" => "modules/news/actions/news.php",
			"class" => "NewsAction",
			"method" => "deleteNews",
			"results" => array(
				"deleteSuccess" => array("action" => "listNews" ),
				"error" => array( "view" => "general_error" ),
				"user_not_logged_in" => array( "action" => "logInForm", "module" => "user" ),
				"user_not_validated" => array( "action" => "logInForm", "module" => "user" )
			)
		)
	);
	
	$newsViews = array(
		"listNews" => array(
			"file" => "modules/news/views/news.php",
			"class" => "NewsView",
			"method" => "listNews"
		),
		"newNews" => array(
			"file" => "modules/news/views/news.php",
			"class" => "NewsView",
			"method" => "editNews"
		),
		"editNews" => array(
			"file" => "modules/news/views/news.php",
			"class" => "NewsView",
			"method" => "editNews"
		),
		"confirmDeleteNews" => array(
			"file" => "modules/news/views/news.php",
			"class" => "NewsView",
			"method" => "confirmDeleteNews"
		)
	);
?>