<?php 
	class Registrator{
		private $url = "http://practice-5.apphb.com/Identity/SignUp";
		
		public function __construct(){
			srand();
		}
		
		private function getUsername(){
			return "Your_chat_does_not_work" . (string)rand(1 , 1e9);
		}
		
		private function getPassword(){
			return "password" . (string)rand(1 , 1e9);
		}
		
		private function signUp(){
			$ch = curl_init();
			curl_setopt($ch , CURLOPT_URL , $url);
			curl_setopt($ch , CURLOPT_REFERER , $url);
			curl_setopt($ch , CURLOPT_RETURNTRANSFER , 1);
			curl_setopt($ch , CURLOPT_FOLLOWLOCATION , 1);
			curl_setopt($ch , CURLOPT_CONNECTTIMEOUT , 30);
			curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false);
			curl_setopt($ch , CURLOPT_HEADER , 1);
			curl_setopt($ch , CURLOPT_POST , 1);
			$login = $this->getUsername();
			$pass = $this->getPassword();
			$email = $login + "@gmail.com";
			curl_setopt($ch , CURLOPT_POSTFIELDS , array(
				'login'=>$login,
				'password'=>$pass,
				'email'=>$email
			));
			$data = curl_exec($ch);
			curl_close($ch);
			return array(
				'login'=>$login,
				'password'=>$pass
			);
		}
		
		public function doYourBadThings($n){
			for($i = 0 ; $i < $n ; ++$i){
				$res = $this->signUp();
				print_r($res);
				printf("<br>");
			}
		}
	}
	
	$o = new Registrator();
	
	$o->doYourBadThings(200);
	
	
	echo "<br>Шалость удалась!";
?>