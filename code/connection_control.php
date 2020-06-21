<?php
	
define(NEW_STR, "<br />");
define(NEW_GAME , "new");
define(EXIST , "exist");
define(NEED, 1);
define(NOT_NEED, 0);

interface connection_user{

	public function control_start($status,$key);

}

class connection_control implements connection_user{
	
	public function control_start($status,$key){
		switch ($status) {
			case NEW_GAME:
				create_game($key);
				break;
			
			case EXIST:
				if (control_valid_key($key,NOT_NEED)) 
				{
					echo "VALID";
				}
				else
				{
					echo "INVALID";
				}
				break;
			
			default:
				echo "Please select a game type".NEW_STR;
				break;
		}
		
	}

}

function create_game($key_phrase){	
	$table = fopen("table_hash_game.txt", "ab");
	$game_name = time();
	mkdir("./game/$game_name");
	$info = fopen("./game/$game_name/"."$game_name"."_info.txt", "ab");
	fwrite($table, md5($key_phrase)."\n".$game_name."\n");
	fclose($table);
	fclose($info);
}


function control_valid_key_game($key_phrase, $need_game_number){

	$table = fopen("table_hash_game.txt", "rb");
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

?>