<?php 
/**
@author Hector Mrn
*/
class AppHelper
{
	private $queryLogin = "";

	function __construct()
	{
	}


	public static function echoStr($str){
		echo (isset($str)) ? $str: "";
	}

	public static function getTypeUserOperation($SClave, $SPwd){
		$operacion = '';
		if($SClave == ''){
        	$operacion = "insert";
	    }
	    
	    if(is_numeric($SClave)){
	        if($SPwd == ''){
	          $operacion = "updateSinPWD";
	        }else{
	          $operacion = "update";
	        }
	    }
	    return $operacion;
	}

	public static function getTypeOperation($SClave){
		$operacion = '';
		if($SClave == ''){
        	$operacion = "insert";
	    }
	    
	    if(is_numeric($SClave)){
	        if($SPwd == ''){
	          $operacion = "updateSinPWD";
	        }else{
	          $operacion = "update";
	        }
	    }
	    return $operacion;
	}

	public static function formatNameImg($nameImage){
		//return preg_replace('`[^a-z0-9-_.]`i','',strtolower(ereg_replace(" ","_",$nameImage)));
		return $file_name = preg_replace('/[^a-z0-9_\.\-[:space:]]/i', '_', $nameImage);  
	}

	public static function existImageToUpload(){
		if( strlen($_FILES['userfile']['tmp_name'])>0 && $_FILES['userfile']['name'] != "" ){
			return true;
		}else{
			return false;
		}
	}

	public static function existImageToUploadSmall(){
		if( strlen($_FILES['userfiledos']['tmp_name'])>0 && $_FILES['userfiledos']['name'] != "" ){
			return true;
		}else{
			return false;
		}
	}

	
}

//echo AppHelper::formatNameImg("iamagen algo$.png");