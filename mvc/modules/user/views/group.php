<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class GroupView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	public function error ($oMsg) {
	
		$oMsg->response = "<h2>ERROR!!</h2>". $oMsg->data['errorpage'];
	}
	
	public function listGroup( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['listGroup']['xml'], 'modules/user/views/templates/listGroups.xsl') ;
	}
	
	public function newGroup( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['newGroupForm']['xml'], 'modules/user/views/templates/editGroup.xsl') ;
	}
	
	public function editGroup( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['editGroupForm']['xml'], 'modules/user/views/templates/editGroup.xsl') ;
	}
	
	public function confirmDelete ( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['showDeleteGroupConfirmationForm']['xml'], 'modules/user/views/templates/confirmDeleteGroup.xsl');
	}
	public function permissionSelect ( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['permissionsForGroup']['xml'], 'modules/user/views/templates/permissionSelect.xsl');
	}
	public function groupUserSelect ( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['userGroupData']['xml'], 'modules/user/views/templates/userSelect.xsl');
	}
}