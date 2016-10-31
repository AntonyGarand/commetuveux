<?php
    /*define('DB_USER','tia16001');
    define('DB_PASS','bacote');
    define('DB_NAME','tia16001');
    define('DB_HOST','127.0.0.1');
	define('SITE_URL','http://weba.cegepsherbrooke.qc.ca/~tia16001/');

    //Antony's PC debugging values
    /*define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'projet1');
    define('DB_HOST', '127.0.0.1');
	define('SITE_URL','http://p1.ca/');*/
	
	//Amelie's PC debugging values
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tia16001');
    define('DB_HOST', '127.0.0.1');
	define('SITE_URL','http://localhost/commetuveux/');
	
    //Required session variables
    if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = 'guest';
    }
    if (!isset($_SESSION['isLoggedIn'])) {
        $_SESSION['isLoggedIn'] = 0;
    }
    if (!isset($_SESSION['pannierQte'])) {
        $_SESSION['pannierQte'] = 0;
    }
    if (!isset($_SESSION['userId'])) {
        $_SESSION['userId'] = 0;
    }
