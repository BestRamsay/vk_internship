<?php

include_once 'connection_control.php';
include_once 'game_make.php';

define(WHITE, 1);
define(BLACK, 0);


interface manage_step {

	public function get_status($key);
	public function set_step($figure, $start, $finish);
	public function update_board();

}


class game_control implements manage_step
{
	public function get_status(){
		return view_status_game();
	}

	public function set_step($figure, $start, $finish){
 		return chech_valid($key_phrase);
	}

	public function update_board(){


	}

}



function view_status_game($key_phrase)
{
	return control_user($_COOKIE["hash"] , $_COOKIE["id"]);
}


function change_position($figure, $start, $finish)
{
	//[]проходим миллион проверок
	if (all_control()) {
		# code...
	}
	else {
		return 0;
	}
	//[]делаем ход через функции game_make.php

}

function all_control(){
	return control_user($_COOKIE["hash"] , $_COOKIE["id"]);
}



function control_user($hash , $id)
{
	if (control_valid_key_game($hash , NOT_NEED)) {

		$name_game = trim(control_valid_key_game($hash , NEED));
		$info = fopen("./game/$name_game/$name_game"."_info.txt", "c+b");
		$gamer_1 = trim(fgets($info));
		$gamer_2 = trim(fgets($info));
		$queue = fgets($info);
		fseek($info, 0);

		if ($gamer_1 == $id || $gamer_2 == $id) {
			if ($queue % 2 == BLACK && $gamer_1 == $id) {
				fclose($info);
				return 1;
			}elseif ($queue % 2 == WHITE && $gamer_2 == $id) {
				fclose($info);
				return 1;
			}
			
		}
	}else {
		return 0;
	}

}



?>