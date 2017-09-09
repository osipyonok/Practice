<?php
//	session_start();
	
	define("DB_SERVER" , "localhost");
	define("DB_USER" , "1050541");
	define("DB_PASS" , "Dreamfoot1");
	define("DB_NAME" , "1050541");

	
	function connect(){
		$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		$db->set_charset("utf8");
		return $db;
	}
	function swap(&$a , &$b){
		$tmp = $a;
		$a = $b;
		$b = $tmp;
	}
	
	function fucking_strange_bug_killer(&$s){
		while(strlen($s) > 0 and  0 > ord($url{0})){
			$s = substr($s , 1);
		}
	}
	
	function fill_distances($id , $name){
		$db = connect();
		$query = "SELECT `ID`, `Name_EN` FROM `CITIES` WHERE 1 ORDER BY `ID` DESC";
		if($db->connect_error){
			die("Ошибка подключения к бд для заполнения расстояний.<br>");
		}
		
		$res = $db->query($query);
		$edges = array();
		$was = false;
		$insert_query = "INSERT INTO `DISTANCES`(`City_1`, `City_2`, `Distance`) VALUES ";
		while($row = $res->fetch_assoc()){
			$city_name = $row["Name_EN"];
			$u = $row["ID"];
			$v = $id;
			if($u > $v)swap($u , $v);
			$dist = get_distance($name , $city_name );
			if($was != false){
				$insert_query .= ", ";
			}else{
				$was = true;
			}
			$insert_query .= "(".$u." , ".$v." , ".$dist.") ";
		}
		$db->query($insert_query);
		$db->close();
	}
	
	function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

		$data = curl_exec($ch);
	//	echo curl_error($ch);
	//	echo '<br>';
		curl_close($ch);
		
		return $data;
	}

	function get_distance($city1 , $city2){
		$ch = curl_init();
		$url = "http://www.distance24.org/route.json?stops=".$city1."|".$city2;
		$json = file_get_contents($url);
		$arr = json_decode($json, true);
//		echo $arr["distance"].'<br>';
		return $arr["distance"];
	}
	
	function reg_prepare(&$s){
		$s = stripslashes($s);
		$s = htmlspecialchars($s);
		$s = trim($s);
	}
	
	function charhtmlcode($s){
		for($c = 65 ; $c <= 90 ; ++$c){
			$o = "&#" . (string)($c) . ";";
			$s = str_replace((string)chr($c) , $o , $s);
		}
		for($c = 97 ; $c <= 122 ; ++$c){
			$o = "&#" . (string)($c) . ";";
			$s = str_replace((string)chr($c) , $o , $s);
		}
		return $s;
	}
	
	function charhtmldecode($s){
		for($c = 65 ; $c <= 90 ; ++$c){
			$o = "&#" . (string)($c) . ";";
			$s = str_replace($o , (string)chr($c) , $s);
		}
		for($c = 97 ; $c <= 122 ; ++$c){
			$o = "&#" . (string)($c) . ";";
			$s = str_replace($o , (string)chr($c) , $s);
		}
		return $s;		
	}
	
	function parse($s){
		$db = connect();
		$query = "SELECT * FROM `EMOJIS` WHERE 1 ORDER BY LENGTH(`Emoji`) DESC";
		$res = $db->query($query);
		$s = substr($s , 0 , min(300 , strlen($s)));
		reg_prepare($s);
		while($row = $res->fetch_assoc()){
			$url = $row['Url'];
			$text = $row['Emoji'];
			$link = '<img src="'.$url.'" title="'.$text.'">';
			$link = charhtmlcode($link);
			$s = str_replace($text , $link , $s);
		}		
		$db->close();
		return charhtmldecode($s);
	}
	
	function get_weather($city){
		$url = 'http://api.openweathermap.org/data/2.5/weather?q='.$city."&appid=970f2d9814faf89bd143f8a7effb51fa&units=metric";
		$json = file_get_contents($url);
		$arr = json_decode($json, true);
		return array(
			'temp'=>$arr['main']['temp'] , 
			'weather'=>$arr['weather'][0]['main']
		); 
	}
	
	function get_city_name($id , $en = false){
		$lang = 'Name_';
		$lang .= ($en ? 'EN' : 'UA');
		$db = connect();
		$query = "SELECT `".$lang."` FROM `CITIES` WHERE `ID` = '".$id."' LIMIT 1";
		$res = $db->query($query);
		$ans = $res->fetch_assoc()[$lang];
		$db->close();
		return $ans;
	}
	
	function get_current_location(){
		$db = connect();
		$get_cur_version = "SELECT `Location` FROM `SYSTEM` WHERE 1 ORDER BY `Time` DESC LIMIT 1";
		$res = $db->query($get_cur_version);
		$ans = $res->fetch_assoc()['Location'];
		$db->close();
		return $ans;
	}	
	
	function exitlog($file){
		fwrite($file , "Аварійна зупинка регенерації.   ".date('m/d/Y h:i:s a', time())."\n");
		fclose($file);
		exit;
	}
	
	function sign($x){
		return x < 0 ? -1.0 : 1.0;
	}
	
?>