<?php

Log::getInstance()->write (__FILE__." loaded", "debug");

/*
* ... static template markup ... {TAG} ... static template markup
* tags are to be wrapped in '{...}' with no spaces between.
*/

class GenericTemplate {

	private $file;

	public function __construct( $tpl ) {
		
		
		
		if (!file_exists($tpl) ) {
			Log::getInstance()->write ("Template file ".$tpl." not found on disk", "error");
			die ("Template file ".$tpl. " not found on disk");
		} else {
			Log::getInstance()->write ("File ".$tpl. " loaded", "debug" );
			$this->file = file_get_contents($tpl);
		}
	}
	
	public function assign( $tag, $data ) {
	
		$this->file = str_replace ("{".$tag."}", $data, $this->file);
	}
	
	public function getGenericTemplate( ) {
		return $this->file;
	}
}
?>