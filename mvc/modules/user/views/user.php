<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class UserView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function newUserForm ($oMsg) {
		$oMsg->response = $this->execute($oMsg->data['newUserFormXml'], 'modules/user/views/templates/editUser.xsl') ;
	}
	
	public function listUser ($oMsg) {
	
		$oMsg->response = $this->execute($oMsg->data['listUser']['xml'], 'modules/user/views/templates/listUsers.xsl');
	}
	
	public function editUser ($oMsg) {
		$oMsg->response = $this->execute($oMsg->data['editUser']['xml'], 'modules/user/views/templates/editUser.xsl') ;
	}
	
	public function confirmDelete ( $oMsg ) {
		$oMsg->response = $this->execute ( $oMsg->data['confirmDelete']['xml'], 'modules/user/views/templates/confirmDeleteUser.xsl' );
	}
}
?>