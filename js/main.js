$(document).ready(function(){

  $('.borrar_item').click(function(){

    if(confirm("¿Está seguro que quiere eliminar este producto del carrito?") == 1){
      alert("Producto eliminado");
    }
    else {
      return false;
    }

  });

});
