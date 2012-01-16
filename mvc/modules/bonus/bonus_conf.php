<?php
$bonusActions = array(
	"listBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "listBonus",
		"results" => array(
			"listBonus" => array( "view" => "listBonus"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"newBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "newBonus",
		"results" => array(
			"newBonusForm" => array( "view" => "newBonusForm"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"insertBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "insertBonus",
		"results" => array(
			"insertBonusSuccess" => array( "action" => "listBonus"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"editBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "editBonus",
		"results" => array(
			"editBonusForm" => array( "view" => "editBonusForm"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"updateBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "updateBonus",
		"results" => array(
			"updateBonusSuccess" => array( "action" => "listBonus"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"confirmDeleteBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "confirmDeleteBonus",
		"results" => array(
			"confirmDeleteBonusForm" => array( "view" => "confirmDeleteBonusForm"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	),
	"deleteBonus" => array(
		"file" => "modules/bonus/actions/bonus.php",
		"class" => "BonusAction",
		"method" => "deleteBonus",
		"results" => array(
			"deleteBonusSuccess" => array( "action" => "listBonus"),
			"error" => array("view" => "general_error"),
			"user_not_logged_in" => array("action" => "logInForm", "module"=>"auth"),
			"user_not_validated" => array("action" => "logInForm", "module" => "auth")
		)
	)
);

$bonusViews = array(
	"listBonus" => array(
		"file" => "modules/bonus/views/bonus.php",
		"class" => "BonusView",
		"method" => "listBonus"
	),
	"newBonusForm" => array(
		"file" => "modules/bonus/views/bonus.php",
		"class" => "BonusView",
		"method" => "editBonus"
	),
	"editBonusForm" => array(
		"file" => "modules/bonus/views/bonus.php",
		"class" => "BonusView",
		"method" => "editBonus"
	),
	"confirmDeleteBonusForm" => array(
		"file" => "modules/bonus/views/bonus.php",
		"class" => "BonusView",
		"method" => "confirmDeleteBonus"
	)
);
?>