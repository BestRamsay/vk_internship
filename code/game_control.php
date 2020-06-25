<?php

include_once 'connection_control.php';
include_once 'game_make.php';
include_once 'defines_army.php';

define(WHITE, 0);
define(BLACK, 1);

define(ALIEN_STEP, 1);
define(MY_STEP, 0);

define(STUB, 0);
define(FIGURE, 0);
define(LETTER_START, 1);
define(DIGIT_START, 2);
define(LETTER_FINISH, 4);
define(DIGIT_FINISH, 5);
define(FIGURE, 0);

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

function change_my_board($launc_mode, $figure, $start, $finish)
{
	/*тут должны код для редактирования "доски"
	но пока сама доска еще не написано, так что будет стоять заглушка с echo "$alien_step".NEW_STR*/

	switch ($launc_mode) {
		case ALIEN_STEP:
			$change = parser_step(read_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED))));
			break;
		
		case MY_STEP:
			$string_step = $figure.$start."-".$finish;
			$change = parser_step($string_step);
			break;

	}

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

		change_my_board(ALIEN_STEP, STUB, STUB, STUB);
		if (all_control($figure, $point_A , $point_B))
		{
			change_my_board(MY_STEP, $figure, $point_A , $point_B);
			write_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED)), $figure.$point_A."-".$point_B."\n");
			echo "all correct fig: ".$figure." A: ".$point_A." B: ".$point_B.NEW_STR;
		}else
		echo "Incorrect step".NEW_STR;
	}else
	echo "You can't make a move".NEW_STR;
}

function parser_step($parse_string)
{
	$info = array("figure" => "0", "start" => array(0 , 0) , "finish" =>array(0 , 0));
	
	//parse figure
	switch ($parse_string[FIGURE]) {
		case KING:
			$info["figure"][0] = KING;		
			break;
		case QUEEN:
			$info["figure"][0] = QUEEN;
			break;
		case BISHOP:
			$info["figure"][0] = BISHOP;
			break;
		case CASTLE:
			$info["figure"][0] = CASTLE;
			break;
		case KNIGHT:
			$info["figure"][0] = KNIGHT;
			break;
		case PAWN:
			$info["figure"][0] = PAWN;
			break;
		
		default:
			echo "Invalid input figure".NEW_STR;
			return 0;
			break;
	}

	//parse letter start
	switch ($parse_string[LETTER_START]) {
		case "a":
			$info["start"][0] = 1;
			break;
		case "b":
			$info["start"][0] = 2;
			break;
		case "c":
			$info["start"][0] = 3;
			break;
		case "d":
			$info["start"][0] = 4;
			break;
		case "e":
			$info["start"][0] = 5;
			break;
		case "f":
			$info["start"][0] = 6;
			break;
		case "g":
			$info["start"][0] = 7;
			break;
		case "h":
			$info["start"][0] = 8;
			break;

		default:
			echo "Invalid input  letter".NEW_STR;
			return 0;
			break;
	}

	if (0 < $parse_string[DIGIT_START] && $parse_string[DIGIT_START] < 9) {
		$info["start"][1] = $parse_string[DIGIT_START];
	}else{
		echo "Invalid input digit".NEW_STR;
		return 0;
	}

	//parse letter finish
	switch ($parse_string[LETTER_FINISH]) {
		case "a":
			$info["finish"][0] = 1;
			break;
		case "b":
			$info["finish"][0] = 2;
			break;
		case "c":
			$info["finish"][0] = 3;
			break;
		case "d":
			$info["finish"][0] = 4;
			break;
		case "e":
			$info["finish"][0] = 5;
			break;
		case "f":
			$info["finish"][0] = 6;
			break;
		case "g":
			$info["finish"][0] = 7;
			break;
		case "h":
			$info["finish"][0] = 8;
			break;

		default:
			echo "Invalid input letter".NEW_STR;
			return 0;
			break;
	}

	if ( 0 < $parse_string[DIGIT_FINISH] && $parse_string[DIGIT_FINISH] < 9 ) {
		$info["finish"][1] = $parse_string[DIGIT_FINISH];
	}else{
		echo "Invalid input digit".NEW_STR;
		return 0;
	}

	return $info;
}

function make_board()
{
	return construct_board();
}



?>