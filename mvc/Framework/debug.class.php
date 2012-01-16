<?php
class Debug {
	
	static private $instance = NULL;
	
	static function getInstance( ) {
		if (self::$instance == NULL) {
			self::$instance = new Debug();
		}		
		return self::$instance;
	}
	
	public function showStack( $obj, $name="", $exitCode=1) {
		echo "<html><body><h1>STACK TRACE</h1><H4>".$name."</H4><pre>";
		print_r($obj);
		echo "</pre></body></html>";
		if ($exitCode == 1) {
			exit;
		}
	}
}
?>