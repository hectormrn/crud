<?php 
/*
@author Hector Mrn
*/
include_once 'appHelper.php';
require_once 'Cargador.php';

class Conn{

	private $tipoDB;
	private $host;
	private $dbName;
	private $usuario;
	private $password;
	private $pdoDB;
  private $salt;
  private $ruta_images;
  private $configuracion;
	
	private $nuevoArticulo = "INSERT INTO articulos ( titulo, contenido, img_big, img_small) VALUES (:param1, :param2, :param3, :param4 )";
	
  private $editarArticulo = "UPDATE articulos SET titulo= :param1, contenido= :param2, img_big= :param3, img_small=:param4 WHERE idArticulo = :clave LIMIT 1";
  
  private $editarArticuloSinImgs = "UPDATE articulos SET titulo= :param1, contenido= :param2 WHERE idArticulo = :clave LIMIT 1";
  
  private $editarArticuloOnlyImgBig = "UPDATE articulos SET titulo= :param1, contenido= :param2, img_big= :param3 WHERE idArticulo = :clave LIMIT 1";

  private $editarArticuloOnlyImgSmall = "UPDATE articulos SET titulo= :param1, contenido= :param2, img_small = :param3 WHERE idArticulo = :clave LIMIT 1";

  private $eliminaArticulo = "DELETE FROM articulos WHERE idArticulo = :clave";


	function __construct($config)
	{
    $this->tipoDB = $config['typeDB'];
    $this->host = $config['host'];
    $this->dbName = $config['dbName'];
    $this->usuario = $config['dbUser'];
    $this->password = $config['dbPass'];
    $this->configuracion = $config;

		try {
			$this->pdoDB = new PDO($this->tipoDB.':host='.$this->host.';dbname='.$this->dbName, $this->usuario, $this->password);
			$this->pdoDB->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			$this->pdoDB->exec("set names utf8");
		} catch (Exception $e) {
			echo "ocurrio un error al intentar conectarse a DB, no se pudo conectar a la base de datos";
			exit;
		}
  }

  	public function getAll(){

  		$sentencia = $this->pdoDB->prepare("SELECT * FROM articulos ORDER BY idArticulo ASC ");
	    $sentencia->execute();
	    return $sentencia->fetchAll(PDO::FETCH_CLASS);
  	}

  	public function getArticulo($idCliente){
  		$sentencia = $this->pdoDB->prepare("SELECT * FROM articulos WHERE idArticulo = :clave ");
	    $sentencia->bindParam(':clave', $idCliente);
	    $sentencia->execute();
	    return $sentencia->fetch(PDO::FETCH_OBJ);
  	}

	 public function getQueryStr($tipo, $operacion){
		$queryRetorno = '';
		switch ($tipo) {
  			case 'articulo':
  				
  				switch ($operacion) {
  					case 'insert': $queryRetorno = $this->nuevoArticulo;
  						break;
  					case 'update': $queryRetorno = $this->editarArticulo;
  						break;
            case 'updateSinImgs': $queryRetorno = $this->editarArticuloSinImgs;
              break;
            case 'updateOnlyImgBig': $queryRetorno = $this->editarArticuloOnlyImgBig;
              break;
            case 'updateOnlyImgSmall': $queryRetorno = $this->editarArticuloOnlyImgSmall;
              break;
  					case 'delete': $queryRetorno = $this->eliminaCliente;
  						break;
  				}
  				break;

        case 'usuario':
          
          switch ($operacion) {
            case 'insert': $queryRetorno = $this->nuevoUsuario;
              break;
            case 'update': $queryRetorno = $this->editarUsuario;
              break;
            case 'updateSinPWD': $queryRetorno = $this->editarUsuarioSinPWD;
              break;
            case 'updatePWD': $queryRetorno = $this->editarUsrPWD;
              break;
            case 'delete': $queryRetorno = $this->eliminaUsuario;
              break;
          }   
          break;
  		}

  		return $queryRetorno;
	}
  	
  	
  	public function save($tipo, $Adata){
      
  		$query = $this->getQueryStr($tipo, 'insert');
      $upload =  new Cargador($this->configuracion);
      $response_big   = $upload->uploadValidImage("big");
      
      if($response_big['success']){
        
        $response_small = $upload->uploadValidImage("small");
        
        if($response_small['success']){

              $sentencia = $this->pdoDB->prepare($query);
              $sentencia->bindParam(':param1', $Adata['STitulo']);
              $sentencia->bindParam(':param2', $Adata['SContenido']);
              $sentencia->bindParam(':param3', $response_big['url_img']);
              $sentencia->bindParam(':param4', $response_small['url_img']);
              
              $result = $sentencia->execute();
              $errlog = $sentencia->errorInfo();
              $response = array(
                              'success' => $result,
                              'error_msg' => isset($errlog[2])?$errlog[2]:""
                              );

        }else{
          return $response_small;
        }
      
      }

      return $response_big;
  	}

