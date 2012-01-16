<?php

	require_once ( "Framework/request.class.php" );
	
	$oRequest = new Request (  );
	
	echo $oRequest -> execute ( "s14" );
	
	//echo "<pre>"; print_r($oRequest->execute ( 's14' ) ); echo "</pre>";
?>