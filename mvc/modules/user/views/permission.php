<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class PermissionView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function listPermission( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['listPermissions']['xml'], 'modules/user/views/templates/listPermissions.xsl') ;
	}
	
	public function permissionGroupSelect( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['getPermissionGroupData']['xml'], 'modules/user/views/templates/permissionGroup.xsl');
	}
}