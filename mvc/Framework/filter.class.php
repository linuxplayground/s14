<?php
Log::getInstance()->write(__FILE__." loaded", "debug");

interface Filter {
	public function getError( );
	public function execute( $oMsg );
}

class FilteredController {
	private $cObj; //The controller object
	private $filters = array(); //Array of filter objects
	
	public function __construct ( $controllerObj, $appName ) {
		$this->cObj = $controllerObj; //Add the controller to this.
		$filterConfFile = $appName."_filterConfig.php";
		require_once($filterConfFile);
		$filterConf = new FilterConfiguration();
		foreach ($filterConf->filters as $key => $f) {
			require_once($f['file']);
			$filter = new $f['class'];
			$this->registerFilter($filter, $key);
		}
	}
	
	public function registerFilter( $filterObj, $key ) {
		$this->filters[] = $filterObj;
	}
	
	public function process( $messageObject ) {
		foreach ( $this->filters as $f ) {
			if ( method_exists( $f, "execute" ) ) {
				if (! $f->execute( $messageObject )) {
					//Something has failed during the filters
					die( $f->getError( ) );
				}
			}
		}
		$this->cObj->process( $messageObject );
	}
}
?>