  	/*
  	* Update usuario|galeria
  	* @param $tipo: define si es cliente o galería
  	* @param $Adata: Arreglo de datos a guardar;
  	* return boolean.
  	*/
  	public function update($tipo, $Adata){
      $operacion = "";
      $query ="";
      $updateImgBig = false;
      $updateImgSmall = false;
      $response = "";
      $response_big = array();
      $response_small = array();

      if( AppHelper::existImageToUpload() && AppHelper::existImageToUploadSmall() ){
        //intentar subir imagenes
        $updateImgBig = true;
        $updateImgSmall = true;

        $upload =  new Cargador($this->configuracion);
        $response_big   = $upload->uploadValidImage("big");
        if($response_big['success']){
          
          $upload2 =  new Cargador($this->configuracion);
          $response_small = $upload2->uploadValidImage("small");
          
          if($response_small['success'] == false){
            //eliminar imagen big subida.

            return $response_small;
          }
        
        }else{ // error al intentar subir imagen big
          return $response_big;
        }

      }else{ //intentar subir imagenes individuales

          //definir si actualizar imagen big
          if(AppHelper::existImageToUpload()){
            $updateImgBig = true;
            $response_big = $upload->uploadValidImage("big");
            if($response_big['success']==false){
              return $response_big;
            }
          }

          //definir si actualizar imagen big
          if(AppHelper::existImageToUploadSmall()){
            $updateImgSmall = true;
            $response_small = $upload->uploadValidImage("small");
            if($response_small['success']==false){
              return $response_small;
            }
          }
        
      }

      if($updateImgSmall == false && $updateImgBig == false){
        $operacion = "updateSinImgs";
      }
      if($updateImgSmall == true && $updateImgBig == false){
        $operacion = "updateOnlyImgSmall";
      }
      if($updateImgSmall == false && $updateImgBig == true){
        $operacion = "updateOnlyImgBig";
      }
      if($updateImgSmall == true && $updateImgBig == true){
        //update tabla completa
        $operacion = "update";
      }

      $query = $this->getQueryStr($tipo, $operacion);
      $sentencia = $this->pdoDB->prepare($query);
      $sentencia->bindParam(':param1', $Adata['STitulo']);
      $sentencia->bindParam(':param2', $Adata['SContenido']);
      $sentencia->bindParam(':clave', $Adata['SClave']);

      switch ($operacion) {
        case 'updateOnlyImgBig':
          $sentencia->bindParam(':param3', $response_big['url_img']);
          break;

        case 'updateOnlyImgSmall':
          $sentencia->bindParam(':param3', $response_small['url_img']);
          break;

        case 'update':
          $sentencia->bindParam(':param3', $response_big['url_img']);
          $sentencia->bindParam(':param4', $response_small['url_img']);
          break;
      }
        //eliminamos el archivo(s) anterior del servidor
        $this->deleteFilesUpdated($operacion, $Adata['SClave']);

      $result = $sentencia->execute();
      $errlog = $sentencia->errorInfo();
      
      
      $response = array(
                        'success' => $result,
                        'error_msg' => isset($errlog[2])?$errlog[2]:""
                        );
  		
      return $response;

  	}

    public function eliminar($tipo, $clave){
      $query = "";
      switch ($tipo) {
        case 'articulo':
              $query = $this->eliminaArticulo;
          break;
        case 'usuario':
              $query = $this->eliminaUsuario;
          break;
      }
      
      $sentencia = $this->pdoDB->prepare($query);
      $sentencia->bindParam(':clave', $clave);
      $sentencia->execute();
    }

   


  	/*
  	* delete usuario|galeria
  	* @param $tipo: define si es cliente o galería
  	* @param $clave: id de la tabla;
  	* return boolean.
  	*/
  	public function delete($tipo, $clave){

  		$query = $this->getQueryStr($tipo, 'insert');
  	
  		$sentencia = $this->pdoDB->prepare($query);
  		$sentencia->bindParam(':param1', $Adata['val']);
  		return $sentencia->execute();
  	}


    //consultar rows que se actualizaron para borrar archivos.
    public function deleteFilesUpdated($operacion, $clave){
      $query = "SELECT * FROM articulos WHERE idArticulo = :param LIMIT 1";
      $sentencia = $this->pdoDB->prepare($query);
      $sentencia->bindParam(':param', $clave);
      $sentencia->execute();
      $result = $sentencia->fetch(PDO::FETCH_OBJ);
      switch ($operacion) {
        case 'updateOnlyImgBig':
            $rutaimg = $this->configuracion['url_upload_articles'].$result->img_big;
            try {
                unlink($rutaimg);
              } catch (Exception $e) { }
        
          break;

        case 'updateOnlyImgSmall':
          $rutaimg = $this->configuracion['url_upload_articles'].$result->img_small;
            try {
                unlink($rutaimg);
              } catch (Exception $e) { }
          break;

        case 'update':
          $rutaimg = $this->configuracion['url_upload_articles'].$result->img_big;
            try {
                unlink($rutaimg);
              } catch (Exception $e) { }
        $rutaimg = $this->configuracion['url_upload_articles'].$result->img_small;
            try {
                unlink($rutaimg);
              } catch (Exception $e) { }
          break;
      }

    }

}