<?php

include_once 'connection_control.php';
include_once 'game_control.php';
include_once 'game_make.php';



interface game_API{
	public function start_game($status,$key);
	public function status_game();
	public function make_step($figure, $start, $finish);
	public function give_up($consent);
}

class API implements game_API{
	
	public function start_game($status,$key){
		switch ($status) {
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

	public function status_game(){
		view_status_game($_COOKIE["hash"]);
	}

	public function make_step($figure, $start, $finish){
 		go_step($figure, $start, $finish);
	}

	public function give_up($consent)
	{
		if ($consent == OFFER)
			control_end(trim(control_valid_key_game($_COOKIE["hash"] , NEED)));
		else 
			echo "Invalid call".NEW_STR;
	}

}


?>