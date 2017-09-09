<?php
	ignore_user_abort(true);
	include 'functions.php';
	if($_REQUEST['act'] != 'do')exit;
	date_default_timezone_set('Europe/Kiev');
	
	$logfile = fopen("log.txt" , 'a');
	fwrite($logfile , "Початок регенерації!   ".date('m/d/Y h:i:s a', time())."\n");
	$db = connect();
	
	$get_cities = "SELECT * FROM `CITIES` WHERE EXISTS (SELECT 1 FROM `DISTANCES` WHERE `City_1` = `CITIES`.`ID` OR `City_2` = `CITIES`.`ID`)";
	$res = $db->query($get_cities);
	if($res->num_rows < 1)exitlog($logfile);
	$cities = array();
	while($row = $res->fetch_assoc()){
		$cities[$row['ID']] = array(
			'Name_EN'=>$row['Name_EN'],
			'Name_UA'=>$row['Name_UA']
		);
	}
	
	fwrite($logfile , "Міста в БД:\n");
	fwrite($logfile , print_r($cities , true));
	
	$get_dist = "SELECT * FROM  `DISTANCES` WHERE EXISTS (SELECT 1 FROM  `CITIES` WHERE  `CITIES`.`ID` =  `DISTANCES`.`City_1` OR  `CITIES`.`ID` =  `DISTANCES`.`City_2`)";
	$res = $db->query($get_dist);
	if($res->num_rows < 1)exitlog($logfile);
	$dist = array();
	while($row = $res->fetch_assoc()){
		$i = (int)$row['City_1'];
		$j = (int)$row['City_2'];
		$d = (double)$row['Distance'];
		$dist[$i][$j] = $d;
		$dist[$j][$i] = $d;
	}
	
	fwrite($logfile , "Відстані в БД:\n");
	fwrite($logfile , print_r($dist , true));
	
	$get_weathers = "SELECT * FROM `WEATHERS` WHERE 1";
	$res = $db->query($get_weathers);
	if($res->num_rows < 1)exitlog($logfile);
	$weathers = array();
	while($row = $res->fetch_assoc()){
		$weathers[$row['ID']] = $row['Name'];
	}
	
	fwrite($logfile , "Типи погоди в БД:\n");
	fwrite($logfile , print_r($weathers , true));
	
	$get_w_func = "SELECT * FROM  `WEATHER_FUNCTION` WHERE 1";
	$res = $db->query($get_w_func);
	if($res->num_rows < 1)exitlog($logfile);
	$weather_func = array();
	while($row = $res->fetch_assoc()){
		$weather_func[$row['Weather_1']][$row['Weather_2']] = $row['Value'];
	}

	fwrite($logfile , "Співвідношення між типами погоди в БД:\n");
	fwrite($logfile , print_r($weather_func , true));

	$get_params = "SELECT * FROM `STATIC_PARAMS` WHERE 1 LIMIT 1";
	$res = $db->query($get_params);
	if($res->num_rows != 1)exitlog($logfile);
	$params = array();
	if($row = $res->fetch_assoc()){
		$params = array(
			'dist_coefficient'=>$row['dist_coefficient'],
			'f_temperature'=>$row['f_temperature'],
			'f_weather'=>$row['f_weather']
		);
	}
	
	fwrite($logfile , "Параметри системи:\n");
	fwrite($logfile , print_r($params , true));
	
	$get_cur_version = "SELECT `Location` FROM `SYSTEM` WHERE 1 ORDER BY `Time` DESC LIMIT 1";
	$res = $db->query($get_cur_version);
	if($res->num_rows != 1)exitlog($logfile);
	$location = $res->fetch_assoc()['Location'];
	$location_name = $cities[$location]['Name_EN'];
	$location_weather = get_weather($location_name);
	$location_w_idx = 0;
	foreach($weathers as $i=>$weather){
		if($location_weather['weather'] == $weathers[$i]){
			$location_w_idx = $i;
			break;
		}
	}
	
	if($location_w_idx == 0)exitlog($logfile);
	
	fwrite($logfile , "Система зараз проживає в ".$location_name.".\n");
	fwrite($logfile , "Погода в ".$location_name.":");
	fwrite($logfile , print_r($location_weather , true));
	
	$f = array();
	
	$min_val = 1e9;
	$min_idx = 0;
	
//	for($i = 1 ; $i <= count($cities) ; ++$i){
	foreach($cities as $i=>$city){
		$i_weather = get_weather($cities[$i]['Name_EN']);
		$i_weather_idx = 0;
		foreach($weathers as $j=>$weather){
	//	for($j = 1 ; $j <= count($weathers) ; ++$j){
			if($i_weather['weather'] == $weathers[$j]){
				$i_weather_idx = $j;
				break;
			}
		}
		fwrite($logfile , "Погода в місті ".$cities[$i]['Name_EN']."\n");
		fwrite($logfile , print_r($i_weather , true));
		if($i_weather_idx == 0){
			$f[$i] = 1e9;
			$todo = fopen("todo_weather.txt" , 'r');
			$ck = false;
			while($str = fgets($todo) != false){
				$cur = (strpos($str , $i_weather['weather']) == false);
				$ck = ($ck or $cur); 
			}
			fclose($todo);
			if($ck == false){
				$todo = fopen("todo_weather.txt" , 'a');
				fwrite($todo , $i_weather['weather']."\n");
				fclose($todo);
			}
			continue;
		}
		
		//
		// f[i] = a_d * log2(1 + dist)^2 + (t_f - t_i)^2 + sgn(w(w_i , w_f))*w(w_i , w_f)^2
		// a_d - distance coeff from db
		// dist - distance between i-th and current cities
		// t_f - favourite temperature
		// t_i - temperature in i-th city
		// sgn - sign of number
		// w - weather function (defined in db)
		// w_i - weather in i-th city
		// w_f - favourite weather
		//

		$f[$i] = 1.0 * $params['dist_coefficient'] * log(1.0 + $dist[$i][$location] , 2.0) + 1.0 * pow($params['f_temperature'] - $i_weather['temp'] , 2.0) + sign($weather_func[$params['f_weather']][$i_weather_idx]) * pow($weather_func[$params['f_weather']][$i_weather_idx] , 2.0);
		if($f[$i] < $min_val){
			$min_val = $f[$i];
			$min_idx = $i;
		}
	} 
	
	fwrite($logfile , "Вартість міст:");
	fwrite($logfile , print_r($f , true));
	
	if($min_idx == 0)exitlog($logfile);
	if($min_idx == $location){
		fwrite($logfile , "Система вирішила залишитися в місті ".$location_name.".\n");
	}else{
		fwrite($logfile , "Система вирішила переїхати до міста ".$cities[$min_idx]['Name_EN'].".\n");
		$regenerate = "INSERT INTO `SYSTEM`(`Location`) VALUES (".$min_idx.")";
		$db->query($regenerate);
	}
	
	fwrite($logfile , "Регенерацію системи успішно завершено!    ".date('m/d/Y h:i:s a', time())."\n\n\n\n");
	fclose($logfile);
	
	$db->close();
	
	printf("Mischief managed");
	exit(0);
?>