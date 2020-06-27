<?

define(SHIFT_BYTE, 7);


include_once 'defines_army.php';

function read_step($game){
	$history_step = fopen("./game/$game/$game".".txt", "rb");
	$info = fopen("./game/$game/$game"."_info.txt", "rb");
	//go to the "queue"
	$queue = fgets($info);
	$queue = fgets($info);
	$queue = fgets($info);

	if ($queue == 0) {
		return 0;
	}
	if ($queue % 2 == 0){
		fseek($history_step, ((floor($queue / 2 ) + ceil($queue / 2 )-1 ) * SHIFT_BYTE));
	}
	else {
		fseek($history_step, ((floor($queue / 2) + ceil($queue / 2 ) -1) * SHIFT_BYTE));
	}
	$step = fgets($history_step);
	fclose($history_step);
	fclose($info);
	return $step;
}


function write_step($game, $step){
	$history_step = fopen("./game/$game/$game".".txt", "ab");
	$info = fopen("./game/$game/$game"."_info.txt", "r+b");
	$gamer_1 = trim(fgets($info));
	$gamer_2 = trim(fgets($info));
	$queue = trim(fgets($info));

	fseek($info, 0);
	fwrite($history_step, $step);
	$queue++;
	fwrite($info, $gamer_1."\n".$gamer_2."\n".$queue);
	fclose($history_step);
	fclose($info);
}

function construct_board()
{
	$original_board = array(array(0),
							array(0,WHITE_CASTLE, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_CASTLE), 
							array(0,WHITE_KNIGHT, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_KNIGHT),
							array(0,WHITE_BISHOP, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_BISHOP),
							array(0,WHITE_QUEEN, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_QUEEN),
							array(0,WHITE_KING, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_KING),
							array(0,WHITE_BISHOP, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_BISHOP),
							array(0,WHITE_KNIGHT, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_KNIGHT),
							array(0,WHITE_CASTLE, WHITE_PAWN, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, EMPTY_SQR, BLACK_PAWN, BLACK_CASTLE));

	return $original_board;
}


?>