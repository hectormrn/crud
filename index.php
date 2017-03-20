<?php
/*
@author Hector Mrn
*/
require_once './clases/config.php';
include_once './clases/appHelper.php';
/*if( appHelper::isSessionOpen() == false ){
    header("Location: index.php");
}*/

require_once './clases/Articulo.php';
include_once 'layouts/header.php'; 
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Dashboard
                    </h1>
                    <ol class="breadcrumb">
                        <li class="active">
                            <i class="fa fa-dashboard"></i> Articulos
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="row breadcrumb">
                <a class="btn btn-big btn-success pull-right" href="view-articulo.php">Crear Nuevo</a>
            </div>

            <div class="row">

                    <table id="" class="display table table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>titulo</th>
                                <th>Contenido</th>
                                <th class="hidden-xs hidden-sm">Img big</th>
                                <th class="hidden-xs hidden-sm">Img small</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $obj = new Articulo($config);
                            $articulos =  $obj->getAll();
                            foreach ($articulos as $articulo) { ?>
                            
                            <tr>
                                <td><?=$articulo->idArticulo;?></td>
                                <td><?=$articulo->titulo;?></td>
                                <td><?=$articulo->contenido;?></td>
                                <td class="hidden-xs hidden-sm"><img class="thumbnail col-md-6" src="<?=$config['url_show_images'].$articulo->img_big;?>" alt="">
                                </td>
                                <td class="hidden-xs hidden-sm"><img class="thumbnail col-md-6" src="<?=$config['url_show_images'].$articulo->img_small;?>" alt="">
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="<?="view-articulo.php?articleid=".$articulo->idArticulo;?>">Editar</a><br><br>
                                    <a class="btn btn-danger" href="<?='clases/delete.php?articleid='.$articulo->idArticulo.'&type=article'?>">Eliminar</a>
                                </td>
                            </tr>

                            <?php } ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Proyecto</th>
                                <th class="hidden-xs hidden-sm">Imágen</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
            </div>

        </div><!-- /.container-fluid -->

    </div><!-- /#page-wrapper -->

<?php include_once('layouts/footer.php'); ?>

