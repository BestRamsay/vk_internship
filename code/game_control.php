<?php

include_once 'connection_control.php';

interface manage_step {

	public function get_status($key);
	public function set_step($figure, $start, $finish);

}


class game_control implements manage_step
{
	public function get_status($key){
		return view_status_game($key);
	}

	public function set_step($figure, $start, $finish){

	}

}


function view_status_game($key_phrase)
{
	return control_valid_key($key_phrase, NEED);
}


function create_board($game_number)
{
	//проверка на существование папки game_number
	//создание доски
}

function change_position($figure, $start, $finish)
{
	//вызываем create_board()
	//проходим миллион проверок
	//делаем ход через функции game_make.php

}


?>