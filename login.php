<?php
	if(empty($_SESSION['login'])){
	echo '
		<form action="authentication.php" method="post">
			<input name="login" type="text" placeholder="Логін або e-mail" size="25" maxlength="25"><br>
			<input name="password" type="password" placeholder="Пароль" size="25" maxlength="25"><br>
			<input type="submit" name="submit" value="Вхід">
		</form>';
	}else{
		echo "Ку, ".$_SESSION['login'];
	}
?>