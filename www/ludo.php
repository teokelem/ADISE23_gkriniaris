<?php

//ludo.php epikoinonia me ton server

// check me curl
print_r("HELLO HERE!!!!!!");
require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php"; 
require_once "../lib/users.php";
print_r("HELLO HERE!!!!!!");

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
// $request = explode('/', trim($_SERVER['SCRIPT_NAME'],'/'));
// Σε περίπτωση που τρέχουμε php –S 
$input = json_decode(file_get_contents('php://input'),true);



switch ($r=array_shift($request)) {
    case 'board' :
	switch ($b=array_shift($request)) {
		case '': 
		case null: handle_board($method);break;
 	case 'piece': handle_piece($method, $request[0],$request[1],$input);
 				break;
	// case 'player': handle_player($method, $request[0],$input);
					//break;
					default: header("HTTP/1.1 404 Not Found");
                            break;
			
 
	}
		break;
	case 'status': 
		if(sizeof($request)==0) {handle_status($method);}
		else {header("HTTP/1.1 404 Not Found");}
		break;
 	case 'players': handle_player($method, $request,$input);
  			break;
	case 'delete_players': handle_delete_players($method);
	break;

   default: 	
	header("HTTP/1.1 404 Not Found");
    print_r("NOT FOUND");
	exit;
}

function handle_delete_players($method) {
    if($method=='GET') {
		header('HTTP/1.1 405 Method Not Allowed');
    } else if ($method=='POST') {
		reset_players();
    }  else {header('HTTP/1.1 405 Method Not Allowed');}
    
}

function handle_board($method) {
    if($method=='GET') {
            show_board();
    } else if ($method=='POST') {
		reset_board();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
}

function handle_piece($method, $x,$y,$input) {
	if($method=='GET') {
		show_piece($x,$y);
	} else if ($method=='PUT') {
		move_piece($x,$y,$input['x'],$input['y'],  
				   $input['token']);
	}       
}

function handle_player($method, $p,$input) {
	 
 // if ($method=='POST') {
//	reset_players();}
 
    switch ($b=array_shift($p)) {

	 	
    case 'R': handle_user($method, $b,$input);break;  
    case 'G': handle_user($method, $b,$input);break; 
        case 'B': handle_user($method, $b,$input);break; 
		case 'Y': handle_user($method, $b,$input);break;
		default: header("HTTP/1.1 404 Not Found");
				 print json_encode(['errormesg'=>"Player $b not found."]);
                 break;
	}
}

function handle_status($method) {
    if($method=='GET') {
        show_status();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
}
?>