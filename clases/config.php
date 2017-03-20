<?php 
/*
@author Hector Mrn
*/
$config = array(
				'typeDB' 				=> 'mysql',
				'host' 					=> 'localhost',
				'dbName' 				=> 'crud',
				'dbUser' 				=> 'root',
				'dbPass' 				=> 'root',
				'title_project' 		=> 'Project - CRUD',
				'paginaSalir' 			=> 'clases/logout.php',
				'paginaInicio' 			=> $_SERVER['HTTP_HOST'],
				'url_show_images' 		=> 'http://'.$_SERVER['HTTP_HOST'].'/img/',
				'url_upload_articles'   => $_SERVER['DOCUMENT_ROOT'].'/img/',
				'img_big'				=> array('alto' => 300, 'ancho' => 500),
				'img_small'				=> array('alto' => 300, 'ancho' => 500),
				'max_size_contenido'	=> 150,
				'max_size_titulo'		=> 100
				);