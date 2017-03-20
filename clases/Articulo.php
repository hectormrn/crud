<?php 

include 'Conn.php';

/*
@author Hector Mrn
*/
class Articulo
{
	
	private $db = null;
	private $tipo;
	private $response;

	function __construct($config)
	{
		$this->db = new Conn($config);
		$this->tipo = "articulo";
		$this->response = array();
	}


	public function getAll(){
		 return $this->db->getAll();
	}

	public function getOne($id){
		 return $this->db->getArticulo($id);
	}

	public function saveArticulo($AData){
		$actualizar = false;
		if(isset($AData['SClave'])){
			if($AData['SClave'] != "" &&  strlen($AData['SClave'])>0 ){
				$actualizar = true;
			}
		}
		if($actualizar){
			$this->response = $this->db->update($this->tipo, $AData);
		}else{
			$this->response = $this->db->save($this->tipo, $AData);
		}
		
		return $this->response;
	}

}

