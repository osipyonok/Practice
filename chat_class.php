<?php
	session_start();
	
	class Chat{
		function Chat(){}
		
		function acceptMessages(){
			$sUsername = $_SESSION['login'];
			$iUserID = (int)$_SESSION['id'];
			if($sUsername && isset($_POST['mess']) && $_POST['mess'] != '') {
				$db = connect();
				if($db->connect_errno) {
					$db->close();
					return 0;
				}
				
				$sMessage = parse($_POST['s_message']);
				if($sMessage != '') {
					$query = "INSERT INTO `MESSAGES`(`Author_ID`, `Author_Name`, `Message`) VALUES ('".$iUserID."', '".$sUsername."', '".$sMessage."')";
					$res = $db->query($query);
				}
				$db->close();
			}
		}
		
		function getInputForm() {
			ob_start();
			require_once('send_mess.php');
			return ob_get_clean();
		}
		
		function getMessages($bOnlyMessages = false) {
			$db = connect();
			$query = "SELECT * FROM `MESSAGES` ORDER BY `ID` DESC LIMIT 50";
			$res = $db->query($query);
			$sMessages = '';
			while($row = $res->fetch_assoc()){
				$sMessages .= '
				<div style="width:200px;word-break: break-all;"> '.$row['Author_Name'].': '.$row['Message'].' </div>';
			}
			$db->close();
			return $sMessages;
		}
	}
	
	$GLOBALS['Chat'] = new Chat();
	
?>