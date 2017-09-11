<?php
	ignore_user_abort(true);
	include 'functions.php';
	$doozers = array('http://tk2017euro.apphb.com', 'http://tk2017usa.apphb.com');
	$db = connect();
	foreach($doozers as $page){
		$status = ping($page);
		$query = "INSERT INTO `FUN`(`URL`, `HTTP_STATUS`) VALUES ('".$page."', '".$status."')";
		$db->query($query);
	}
	$db->close();
?>