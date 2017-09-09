<?php
	session_start();
	include 'functions.php';
	require_once 'lib/passwordLib.php';
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
		header('Location: index.php');
		exit;		
	}
	
	if(isset($_POST['login'])){ 
		$login = $_POST['login']; 
		if($login == ''){ 
			unset($login);
		} 
	}
    if(isset($_POST['password'])){ 
		$password = $_POST['password']; 
		if($password ==''){ 
			unset($password);
		} 
	}
	
	if(empty($login) or empty($password)){
		header('Location: index.php?login=empty_data');
		exit;
    }
    
    reg_prepare($login);  
	reg_prepare($password);
	
	$db = connect();
	if($db->connect_errno) {
		header('Location: index.php?login=db_error');
		exit;
	}
	$query = "SELECT * FROM `USERS` WHERE (`Username` = '".$login."' OR `Email` = '".$login."')";
	$res = $db->query($query);
	if($res->num_rows > 0){
		$row = $res->fetch_assoc();
		if(password_verify($password, $row["Password"])){
			$_SESSION['login'] = $row["Username"];
			$_SESSION['id'] = $row["ID"];
			$_SESSION['email'] = $row["Email"];
			header('Location: index.php?login=ok');
			exit;
		}
		header('Location: index.php?login=wrong_password');
		exit;
	}
	header('Location: index.php?login=wrong_login_or_email');
?>