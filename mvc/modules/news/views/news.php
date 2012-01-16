<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class NewsView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listNews ( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['news']['xml'], "modules/news/views/templates/news.xsl");
	}
	
	public function editNews ( $oMsg ) {
		$oMsg->response = $this->execute( $oMsg->data['news']['xml'], "modules/news/views/templates/editNews.xsl");
	}
	
	public function confirmDeleteNews( $oMsg ){
		$oMsg->response = $this->execute( $oMsg->data['news']['xml'], "modules/news/views/templates/editNews.xsl");
	}
}
?>