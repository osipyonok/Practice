Меню
<br>
<a href="index.php">Головна</a><br>
<a href="doozer.php">Practice by Slava</a><br>
<?php
	if(empty($_SESSION['login'])){
		echo '<a href="signup.php">Реєстрація</a><br>';
	}else{
		echo '<a href="logout.php">Вихід</a><br>';
	}
?>
