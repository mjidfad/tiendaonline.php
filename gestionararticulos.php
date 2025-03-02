<?php
 include_once("db.php");
     include_once("clases.php");
class Gestorarticulos {
    private $db;
    //Constructor base datos
    public function __construct($db) {
        $this->db = $db;}
       
    
//Para insertar los datos de los socios
public function insertarA(Articulos $articulo) {

    
   
    $pdo = "INSERT INTO articulos (codigo,nombre,descripcion,categoriapadre,categoriahijo,precio,descuento,
     imagen) VALUES (:codigo,:nombre, :descripcion, :categoriapadre, :categoriahijo,:precio,:descuento, :imagen)";
    try {
        $stmt = $this->db->prepare($pdo);
        $stmt->bindValue(':codigo',$articulo->getCodigo());
       $stmt->bindValue(':nombre', $articulo->getNombre());
       $stmt->bindValue(':descripcion', $articulo->getDescripcion());
       $stmt->bindValue(':categoriapadre', $articulo->getCategoriapadre());
       $stmt->bindValue(':categoriahijo', $articulo->getCategoriahijo());
       $stmt->bindValue(':precio', $articulo->getPrecio());
       $stmt->bindValue(':descuento', $articulo->getDescuento());
      $stmt->bindValue(':imagen', $articulo->getImagen());
        
        if ($stmt->execute()) {
            echo "<div class='alerta'>
            <h5>!el articulo añasdido correctamente¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";

        } else {
    
            echo "<div class='alerta'>
            <h5>!error en añadir el articulo¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
          
            
        }
    } catch (PDOException $e) {
        return "Error al insertar los valores: " . $e->getMessage();
    }
    }
//Para modificar los datos a partir del nombre del socio
public function modificarA(Articulos $articulo) {
    $pdo2="UPDATE articulos SET nombre =:nombre, descripcion = :descripcion,categoriapadre=:categoriapadre,categoriahijo=:categoriahijo ,
     precio = :precio,descuento=:descuento,imagen=:imagen  WHERE codigo=:codigo";
    try {
        $stmt = $this->db->prepare($pdo2);
        $codigo=$articulo->getCodigo();
        $nombre = $articulo->getNombre();
        $descripcion = $articulo->getDescripcion();
        $categoriapadre=$articulo->getCategoriapadre();
        $categoriahijo=$articulo->getCategoriahijo();
        $precio = $articulo->getPrecio();
        $descuento = $articulo->getDescuento();
        $ruta =$articulo->getImagen();
       
        
         $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion',  $descripcion);
        $stmt->bindParam(':categoriapadre',  $categoriapadre);
        $stmt->bindParam(':categoriahijo',  $categoriahijo);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':descuento',$descuento);
        $stmt->bindParam(':imagen',  $ruta);
        
    if ($stmt->execute()) {
           
            echo "<div class='alerta'>
            <h5>!Los datos han sido actualizados satisfactoriamente¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        } else {
            
            echo "<div class='alerta'>
            <h5>!No se pudieron actualizar los datos¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
   } catch (PDOException $e) {
        return "Ha habido un error al actualizar los valores: " . $e->getMessage();
    }
}
///////////////////////////
public function mostrarA() {
    include_once("db.php");

    $pdo = "SELECT codigo ,nombre,descripcion,categoriapadre,categoriahijo,precio,descuento ,imagen FROM articulos";
    ///


    ///
   
   try {
        $stmt = $this->db->prepare($pdo);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ////
     
     
        ////
       

        $articulos= [];
        foreach ($result as $row) {
            $articulos[] = new Articulos($row['codigo'],$row['nombre'], $row['descripcion'], $row['categoriapadre'],$row['categoriahijo'],
             $row['precio'],$row['descuento'],
            $row['imagen']);
        }
        return $articulos;



    } catch (PDOException $e) {
        return "Error en la consulta: " . $e->getMessage();
    }
}
//Para buscar los datos a partir del nombre public function buscar($cadena)
   
public function buscarA($cadena) {
    $pdo = "SELECT * FROM articulos WHERE nombre LIKE :cadena ORDER BY nombre";
    try {
        $stmt = $this->db->prepare($pdo);
        $stmt->bindValue(':cadena', "%$cadena%", PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $socios = [];
        foreach ($result as $row) {
            $socios[] = new Articulos($row['codigo'],$row['nombre'],$row['descripcion'], $row['categoriapadre']
            , $row['categoriahijo'], $row['precio'],$row['descuento'],$row['imagen']);
        }
        return $socios;
    } catch (PDOException $e) {
        return "Error en la búsqueda: " . $e->getMessage();
    }
}
//Para eliminar los datos a partir del nombre del socio
public function eliminarA($codigo) {
$pdo = "DELETE FROM articulos WHERE codigo = :codigo";
try {
    $stmt = $this->db->prepare($pdo);
    $stmt->bindParam(':codigo', $codigo);

    $stmt->execute();
       
    } catch (PDOException $e) {
    return "Error al eliminar el socio: " . $e->getMessage();
}
}

}



?>
