<?php
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
    if(isset($_POST['mail'])){ 
		$mail = $_POST['mail']; 
		if($mail ==''){ 
			unset($mail);
		} 
	}	
	
	if(empty($login) or empty($password) or empty($mail)){
		header('Location: index.php?signup=empty_data');
		exit;
    }
    
    reg_prepare($login);  
	reg_prepare($password);
    reg_prepare($mail);
	
	$db = connect();
	if($db->connect_errno) {
		header('Location: index.php?signup=db_error');
		exit;
	}
	
	$ck_login = "SELECT 1 FROM `USERS` WHERE `Username` = '".$login."'";
	$ck_mail = "SELECT 1 FROM `USERS` WHERE `Email` = '".$mail."'";
	
	$ck_login_res = $db->query($ck_login);
	$ck_mail_res = $db->query($ck_mail);
	
	if(!$ck_login_res or !$ck_mail_res){
		header('Location: index.php?signup=db_error');
		exit;	
	}
	
	if($ck_login_res->num_rows > 0){
		header('Location: index.php?signup=login_exists');
		exit;			
	}
	if($ck_mail_res->num_rows > 0){
		header('Location: index.php?signup=mail_exists');
		exit;	
	}
	$pass = password_hash($password, PASSWORD_DEFAULT);
	$signup = "INSERT INTO `USERS`(`Username`, `Password`, `Email`) VALUES ('".$login."', '".$pass."', '".$mail."')";
    $res = $db->query($signup);
	if($res){
		header('Location: index.php?signup=ok');
		exit;		
	}else{
		header('Location: index.php?signup=db_error');
		exit;			
	}
?>