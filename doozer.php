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
					Інша версія системи by Слава (оновлення кожні 5 хвилин):
					<br>
					<?php
						$doozers = array('http://tk2017euro.apphb.com', 'http://tk2017usa.apphb.com');
						$db = connect();
						foreach($doozers as $page){
							$query = "SELECT `HTTP_STATUS` FROM FUN WHERE `URL`='".$page."' ORDER BY `TIME` DESC LIMIT 1";
							$res = $db->query($query);
							$status = (int)$res->fetch_assoc()['HTTP_STATUS'];
							$color = "red"; 
							if($status / 100 == 2)$color = "green";
							echo "<a href='".$page."' target='_blank'>".$page."</a> - HTTP STATUS: ".$status." <span style='color:".$color."; font-size: 15pt;'>●</span><br>";
						}
					?>
					
					
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