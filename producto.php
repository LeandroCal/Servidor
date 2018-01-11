<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Productos.php';
  require './include/ElCaminas/Producto.php';

  use ElCaminas\Carrito;
  use ElCaminas\Productos;
  use ElCaminas\Producto;
  $productos = new Productos();
  $carrito = new Carrito();

  try {
    $producto = $productos->getProductoById($_GET["id"]);
  }catch(Exception $e){
    http_response_code(404);
    exit();
  }

  $title = "Smartphone's el Caminàs -> " . $producto->getNombre();

  $state = "normal";

  if(isset($_GET["state"])){
    $state = $_GET["state"];
  }

  if ("normal" == $state){
    include("./include/header.php");
  }else if("popup" == $state){
    $urlCanonical = $producto->getUrl();
    include("./include/header-popup.php");
  } else if("json" == $state){
    echo $producto->getJson();
    exit();
  } else if("exclusive" == $state){
      /** carga el header.php y footer.php
      */
  }

?>
  <div class="row" style='position:relative; border:1px solid #ddd; border-radius:4px; padding:4px;' >
      <?php
        if("normal" == $state || "exclusive" == $state){
          echo $producto->getHtml();
        }
        else{
          echo $producto->getHtmlPopup();
        }
       ?>
  </div>
  <?php if("normal" == $state): ?>

  <div class="row">
    <h2 class='subtitle'>También te puede interesar...</h2>
    <?php
      foreach($productos->getRelacionados($producto->getId(),  $producto->getIdCategoria()) as $producto){
         echo $producto->getThumbnailHtml();
      }
    ?>
  </div>
<?php endif ?>
<!-- Ventana modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalles del producto</h4>
      </div>
      <div class="modal-body">
        <div id='data-container'></div>
      </div>
    </div>
  </div>
</div>

<?php

  $bottomScripts = array();
  $bottomScripts[] = "modalDomProducto.js";

  if ("normal" == $state){
    include("./include/footer.php");
  }else if("popup" == $state){
    include("./include/footer-popup.php");
  }
?>
