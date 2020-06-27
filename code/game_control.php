<?php

include_once 'connection_control.php';
include_once 'game_make.php';
include_once 'defines_army.php';



function control_user($hash , $id)
{
	if (control_valid_key_game($hash , NOT_NEED)) {
		$name_game = trim(control_valid_key_game($hash , NEED));
		$info = fopen("./game/$name_game/$name_game"."_info.txt", "c+b");
		$gamer_1 = trim(fgets($info));
		$gamer_2 = trim(fgets($info));
		$queue = fgets($info);
		fseek($info, 0);

		if ($gamer_1 == $gamer_2){
			fclose($info);
			return SINGLE_PLAYER;
		}

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


function all_control($figure, $point_A , $point_B, $board){

	$step = parser_step($figure.$point_A."-".$point_B);
	return control_step($step, $board) &&
		   control_exist_figure($step, $board) &&
		   control_through_figure($step, $board) && 
		   control_final_point($step, $board) ; 
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
	$data_change = parser_step(read_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED))));
	return $data_change;
}




function go_step($figure, $point_A , $point_B)
{
	if (control_user($_COOKIE["hash"], $_COOKIE["id"]))
	{
		$change = update_board();
		static $board;

		if (!isset($board)) {
			$board = make_board();	
		}
		
		
		if (!(control_user($_COOKIE["hash"], $_COOKIE["id"]) == 2 || read_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED))) == "")){

				$board[$change["finish"][0]][$change["finish"][1]] = $board[$change["start"][0]][$change["start"][1]];
				$board[$change["start"][0]][$change["start"][1]] = EMPTY_SQR;
		}

		
	if (all_control($figure, $point_A , $point_B, $board))
		{
				$change = parser_step($figure.$point_A."-".$point_B);
				write_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED)), $figure.$point_A."-".$point_B."\n");
				
				$board[$change["finish"][0]][$change["finish"][1]] = $board[$change["start"][0]][$change["start"][1]];
				$board[$change["start"][0]][$change["start"][1]] = EMPTY_SQR;
				
				echo "all correct. Figure: ".$figure." point_A: ".$point_A." point_B: ".$point_B.NEW_STR.NEW_STR;
				
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
			
			if (!read_step(trim(control_valid_key_game($_COOKIE["hash"] , NEED))) == "")
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
			echo "Invalid input letter".NEW_STR;
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

function get_info($variant)
{
	$name_game = trim(control_valid_key_game($_COOKIE["hash"] , NEED));
	$info = fopen("./game/$name_game/$name_game"."_info.txt", "c+b");
	$gamer_1 = trim(fgets($info));
	$gamer_2 = trim(fgets($info));
	$queue = trim(fgets($info));


	switch ($variant) {
		case FIRST_GAMER:
			fclose($info);
			return $gamer_1;
			break;
		
		case SECOND_GAMER:
			fclose($info);
			return $gamer_2;
			break;
		
		case QUEUE:
			fclose($info);
			return $queue % 2;
			break;
		
	}
}


