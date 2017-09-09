<?php
	include 'functions.php';
	
	$act = $_POST['act'];
	if($act == "send"){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$text = parse($_POST['text']);	
		
		if(strlen($text) > 0 and strlen($name) > 0){ 
			$db = connect();
			$query = "INSERT INTO `MESSAGES`(`Author_ID`, `Author_Name`, `Message`) VALUES ('".$id."', '".$name."', '".$text."')";
			$res = $db->query($query);
		}
	}
?>