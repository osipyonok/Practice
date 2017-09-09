<?php
	session_start();
	include 'functions.php';
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>TK Practice</title>
		<link rel="stylesheet" type="text/css" href="css\style.css">
		<?php 
			if(!empty($_SESSION['login'])){
				echo '
					<script>
						window.onload = function() {
							$("#chat_form").submit(Send);
						}
						
						function Send() {
							$.post("apply_mess.php",
							{
								act: "send",
								id: "'.$_SESSION['id'].'",
								name: "'.$_SESSION['login'].'",
								text: $("#mess").val()
							}); 

							$("#mess").val("");
							$("#mess").focus();
							return false;
						}
						
						function Get_Emoji_List(){
							var div = document.getElementById(\'overlay\');
							if(div.style.display == \'block\'){
								div.style.display = \'none\';
							}else{
								div.style.display = \'block\';
							}
						}
						
						function append(emoji){
							document.getElementById(\'mess\').value = document.getElementById(\'mess\').value + \' \' + emoji;
						}
					</script>';
			}
		?>
		<style>
			#overlay {
				position: fixed;
				display: none;
    			width: 100%;
    			height: 100%;
    			top: 0;
    			left: 0;
    			right: 0;
    			bottom: 0;
    			z-index: 2;
    			cursor: pointer;
			}

			#text{
				background: #C4C4C4;
    			position: absolute;
    			top: 50%;
    			left: 50%;
    			font-size: 50px;
    			color: white;
    			transform: translate(-50%,-50%);
    			-ms-transform: translate(-50%,-50%);
			}
		</style>
	</head>
	<body>
		<table cellpadding="0" cellspacing="0" width="100%" align="center">
			<tr>
				<td colspan="3" class="header">Практика</td>
			</tr>
			<tr>
				<td class="left_col">
					<? include('menu.php'); ?>
				</td>
				<td class="center_col">			
					<form action = "save_user.php" method="post">
						<label>Логін: </label>
						<input name="login" type="text" size="25" maxlength="25">
						<br>
						<label>Пароль: </label>
						<input name="password" type="password" size="25" maxlength="25">
						<br>
						<label>E-mail: </label>
						<input name="mail" type="email" size="25" maxlength="25">
						<br>
						<input type="submit" name="submit" value="Зареєструватися">						
					</form>
				</td>
				<td class="right_col">Чат
				
					<? include('chat.php'); ?>

				</td>
			</tr>
			<tr>
				<td colspan="3" class="footer">&copy; 2017</td>
			</tr>
		</table>
	</body>
</html>