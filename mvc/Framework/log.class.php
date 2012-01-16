<?php

class Log {
	private $levels = array (
		"error" => 0,
		"info" => 1,
		"warning" => 2,
		"debug" => 3
	);
	
	private $level;
		
	private $file;

	static $instance = NULL;
	
	protected function __Construct ( $file = '/tmp/application', $level = 'info' ) {
		
		$this->file = $file;
		$this->level = $level;
		$this->write ("************* START ***********", "debug" );
		$this->write (__FILE__." loaded" );
	}
	
	static function getInstance() {
		if (self::$instance == NULL) {
			self::$instance = new Log('/tmp/application','debug');
		}
		return self::$instance;
	}
	
	public function write ($entry, $level = 'debug' ) {
		
		if ( $this->levels [ $this->level ] < $this->levels[ $level ] )
			return;
		
		$line = "\"".date( 'r' )."\",";
		$line .= "\"".$level."\",";
		$line .= "\"".$_SESSION['userName']."\",";
		$line .= "\"".$_SERVER['REMOTE_ADDR']."\",";
		$line .= "\"".$entry."\""."\r\n";  // \r\n allows for new lines on windows systems.  \n only required for linux.
		// note that the \r on a nix systems is treated as whitespace.
		
		$file = fopen ( $this->file.".".date( "Y-m-d" ).".log", "a" );
		fputs( $file, $line );
		fclose( $file );
	}
	
}

?>