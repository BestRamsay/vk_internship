<?php
	
define(NEW_STR, "<br />");
define(NEW_GAME , "new");
define(EXIST , "exist");

define(NEED, 1);
define(NOT_NEED, 0);
define(HOUR, 3600);
define(MAX_SHIFT, 100);
define(WHITE, 0);
define(NO_SECOND_PLAYER, 0);

interface connection_user{
	public function control_start($status,$key);
}

class connection_control implements connection_user{
	
	public function control_start($status,$key){
		switch ($status) {
			case FIRST_START:
				get_id();
				break;

			case NEW_GAME:
				create_game($key);
				break;
			
			case EXIST:
				add_gamer($key);
				break;
			
			default:
				echo "Please select a game type".NEW_STR;
				break;
		}	
	}
}

function get_id(){
	if(!isset($_COOKIE["id"])){
		setcookie("id", rand(1, 200000), time() + HOUR);
	}
}


function get_hash($key){
	setcookie("hash", md5($key) , time() + HOUR);
	if(!isset($_COOKIE["hash"]) || $_COOKIE["hash"] != md5($key)){
		header("Refresh: 0");
	}
}


function create_game($key_phrase){	
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
				#play with yourself
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
}


?>