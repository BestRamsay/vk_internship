<?php
	
define(NEW_STR, "<br />");

define(NEW_GAME , "new");
define(EXIST , "exist");

define(NEED, 1);
define(NOT_NEED, 0);

define(HOUR, 3600);

define(MAX_SHIFT, 100);

define(WHITE, 1);
define(BLACK, -1);

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

function get_id()
{
	setcookie("id", rand(1, 200000), time() + HOUR);
}

function create_game($key_phrase){	
	
	static $shift = 0;
	$shift == MAX_SHIFT ? $shift = 0 : $shift++ ;
	$table = fopen("table_hash_game.txt", "ab");
	$game_name = time()+$shift;


		
	mkdir("./game/$game_name");	
	$info = fopen("./game/$game_name/"."$game_name"."_info.txt", "ab");
	
	fwrite($info, $_COOKIE["id"]."\n"."0"."\n");
	fwrite($table, md5($key_phrase)."\n".$game_name."\n");
	fclose($table);
	fclose($info);

}


function control_valid_key_game($key_phrase, $need_game_number){

	$table = fopen("table_hash_game.txt", "c+b");
	$found_hash = 0;
	$control_phrase = md5($key_phrase)."\n";

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


function control_end($name)
{
	//удалить записи из table_hash_game.txt и удалить папку ./game/$name
}

function add_gamer($key)
{
	$check_value = trim(control_valid_key_game($key,NEED));
	if (!($check_value == 0) ) {
		//echo "VALID".NEW_STR;
		$info = fopen("./game/$check_value/$check_value"."_info.txt", "c+b");
		$gamer_1 = fgets($info);
		$gamer_2 = fgets($info);
		//fclose($info);

		echo $_COOKIE["id"].NEW_STR;
		//setcookie("id", "" , time() - HOUR);
		if (!isset($_COOKIE["id"])){
			if ($gamer_2 == NO_SECOND_PLAYER){
				fseek($info, 0);
				fwrite($info, $gamer_1.$_COOKIE["id"]."\n".WHITE);
				echo $_COOKIE["id"].NEW_STR;
			}
			else {
				echo "There are already 2 players in the game".NEW_STR;
				echo $_COOKIE["id"].NEW_STR;
			}
		}
		else{
			if ($gamer_2 == NO_SECOND_PLAYER){
				fseek($info, 0);
				fwrite($info, $gamer_1.$_COOKIE["id"]."\n".WHITE);
			}
			else {
				echo "There are already 2 players in the game".NEW_STR;
			}

		}	
		fclose($info);
	}
	else{
		echo "INVALID";
	}
	//$gamer = rand(2001, 3000);
}

?>