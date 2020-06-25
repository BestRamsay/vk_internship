<?php

include_once 'connection_control.php';
include_once 'game_make.php';
include_once 'defines_army.php';

define(WHITE, 0);
define(BLACK, 1);


interface manage_step {

	public function get_status();
	public function set_step($figure, $start, $finish);

}


class game_control implements manage_step
{
	public function get_status(){
		view_status_game($_COOKIE["hash"]);
	}

	public function set_step($figure, $start, $finish){
 		make_step($figure, $start, $finish);
	}

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
			if ($queue % 2 == WHITE && $gamer_1 == $id) {
				fclose($info);
				return 1;
			}elseif ($queue % 2 == BLACK && $gamer_2 == $id) {
				fclose($info);
				return 1;
			}
			
		}
	}else {
		return 0;
	}

}


function all_control($figure, $point_A , $point_B){

	return 1;
//здесь весь контроль по корректности хода и тд

//control_user отдельный вид котроля и находится обособленно
//и если не валидный пользователь, то другие проверки попросту не нужны
}


function view_status_game($hash)
{
	if (control_valid_key_game($hash , NOT_NEED)) 
	{
		$name_game = trim(control_valid_key_game($hash , NEED));
		$info = fopen("./game/$name_game/$name_game"."_info.txt", "rb");
		echo "White ID: ".fgets($info).NEW_STR;
		echo "Black ID: ".fgets($info).NEW_STR;
		$queue = fgets($info);
		
		if ($queue % 2 == WHITE) 
			echo "Queue: WHITE".NEW_STR;
		else 
			echo "Queue: BLACK".NEW_STR;

		fclose($info);
	} else
	echo "invalid key".NEW_STR;
}



function update_board()
{
	//echo read_step(control_valid_key_game($_COOKIE["hash"] , NEED)).NEW_STR;
	return read_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED)));
}

function change_my_board($alien_step)
{
	/*тут должны код для редактирования "доски"
	но пока сама доска еще не написано, так что будет стоять заглушка с echo "$alien_step".NEW_STR*/

	echo "$alien_step".NEW_STR;
}


function make_step($figure, $point_A , $point_B)
{

	if (control_user($_COOKIE["hash"], $_COOKIE["id"]))
	{
		if(!isset($board)){
			static $board;
			$board = make_board();
			echo "create new board".NEW_STR;
		}else
			echo "board exist".NEW_STR;
		
		change_my_board(trim(update_board()));
		if (all_control($figure, $point_A , $point_B))
		{
			write_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED)), "$figure"."$point_A"."-"."$point_B"."\n");
			echo "all correct fig: ".$figure." A: ".$point_A." B: ".$point_B.NEW_STR;
		}else
		echo "Incorrect step".NEW_STR;
	}else
	echo "You can't make a move".NEW_STR;
}

function parser_step($step , $parse_step)
{
	$parse_step = array("figure" => 0, "letter" => 0 , "numeral" =>0);
	$parse_step["figure"] = $step;
	
	return $parse_step;
}

function make_board()
{
	return construct_board();
}


?>