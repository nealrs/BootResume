<?php 
// MYSQL PDO INIT
		require 'db_info_u.php';		
		$db = new PDO('mysql:host='.$dbhost.'; dbname='.$dbname.'; charset=UTF8', $dbuser, $dbpass);		
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
// END MYSQL INIT
?>
