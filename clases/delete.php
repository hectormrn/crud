<?php 
/*
@author Hector Mrn
*/
require_once 'config.php';
require_once 'appHelper.php';

require_once 'Conn.php';
$msg_error = "";
$request = "";
$response = "";

$opt = array("article", "galeria");

if( isset($_GET['articleid']) && isset($_GET['type']) ){

	if (in_array($_GET['type'], $opt) && is_numeric($_GET['articleid'])) {
    	$request = new Conn($config);
    	$request->eliminar('articulo', $_GET['articleid']);
	}else{
		$msg_error = "Operación no valida.";
	}
}

if( isset($_GET['galeriaid']) && isset($_GET['type']) ){

	if (in_array($_GET['type'], $opt) && is_numeric($_GET['galeriaid'])) {
    	$request = new Conn($config);
    	$request->eliminar('galeria', $_GET['galeriaid']);
	}else{
		$msg_error = "Operación no valida.";
	}

}



if(isset($_GET['type'])){

	if(in_array($_GET['type'], $opt)){

		switch ($_GET['type']) {
			case 'article':
				header("Location: ../index.php");
				break;
			case 'usuario':
				header("Location: ../usuarios.php");
				break;
			default:
				header("Location: ../index.php");
		}

	}else{
		header("Location: ../index.php");
	}

}else{

header("Location: ../index.php");

}



?>