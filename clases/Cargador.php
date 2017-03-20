<?php 
require_once 'appHelper.php';
/**
@author Hector Mrn
*/
class Cargador
{
	private $rutaDestino;
	private $maxFileSize;

	function __construct($config)
	{
		$this->rutaDestino = $config['url_upload_articles'];
		$this->maxFileSize = 70000; //50 kilobytes
	}

	public function uploadValidImage($sizeImg){
		$tamanoMaximo = $this->maxFileSize;
		$anchoMaximo = 0;
	    $altoMaximo = 0;
	    $nombre_img = "";
		$tipo = "";
		$tamano = "";
		$tmp_name = "";
		$response = array();

	    //configuarar al tamaño deseado...
		switch ($sizeImg) {
	        case 'big':
				$anchoMaximo = 540;
				$altoMaximo = 344;
				$nombre_img = $_FILES['userfile']['name'];
				$tipo = $_FILES['userfile']['type'];
				$tamano = $_FILES['userfile']['size'];
				$tmp_name = $_FILES['userfile']['tmp_name'];
	    	  break;
	    	case 'small':
				$anchoMaximo = 540;
				$altoMaximo = 344;
				$nombre_img = $_FILES['userfiledos']['name'];
				$tipo = $_FILES['userfiledos']['type'];
				$tamano = $_FILES['userfiledos']['size'];
				$tmp_name = $_FILES['userfiledos']['tmp_name'];
	    	  break;
	      }

		if (($nombre_img == !NULL) && ($tamano <= $tamanoMaximo)) 
		{
		   if ( ($tipo == "image/jpeg") || ($tipo == "image/jpg") || ($tipo == "image/png"))
		   {
		   		$im_info = getimagesize($tmp_name); 
				$ancho=$im_info[0]; 
				$alto=$im_info[1];
				if( $ancho>$anchoMaximo || $alto>$altoMaximo && $ancho<$anchoMaximo || $alto<$altoMaximo ){
					$response['success'] = false;
		      		$response['error_msg'] = "La imágen no cumple con las medidas requeridas.";
				}else{

			      	// mover de ruta temporal a ruta de imagenes.
			   		$nombre_img = AppHelper::formatNameImg($nombre_img);

			      	if(move_uploaded_file($tmp_name,$this->rutaDestino.$nombre_img) ){
			      		$response['success'] = true;
			      		$response['url_img'] = $nombre_img;
			      	}else{
			      		$response['success'] = false;
			      		$response['error_msg'] = "No se pudo subir la imágen, intentalo más tarde.";	
			      	}
		      	}

		    } 
		    else 
		    {
		       //si no cumple con el formato
		    	$response['success'] = false;
		    	$response['error_msg'] = "No se puede subir una imágen con ese formato ";
		    }
		} 
		else 
		{
		   //si existe la variable pero se pasa del tamaño permitido
			$response['success'] = false;
		    $response['error_msg'] = "La imágen que intentas subir es demasiado grande";
		    //if($nombre_img == !NULL) echo "La imagen es demasiado grande "; 
		}
		return $response;
	}

}