<?php
	die("testIndex");
	/*define('DB_USER','tia16001');
	define('DB_PASS','bacode');
	define('DB_NAME','tia16011');
	define('DB_HOST','127.0.0.1');*/
	
	/* TODO: Move to config. Put there for debugging purposes */
	define('DB_USER','root');
	define('DB_PASS','');
	define('DB_NAME','infoplusplus');
	define('DB_HOST','127.0.0.1');
	//Required session variables
	if(!isset($_SESSION['role'])){
		$_SESSION['role'] = 'guest';
	}
	if(!isset($_SESSION['isLoggedIn'])){
		$_SESSION['isLoggedIn'] = 0;
	}
	if(!isset($_SESSION['pannierQte'])){
		$_SESSION['pannierQte'] = 0;
	}
	if(!isset($_SESSION['userId'])){
		$_SESSION['userId'] = 0;
	}