function control_step($step, $board)
{
	switch ($step["figure"][0]) {
		case KING:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = abs($step["start"][1] -$step["finish"][1]);

			if ($dif_digit < 2 && $dif_letter < 2 ){
				vector_special_situation($step["start"][0], $step["start"][1] , NOT_NEED);
				return SUCCESS;
			}else
				return FAIL;

			break;

		case QUEEN:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = abs($step["start"][1] -$step["finish"][1]);

			if ($dif_letter == $dif_digit) {
				return SUCCESS;
			}elseif ($dif_letter > 0 && $dif_digit == 0) {
				return SUCCESS;
			}elseif ($dif_letter == 0 && $dif_digit > 0) {
				return SUCCESS;
			}else
				return FAIL;
			break;

		case BISHOP:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = abs($step["start"][1] -$step["finish"][1]);

			if($dif_letter == $dif_digit)
				return SUCCESS;
			else
				return FAIL;
			break;

		case CASTLE:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = abs($step["start"][1] -$step["finish"][1]);

			if ($dif_letter > 0 && $dif_digit == 0) {
				vector_special_situation($step["start"][0], $step["start"][1], NOT_NEED);
				return SUCCESS;
			}elseif ($dif_letter == 0 && $dif_digit > 0) {
				vector_special_situation($step["start"][0], $step["start"][1] , NOT_NEED);
				return SUCCESS;
			}else
				return FAIL;
			break;

		case KNIGHT:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = abs($step["start"][1] -$step["finish"][1]);			
			
			if ($dif_letter == 2 && $dif_digit == 1) {
				return SUCCESS;
			}elseif ($dif_letter == 1 && $dif_digit == 2) {
				return SUCCESS;
			}else
				return FAIL; 
			break;

		case PAWN:
			$dif_letter = abs($step["start"][0] - $step["finish"][0]);
			$dif_digit = $step["finish"][1] -$step["start"][1];

			switch (get_info(QUEUE)) {
				case WHITE:
					if((($step["start"][1] == 2 && $dif_digit == 2) || $dif_digit == 1) && $dif_letter == 0)
						return SUCCESS;
					elseif ($dif_letter = 1 && $dif_digit = 1) {
						
						switch ($board[$step["finish"][0]][$step["finish"][1]]) {
							case BLACK_QUEEN:
								return SUCCESS;
								break;
							case BLACK_CASTLE:
								return SUCCESS;
								break;
							case BLACK_KNIGHT:
								return SUCCESS;
								break;
							case BLACK_BISHOP:
								return SUCCESS;
								break;
							case BLACK_PAWN:
								return SUCCESS;
								break;
							case BLACK_KING:
								echo "You can't cut a king".NEW_STR;
								return FAIL;
								break;
						}	

					}else
						return FAIL;

					break;
				
				case BLACK:

					if((($step["start"][1] == 7 && $dif_digit == -2) || $dif_digit == -1) && $dif_letter == 0)
							return SUCCESS;
					elseif ($dif_letter = 1 && $dif_digit = -1) {
						switch ($board[$step["finish"][0]][$step["finish"][1]]) {
							case WHITE_QUEEN:
								return SUCCESS;
								break;
							case WHITE_CASTLE:
								return SUCCESS;
								break;
							case WHITE_KNIGHT:
								return SUCCESS;
								break;
							case WHITE_BISHOP:
								return SUCCESS;
								break;
							case WHITE_PAWN:
								return SUCCESS;
								break;
							case WHITE_KING:
								echo "You can't cut a king".NEW_STR;
								return FAIL;
								break;
						}	
					}else
						return FAIL;
						
					break;
			}


			break;
	}
}

function control_exist_figure($step, $board)
{
	switch (get_info(QUEUE)) {
			case WHITE:
				if ($board[$step["start"][0]][$step["start"][1]] == "w".$step["figure"][0]) {
					return SUCCESS;
				}else
					echo "Empty square".NEW_STR;
					return FAIL;
				break;
			
			case BLACK:
				if ($board[$step["start"][0]][$step["start"][1]] == "b".$step["figure"][0]) {
					return SUCCESS;
				}else
					echo "Empty square".NEW_STR;
					return FAIL;
				break;
		}	
}

