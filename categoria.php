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

  $query = " SELECT * FROM categorias WHERE id = :id";
	$statement = $connect->prepare($query);
	$statement->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
	$statement->execute();
	$count = $statement->rowCount();
	if($count > 0){
		$categoria = $statement->fetch(PDO::FETCH_ASSOC);
	}else{
    http_response_code(404);
    exit();
  }
  $title = "Smartphone's el Caminàs -> " . $categoria["nombre"];

  require './include/JasonGrimes/Paginator.php';

  use JasonGrimes\Paginator;

  $currentPage = (isset($_GET["currentPage"]) ? $_GET["currentPage"] : 1);
  //En principio este parámetro no estaría en producción. Lo usamos para ir probando el paginador con distintos tamaños
  $itemsPerPage = (isset($_GET["itemsPerPage"]) ? $_GET["itemsPerPage"] : 3);

  // $query = " SELECT COUNT(*) as cuenta FROM productos WHERE id_categoria = :id";
  // $statement = $connect->prepare($query);
  // $statement->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
  // $statement->execute();

  $totalItems = $productos->getCountProductosByCategoria($_GET["id"]);
  $state = "normal";
  if (isset($_GET["state"])){
    $state = $_GET["state"];
  }
  if ("normal" == $state){
    include("./include/header.php");
  }elseif("exclusive" == $state){
    /** en estado exclusive no carga el header ni footer porque carga los productos asíncronos
    */
  }
?>
<?php if ("normal" == $state):?>
  <h2 class='subtitle' style='margin-left:0; margin-right:0;'><?php echo $categoria["nombre"];?></h2>
  <div id="data-container">
<?php endif; ?>
    <div class="row">
      <?php
      foreach($productos->getProductosByCategoria($_GET["id"], $itemsPerPage, $currentPage) as $producto){
         echo $producto->getThumbnailHtml();
      }
      ?>
    </div>

    <div class="row">
      <?php
      $urlPattern ="./categoria.php?id=" . $_GET["id"] . "&itemsPerPage=$itemsPerPage&currentPage=(:num)";
      $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
      //echo $paginator->toHtml();
      include './include/JasonGrimes/examples/pager.phtml';
      ?>
    </div>

  <?php if ("normal" == $state):?>
  </div>
  <?php endif; ?>

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
  if ("normal" == $state){
    $bottomScripts = array();
    $bottomScripts[] = "modalDomProducto.js";
    $bottomScripts[] = "loadCategorias.js";
    include("./include/footer.php");
  }

?>
