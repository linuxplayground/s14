<?php
	$userActions = array(
		"listUser" => array (
			"file"=> "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "listUser",
			"results" => array(
				"display_user_list" => array("view"=>"listUser"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth"),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"newUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "newUser",
			"results" => array (
				"display_new_user_form" => array("view" => "display_new_user_form"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"insertUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "insertUser",
			"results" => array (
				"insert_data_error" => array ( "view" => "general_error" ),
				"insert_data_success" => array ( "action" => "listUser" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"editUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "editUser",
			"results" => array (
				"data_found" => array ( "view" => "editUser"),
				"data_error" => array ( "view" => "general_error" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"updateUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "updateUser",
			"results" => array (
				"update_error" => array ("view" => "general_error" ),
				"update_success" => array ("action" => "listUser" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"deleteUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "deleteUser",
			"results" => array (
				"delete_error" => array ("view" => "general_error" ),
				"delete_confirmation_no" => array ( "action" => "listUser" ),
				"delete_success" => array ("action" => "listUser" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"confirmDeleteUser" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "confirmDelete",
			"results" => array (
				"show_confirmation_check" => array ( "view" => "deleteUserConfirmationCheck" ),
				"confirm_delete_error" => array ( "view"=> "general_error" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
//USER GROUPS
		"getUserGroupData" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "getUserGroupData",
			"results" => array(
				"error" => array("view"=>"general_error"),
				"showUserGroupSelectPage" => array("view"=>"userGroupSelect"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),

		"updateUserGroup" => array (
			"file" => "modules/user/actions/user.php",
			"class" => "UserAction",
			"method" => "updateUserGroup",
			"results" => array (
				"success" => array ("action" => "getUserGroupData"),
				"error" => array ("view" => "general_error"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
//GROUPS
		"listGroup" =>array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "listGroup",
			"results" => array(
				"display_group_list" => array("view" => "display_group_list"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"newGroup" => array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "newGroup",
			"results" => array(
				"display_new_group_form" => array("view" => "display_new_group_form"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"insertGroup" =>array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "insertGroup",
			"results" => array(
				"display_group_list" => array("action" => "listGroup"),
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"editGroup" => array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "editGroup",
			"results" => array(
				"display_edit_group_form" => array("view" => "display_edit_group_form"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")	
			)
		),
		"updateGroup" => array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "updateGroup",
			"results" => array(
				"success" => array("action" => "listGroup"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"confirmDeleteGroup" => array(
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "confirmDeleteGroup",
			"results" => array(
				"display_delete_group_confirmation_form" => array("view" => "deleteGroupConfirmationCheck"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"deleteGroup" => array (
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "deleteGroup",
			"results" => array (
				"delete_confirmation_no" => array ( "action" => "listGroup" ),
				"success" => array ("action" => "listGroup" ),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"groupPermissions" => array (
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "getPermissionsForGroup",
			"results" => array (
				"displayPermissionsSelect" => array("view" => "displayPermissionSelect"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"updateGroupPermission" => array (
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "updateGroupPermission",
			"results" => array (
				"error" => array("view" => "general_error"),
				"user_not_logged_in" => array("action" => "logInForm", "module" => "auth" ),
				"success" => array("action" => "groupPermissions")
			)
		),
		"groupUsers" => array (
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "getGroupUserData",
			"results" =>array (
				"success" => array("view" => "displayGroupUserSelect"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		"updateGroupUser" => array (
			"file" => "modules/user/actions/group.php",
			"class" => "GroupAction",
			"method" => "updateGroupUser",
			"results" => array (
				"success" => array("action" => "groupUsers"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
//PERMISSIONS
		"listPermission" => array (
			"file" => "modules/user/actions/permission.php",
			"class" => "PermissionAction",
			"method" => "listPermission",
			"results" => array(
				"display_permission_list" => array ("view" => "listPermission"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"getGroupPermissionData" => array (
			"file" => "modules/user/actions/permission.php",
			"class" => "PermissionAction",
			"method" => "getGroupPermissionData",
			"results" => array(
				"showPermissionGroupSelectPage" => array( "view" => "permissionGroupSelect"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		),
		
		"updatePermissionGroup" => array (
			"file" => "modules/user/actions/permission.php",
			"class" => "PermissionAction",
			"method" => "updatePermissionGroup",
			"results" => array (
				"success" => array ("action" => "getGroupPermissionData"),
				"user_not_logged_in" => array ( "action" => "logInForm", "module" => "auth" ),
				"user_not_validated" => array ("view" => "general_error"),
				"error" => array( "view" => "general_error")
			)
		)
	);
	
	$userViews = array(
		"listUser" => array(
			"file" => "modules/user/views/user.php",
			"class" => "UserView",
			"method" => "listuser"
		),
		
		"display_new_user_form" => array(
			"file" => "modules/user/views/user.php",
			"class" => "UserView",
			"method" => "newUserForm"
		),
		
		"editUser" => array (
			"file" => "modules/user/views/user.php",
			"class" => "UserView",
			"method" => "editUser"
		),
		
		"deleteUserConfirmationCheck" => array (
			"file" => "modules/user/views/user.php",
			"class" => "UserView",
			"method" => "confirmDelete"
		),
//USERGROUPS
		"userGroupSelect" => array (
			"file" => "modules/user/views/usergroup.php",
			"class" => "UserGroupView",
			"method" => "userGroupSelectPage"
		),

//GROUPS
		"display_group_list" => array(
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "listGroup"
		),
		"display_new_group_form" => array(
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "newGroup"
		),
		"display_edit_group_form" => array(
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "editGroup"
		),
		
		"deleteGroupConfirmationCheck" => array (
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "confirmDelete"
		),
		"displayPermissionSelect" => array(
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "permissionSelect"
		),
		"displayGroupUserSelect" => array(
			"file" => "modules/user/views/group.php",
			"class" => "GroupView",
			"method" => "groupUserSelect"
		),
//PERMISSIONS
		"listPermission" => array(
			"file" => "modules/user/views/permission.php",
			"class" => "PermissionView",
			"method" => "listPermission"
		),
		
		"selectPermissionForm" => array(
			"file" => "modules/user/views/permission.php",
			"class" => "PermissionView",
			"method" => "selectPermissionForm"
		),
		
		"permissionGroupSelect" => array(
			"file" => "modules/user/views/permission.php",
			"class" => "PermissionView",
			"method" => "permissionGroupSelect"
		)
	);
?>