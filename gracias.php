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

  $title = "Smartphone's el Caminàs -> ";
  include("./include/header.php");

  $carrito->empty();

?>
  <div class="row">
    <div class="jumbotron">
        <h1>Gracias</h1>
        <p> Gracias por realizar su compra con nosotros</p>
        <p><a class="btn btn-primary btn-lg" href="/tienda2/" role="button">Continuar</a></p>
    </div>
  </div>
<?php
include("./include/footer.php");
?>
