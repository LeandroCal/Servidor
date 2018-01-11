<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  $title = "Smartphone's el Caminàs -> ";
  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Carrito;

  $carrito = new Carrito();
  //Falta comprobar qué acción: add, delete, empty


  $action = "view";
  if(isset($_GET['action'])){
    $action = $_GET['action'];
  }
  if($action == "add"){
    $carrito->addItem($_GET["id"], $_GET['cantidad']);
  }
  if($action == "delete"){
    $carrito->deleteItem($_GET["id"]);
  }
  if($action == "empty"){
    $carrito->empty();
  }
  include("./include/header.php");
?>
  <div class="row carro">
    <h2 class='subtitle' style='margin:0'>Carrito de la compra</h2>
    <?php  echo $carrito->toHtml();?>
  </div>

<!--modal mostrar producto-->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalles del producto</h4>
        </div>
        <div class="modal-body">
          <iframe src='#' width="100%" height="600px" frameborder=0 style='padding:8px'></iframe>
        </div>
      </div>
    </div>
  </div>

<?php
$bottomScripts = array();
$bottomScripts[] = "modalIframeProducto.js";
include("./include/footer.php");
?>
