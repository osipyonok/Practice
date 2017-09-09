<?php
	include 'chat_class.php';
	require_once('functions.php');
	if ($_REQUEST['action'] == 'get_last_messages') {
		$sChatMessages = $GLOBALS['Chat']->getMessages(true);

		require_once('Services_JSON.php');
		$oJson = new Services_JSON();
		echo $oJson->encode(array('messages' => $sChatMessages));
		exit;
	}
	echo '<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>';
	echo '<script type="text/javascript" src="js/main.js"></script>';
	$sChatMessages = $GLOBALS['Chat']->getMessages();
	echo "<div class='chat_main'>".$sChatMessages."</div>";
	if(!empty($_SESSION['login'])){
		include('send_mess.php');
	}else{
		include('login.php');
	}
	$GLOBALS['Chat']->acceptMessages();
//	require_once('send_mess.php');
//	echo  $sChatInputForm;
?>