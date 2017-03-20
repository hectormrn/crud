<?php 
/*
@author Hector Mrn
*/
require_once './clases/config.php';
 include_once './clases/appHelper.php';

$mensaje_error = "";
$peticion = "";
$response = "";
$articuloid = "";
$resultado = "";
$typeAlert = "alert-warning";

include_once './clases/Articulo.php';
include_once 'layouts/header.php'; 

if(isset($_POST['guardar'])){
  $peticion = new Articulo($config);
  $resultado = $peticion->saveArticulo($_POST);
  if($resultado['success']){
    $typeAlert = "alert-success";
    $mensaje_error =  "Super! operación realizada correctamente.";
  }else{
    $typeAlert = "alert-warning";
    $mensaje_error = $resultado['error_msg'];
  }
}

if( isset($_GET['articleid']) ){

  if(is_numeric($_GET['articleid']) == false){
    $mensaje_error = "La Clave de Articulo no es valida.";
  }else{
    $articuloid = $_GET['articleid'];
    $peticion = new Articulo($config);
    $response = $peticion->getOne($_GET['articleid']);
    if($response == false){
      $mensaje_error = "No se encontro un Articulo con esa clave";
    }
  }

}

?>

	<div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Clientes
                    </h1>
                    <ol class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-dashboard"></i> Articulos
                        </li>
                    </ol>
                </div>
                <?php if(isset($mensaje_error) && $mensaje_error != ""){ ?>
                      <div class="col-md-12">
                        <div class="alert <?=$typeAlert;?> alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <?= $mensaje_error; ?>
                        </div>
                      </div>
                <?php } ?>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-long-arrow-right fa-fw"></i><?=isset($_GET['articleid'])?"Editar":"Nuevo"; ?> Articulo</h3>
                    </div>
                  <div class="panel-body">
                    <div class="col-md-8 col-md-offset-2" style="max-width: 900px;">

                        <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal">
                          <fieldset>
                              <!-- Form Name -->
                              <div class="form-group">
                                <div class="col-md-12 inputGroupContainer">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input  name="SClave" value="<?=$articuloid;?>" class="form-control"  type="hidden" />
                                <input  name="STitulo" value="<?=isset($response->titulo)?htmlentities($response->titulo):""?>" placeholder="Titulo Articulo" class="form-control"  type="text" required maxlength="<?=$config['max_size_titulo'];?>"/>
                                  </div>
                                </div>
                              </div>
                              <!-- Text input-->
                              <div class="form-group">
                                <div class="col-md-12 inputGroupContainer">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input  name="SContenido" value="<?=isset($response->contenido)?htmlentities($response->contenido):""?>" placeholder="Contenido" class="form-control" type="text" required maxlength="<?=$config['max_size_cotenido'];?>"/>
                                  </div>
                                </div>
                              </div>
                              <!-- Text input-->
                              <?php if(isset($_GET['articleid'])){ ?>
                              <div class="form-group">
                                  <div class="col-md-12 inputGroupContainer">
                                  <div class="input-group">
                                      <?php if(isset($response->img_big)){
                                        $response->img_big = $config['url_show_images'] . $response->img_big;
                                        } ?>
                                      <img src="<?=isset($response->img_big)?$response->img_big:''?>" class="img-thumbnail" alt="Imágen cliente" width="304" height="236">
                                  </div>
                                </div>
                              </div>
                              <?php } ?>
                              <!-- Text input-->
                              <div class="form-group">
                                  <div class="col-md-12 inputGroupContainer">
                                  <div class="input-group">
                                    <?php if(isset($_GET['articleid'])){ ?>
                                      <label for="userfile">Cambiar Imágen Big</label>
                                    <?php }else{ ?>
                                      <label for="userfile">Imágen grande</label>
                                     <?php } ?>
                                    <input name="userfile" class="form-control" type="file" <?php if(!isset($_GET['articleid'])){ echo "required"; } ?> />
                                  </div>
                                  <p>La imágen debe ser de 000 x 000 (pixeles)</p>
                                </div>
                              </div>

                              <!-- Text input-->
                              <?php if(isset($_GET['articleid'])){ ?>
                              <div class="form-group">
                                  <div class="col-md-12 inputGroupContainer">
                                  <div class="input-group">
                                      <?php if(isset($response->img_small)){
                                        $response->img_small = $config['url_show_images'] . $response->img_small;
                                        } ?>
                                      <img src="<?=isset($response->img_small)?$response->img_small:''?>" class="img-thumbnail" alt="Imágen cliente" width="304" height="236">
                                  </div>
                                </div>
                              </div>
                              <?php } ?>

                              <!-- Text input-->
                              <div class="form-group">
                                  <div class="col-md-12 inputGroupContainer">
                                  <div class="input-group">
                                    <?php if(isset($_GET['articleid'])){ ?>
                                      <label for="userfile">Cambiar Imágen Small</label>
                                    <?php }else{ ?>
                                      <label for="userfiledos">Imágen pequeña</label>
                                     <?php } ?>
                                    <input name="userfiledos" class="form-control" type="file" <?php //if(!isset($_GET['articleid'])){ echo "required"; } ?> />
                                  </div>
                                  <p>La imágen debe ser de 000 x 000 (pixeles)</p>
                                </div>
                              </div>

                              <!-- Button -->
                              <div class="form-group">
                                <label class="col-md-12 control-label"></label>
                                <div class="col-md-12">
                                  <input type="submit" name="guardar" class="btn btn-primary" value="<?=isset($_GET['articleid'])?'Guardar Cambios':'Guardar'?>" />
                                </div>
                              </div>
                        </fieldset>
                        </form>
                    </div>
                  </div>
                </div>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->

    </div><!-- /#page-wrapper -->

<?php 
include_once 'layouts/footer.php'; 
?>