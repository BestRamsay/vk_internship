<?php
	
include_once 'defines_army.php';


function get_id(){
		setcookie("id", rand(1, 200000), time() + HOUR_AND_HALF);
}


function get_hash($key){
	setcookie("hash", md5($key) , time() + HOUR_AND_HALF);
	if(!isset($_COOKIE["hash"]) || $_COOKIE["hash"] != md5($key)){
		header("Refresh: 0");
	}
}


function create_game($key_phrase){	
	if (!isset($_COOKIE["id"])){
		setcookie("id", rand(1, 200000), time() + HOUR_AND_HALF);
		header("Refresh: 0");
	}else{
		get_hash($key_phrase);
		$game_name = time();

		if (!file_exists("./game/$game_name")  && !control_valid_key_game(md5($key_phrase),NOT_NEED) ){
			$shift == MAX_SHIFT ? $shift = 0 : $shift++ ;
			$table = fopen("table_hash_game.txt", "ab");
			
			mkdir("./game/$game_name");

			$info = fopen("./game/$game_name/"."$game_name"."_info.txt", "ab");
			$game = fopen("./game/$game_name/"."$game_name".".txt", "w");
			fwrite($info, $_COOKIE["id"]."\n"."0"."\n".WHITE);
			fwrite($table, md5($key_phrase)."\n".$game_name."\n");
			fclose($table);
			fclose($info);
			fclose($game);
		}
	}
}


function control_valid_key_game($hash, $need_game_number){

	$table = fopen("table_hash_game.txt", "c+b");
	$found_hash = 0;
	$control_phrase = $hash."\n";

	if ($need_game_number == NEED){
		$found_game = 0;

		do {
			$found_hash = fgets($table);
			$found_game = fgets($table);

			if (($found_hash == $control_phrase)) {
			fclose($table);
			return $found_game;
			break;
			}
		} while (!feof($table));
		fclose($table);
		return 0;
	}
	else{
		
		do {
			$found_hash = fgets($table);

			if (($found_hash == $control_phrase)) {
				fclose($table);
				return true;
				break;
			}
		} while (!feof($table));
		fclose($table);
		return 0;
	}	
	
}



function add_gamer($key){
	if (!isset($_COOKIE["id"])){
		setcookie("id", rand(1, 200000), time() + HOUR_AND_HALF);
		header("Refresh: 0");
	}
	get_hash($key);
	$check_value = trim(control_valid_key_game(md5($key),NEED));
	if (!($check_value == 0) ) {

		$info = fopen("./game/$check_value/$check_value"."_info.txt", "c+b");
		$gamer_1 = fgets($info);
		$gamer_2 = fgets($info);
		$queue = fgets($info);
		fseek($info, 0);

		if (!isset($_COOKIE["id"])){
			if ($gamer_2 == NO_SECOND_PLAYER){
				fwrite($info, $gamer_1.$_COOKIE["id"]."\n".$queue);
			}
			else {
				echo "There are already 2 players in the game".NEW_STR;
			}
		}
		else{
			if ($gamer_2 == NO_SECOND_PLAYER){
				fwrite($info, $gamer_1.$_COOKIE["id"]."\n".$queue);
			}
			else {
				echo "There are already 2 players in the game".NEW_STR;
			}

		}	
		fclose($info);
	}
	else{
		echo "INVALID KEY";
	}

}


function control_end($name){
	if (file_exists("./game/$name")) {
		$game = fopen("./game/$name/$name".".txt", "rb");
		while (!feof($game)) {
			echo fgets($game);
		}
		fclose($game);

	    $file = @file("table_hash_game.txt");
	    $num = 1; 
	    while (!($file[$num] == "$name"."\n")) {
	    	$num++;
	    }
	    unset($file[$num-1]);
	    unset($file[$num]);
	    $fileOpen = @fopen("table_hash_game.txt","w"); 
	    fputs($fileOpen,implode("",$file)); 
	    fclose($fileOpen);

	    unlink("./game/$name/$name"."_info.txt");
		unlink("./game/$name/$name".".txt");
		rmdir("./game/$name");
	}else
	echo "Game not exist".NEW_STR;
}


?>