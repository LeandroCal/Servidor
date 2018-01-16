<?php

namespace ElCaminas;
use \PDO;
use \ElCaminas\Producto;
class Carrito
{
    protected $connect;
    /** Sin parámetros. Sólo crea la variable de sesión
    */
    public function __construct()
    {
        global $connect;
        $this->connect = $connect;
        if (!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = array();
        }

    }
    public function addItem($id, $cantidad){
        $_SESSION['carrito'][$id] = $cantidad;
    }
    public function deleteItem($id){
      unset($_SESSION['carrito'][$id]);
    }
    public function empty(){
      unset($_SESSION['carrito']);
      self::__construct();
    }
    public function howMany(){
      return array_sum($_SESSION['carrito']);
    }
    public function itemExists($id){
        return isset($_SESSION['carrito'][$id]);
    }
    public function getItemCount($id){
      if (!$this->itemExists($id))
        return 0;
      else
        return $_SESSION['carrito'][$id];
    }
    
    public function precioFinal(){
      $precioFinal = 0;
      if ($this->howMany() > 0){
        $i = 0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;
          $precioFinal += $subtotal;
        }
      }
      return $precioFinal;
    }
    public function toHtml(){
      //NO USAR, de momento
      $redirect = "/tienda2/";
      if(isset($_GET['redirect'])){
        $redirect =  $_GET['redirect'];
      }

      $precio_final = 0;
      $str = <<<heredoc
      <table class="table">
        <thead> <tr> <th>#</th> <th>Producto</th> <th>Cantidad</th> <th>Precio</th> <th>Total</th></tr> </thead>
        <tbody>
heredoc;
      if ($this->howMany() > 0){
        $i = 0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;
          $precio_final += $subtotal;
          $subtotalTexto = number_format($subtotal , 2, ',', ' ') ;
          $str .=  "<tr><th scope='row'>$i</th><td><a href='" .  $producto->getUrl() . "'>" . $producto->getNombre() . "</a><a class='open-modal' title='Haga click para más información' href='".$producto->getUrl()."'><span style='margin-left:10px; color:#000' class='fa fa-external-link'></sapan></a></td><td>".$producto->getCantidad()."</td><td>" .  $producto->getPrecioReal() ." €</td><td>$subtotalTexto €</td><td><a class='borrar_item' href='./carro.php?action=delete&id=" . $producto->getId() . "'><span class='fa fa-trash-o'></span></a></td></tr>";
        }
      }
      $str .= <<<heredoc
        </tbody>
      </table>
heredoc;
      $str .= "<a class='col-xs-6 col-md-3' href='$redirect'><button type='button' class='btn btn-warning'>Seguir comprando</button></a>";
      $str .= "<div id='paypal-button-container' class='col-xs-6 col-md-3'></div>";
      $str .= "<p class='col-xs-6 col-md-3 text-success'>Total productos: ".$this->howMany()."</p>";
      $str .= "<p class='col-xs-6 col-md-3 text-info'>Precio final: ".$precio_final." €</p>";

      return $str;
    }
}