function control_through_figure($step, $board)
{
	$dif_letter = $step["finish"][0] - $step["start"][0];
	$dif_digit = $step["finish"][1] -$step["start"][1];

	switch ($step["figure"][0]) {
		case QUEEN:
			if($dif_letter > 0 && $dif_digit > 0) {
				for ($count=1; $count < $dif_letter - 1 ; $count++) { 
					if ($board[$step["start"][0]+$count][$step["start"][1]+$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter > 0 && $dif_digit == 0) {
				for ($count=1; $count < $dif_letter - 1 ; $count++) { 
					if ($board[$step["start"][0]+$count][$step["start"][1]] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter > 0 && $dif_digit < 0) { 
				for ($count=1; $count < $dif_letter - 1 ; $count++) { 
					if ($board[$step["start"][0]+$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter == 0 && $dif_digit > 0) { 
				for ($count=1; $count < $dif_digit - 1 ; $count++) { 
						if ($board[$step["start"][0]][$step["start"][1]+$count] == EMPTY_SQR)
							return SUCCESS;
						else {
							echo "A figure stands in the way".NEW_STR;
							return FAIL;
						}
					}	

			}elseif ($dif_letter == 0 && $dif_digit < 0) { 
				for ($count=1; $count < abs($dif_digit) - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit > 0) { 
				for ($count=1; $count < $dif_digit - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]+$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit == 0) { 
				for ($count=1; $count < abs($dif_letter) - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit < 0) { 
				for ($count=1; $count < abs($dif_digit - 1) ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}
			}else 
				return SUCCESS;

			break;

		case BISHOP:
			if($dif_letter > 0 && $dif_digit > 0) {
				for ($count=1; $count < $dif_letter - 1 ; $count++) {
					if ($board[$step["start"][0]+$count][$step["start"][1]+$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}
			}elseif ($dif_letter > 0 && $dif_digit < 0) { 
				for ($count=1; $count < $dif_letter - 1 ; $count++) { 
					if ($board[$step["start"][0]+$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit > 0) { 
				for ($count=1; $count < $dif_digit - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]+$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit < 0) { 
				for ($count=1; $count < abs($dif_digit - 1) ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}
			}else 
				return SUCCESS;


			break;

		case CASTLE:
			if ($dif_letter > 0 && $dif_digit == 0) {
				for ($count=1; $count < $dif_letter - 1 ; $count++) { 
					if ($board[$step["start"][0]+$count][$step["start"][1]] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter == 0 && $dif_digit > 0) {
				for ($count=1; $count < $dif_digit - 1 ; $count++) { 
						if ($board[$step["start"][0]][$step["start"][1]+$count] == EMPTY_SQR)
							return SUCCESS;
						else {
							echo "A figure stands in the way".NEW_STR;
							return FAIL;
						}
					}	

			}elseif ($dif_letter == 0 && $dif_digit < 0) { 
				for ($count=1; $count < abs($dif_digit) - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]-$count] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}elseif ($dif_letter < 0 && $dif_digit == 0) { 
				for ($count=1; $count < abs($dif_letter) - 1 ; $count++) { 
					if ($board[$step["start"][0]-$count][$step["start"][1]] == EMPTY_SQR)
						return SUCCESS;
					else {
						echo "A figure stands in the way".NEW_STR;
						return FAIL;
					}
				}

			}else
				return SUCCESS;


			break;
	
	}
	return SUCCESS;
}

function control_final_point($step, $board)
{
	switch (get_info(QUEUE)) {
		case WHITE:
			if ($board[$step["finish"][0]][$step["finish"][1]] == WHITE_KING || 
				$board[$step["finish"][0]][$step["finish"][1]] == WHITE_PAWN || 
				$board[$step["finish"][0]][$step["finish"][1]] == WHITE_BISHOP || 
				$board[$step["finish"][0]][$step["finish"][1]] == WHITE_KNIGHT || 
				$board[$step["finish"][0]][$step["finish"][1]] == WHITE_CASTLE || 
				$board[$step["finish"][0]][$step["finish"][1]] == WHITE_QUEEN ) {
					echo "You can not step on your figures".NEW_STR;	
					return FAIL;
				}else{

					return SUCCESS;
				}

			break;
		
		case BLACK:
			if ($board[$step["finish"][0]][$step["finish"][1]] == BLACK_KING || 
				$board[$step["finish"][0]][$step["finish"][1]] == BLACK_PAWN || 
				$board[$step["finish"][0]][$step["finish"][1]] == BLACK_BISHOP || 
				$board[$step["finish"][0]][$step["finish"][1]] == BLACK_KNIGHT || 
				$board[$step["finish"][0]][$step["finish"][1]] == BLACK_CASTLE || 
				$board[$step["finish"][0]][$step["finish"][1]] == BLACK_QUEEN) {
					echo "You can not step on your figures".NEW_STR;	
					return FAIL;
				}else
					return SUCCESS;
			break;
	}

}



function vector_special_situation($letter, $digit, $need)
{
	static $vector = 0; 
	$vector = array("wk" =>"0", "bk" => "0", "wc_a1" => "0", "bc_a8" => "0", "wc_h1" => "0", "bc_h8" => "0" );

	switch ($letter) {
		case "e":
			switch ($digit) {
					case "1":
						$vector["wk"] = "1";
						break;
					
					
					case "8":
						$vector["bk"] = "1";
						break;	
					}
					
			break;
		
		case "a":
			switch ($digit) {
					case "1":
						$vector["wc_a1"] = "1";
						break;
					
					
					case "8":
						$vector["bc_a8"] = "1";
						break;	
					}
			break;

		case "h":
			switch ($digit) {
					case "1":
						$vector["wc_h1"] = "1";
						break;
					
					
					case "8":
						$vector["bc_h8"] = "1";
						break;	
					}
			break;
	switch ($need) {
		case NEED:
			return $vector;
			break;
		
		case NOT_NEED:
			break;
	}
	}
}

?>