<?php

class GestorSocios
{
    private $db;
    //Constructor base datos
    public function __construct($db)
    {
        $this->db = $db;
    }
    //Para insertar los datos de los socios
    public function insertar(Socio $socio)
    {
        include_once("db.php");
        $pdo = "INSERT INTO usuarios (dni,nombre,direccion,localidad,provincia,
     telefono,email,contrasena) VALUES (:dni,:nombre, :direccion,:localidad,:provincia, :telefono, :email,:contrasena)";
        try {
            $stmt = $this->db->prepare($pdo);
            $stmt->bindValue(':dni', $socio->getDni());
            $stmt->bindValue(':nombre', $socio->getNombre());
            $stmt->bindValue(':direccion', $socio->getDireccion());
            $stmt->bindValue(':localidad', $socio->getLocalidad());
            $stmt->bindValue(':provincia', $socio->getProvincia());
            $stmt->bindValue(':telefono', $socio->getTelefono());
            $stmt->bindValue(':email', $socio->getEmail());
            $stmt->bindValue(':contrasena', $socio->getContrasena());
            // $stmt->bindValue(':rol', $socio->getRol());

            if ($stmt->execute()) {
                
                echo "<div class='alerta'>
            <h5>!Los datos han sido introducidos satisfactoriamente,inicia session para acceder¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            } else {


                
                echo "<div class='alerta'>
            <h5>!Ha habido un error al insertar los valores¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            }
        } catch (PDOException $e) {
            return "Error al insertar los valores: " . $e->getMessage();
        }
    }
    //Para modificar los datos a partir del nombre del socio
    public function modificar(Socio $socio)
    {
        $pdo = "UPDATE usuarios SET nombre =:nombre,localidad=:localidad, direccion = :direccion,provincia=:provincia ,
         telefono = :telefono,email = :email,contrasena=:contrasena WHERE dni = :dni";
        try {
            $stmt = $this->db->prepare($pdo);
            $dni = $socio->getDni();
            $nombre = $socio->getNombre();
            $direccion = $socio->getDireccion();
            $localidad = $socio->getLocalidad();
            $provincia = $socio->getProvincia();
            $telefono = $socio->getTelefono();
            $email = $socio->getEmail();
            $contrasena = $socio->getContrasena();

            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':localidad',  $localidad);
            $stmt->bindParam(':provincia',  $provincia);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':email',  $email);
            $stmt->bindParam(':contrasena',  $contrasena);

            if ($stmt->execute()) {
                echo "<div class='alerta'>
            <h5>!Los datos han sido actualizados satisfactoriamente¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
             
            } else {
              
                echo "<div class='alerta'>
            <h5>!No se pudieron actualizar los datos.¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            }
        } catch (PDOException $e) {
            return "Ha habido un error al actualizar los valores: " . $e->getMessage();
        }
    }
    //Para mostrar los datos de los socios
    public function mostrar()
    {
        include_once("db.php");
        $pdo = "SELECT dni ,nombre,direccion,localidad,provincia ,telefono, email,contrasena FROM usuarios";
        try {
            $stmt = $this->db->prepare($pdo);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $socios = [];
            foreach ($result as $row) {
                $socios[] = new Socio(
                    $row['dni'],
                    $row['nombre'],
                    $row['direccion'],
                    $row['localidad'],
                    $row['provincia'],
                    $row['telefono'],
                    $row['email'],
                    $row['contrasena']
                );
            }
            return $socios;
        } catch (PDOException $e) {
            return "Error en la consulta: " . $e->getMessage();
        }
    }

    //Para buscar los datos a partir del nombre
    public function buscar($cadena)
    {
        $pdo = "SELECT * FROM usuarios WHERE nombre LIKE :cadena ORDER BY nombre";
        try {
            $stmt = $this->db->prepare($pdo);
            $stmt->bindValue(':cadena', "%$cadena%", PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $socios = [];
            foreach ($result as $row) {
                $socios[] = new Socio(
                    $row['dni'],
                    $row['nombre'],
                    $row['direccion'],
                    $row['localidad'],
                    $row['provincia'],
                    $row['telefono'],
                    $row['email'],
                    $row['contrasena']
                );
            }
            return $socios;
        } catch (PDOException $e) {
            return "Error en la búsqueda: " . $e->getMessage();
        }
    }

    //Para eliminar los datos a partir del nombre del socio
    public function eliminar($nombre)
    {
        if ($nombre != 'admin') {
            $pdo = "DELETE FROM usuarios WHERE nombre = :nombre ";
            try {
                $stmt = $this->db->prepare($pdo);
                $stmt->bindParam(':nombre', $nombre);

                if ($stmt->execute()) {
                    
                    echo "<div class='alerta'>
            <h5>!El socio ha sido eliminado satisfactoriamente¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                    
                } else {
                 
                    echo "<div class='alerta'>
            <h5>!No se pudo eliminar el socio¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                }
            } catch (PDOException $e) {
                return "Error al eliminar el socio: " . $e->getMessage();
            }
        } else {
         
            echo "<div class='alerta'>
            <h5>!no se puede eliminar al administrador¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
    }
}
