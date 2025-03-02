<?php
class Socio {
    private $dni;
    private $nombre;
    private $direccion;
    private $localidad;
    private $provincia;
    private $telefono;
    private $email;
    private $contrasena;
    private $rol;

    //Constructor
    public function __construct($dni,$nombre, $direccion, $localidad, $provincia,$telefono,$email,$contrasena) {
        $this->dni=$dni;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->localidad = $localidad;
        $this->provincia = $provincia;
        $this->telefono=$telefono;
        $this->email=$email;
        $this->contrasena=$contrasena;
        //$this->rol=$rol;
    }
    // Getters y setters para cada atributo
    
    public function getDni(){
        return $this->dni;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function getLocalidad() {
        return $this->localidad;
    }
    public function getProvincia () {
        return $this->provincia;
    }
    public function getTelefono() {
        return $this->telefono;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getContrasena(){
        return $this->contrasena;
    }
    public function getRol(){
        return $this->rol;
    }
}    
    class Articulos {
        private $codigo;
        private $nombre;
        private $categoriahijo;
        private $descripcion;
        private $categoriapadre;
        private $precio;
        private $descuento;
        private $ruta;
       //Constructor
        public function __construct($codigo,$nombre, $descripcion, $categoriapadre,$categoriahijo, $precio,$descuento,$ruta) {
            $this->codigo=$codigo;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->categoriapadre = $categoriapadre;
            $this->categoriahijo = $categoriahijo;
            $this->precio = $precio;
            $this->ruta=$ruta;
            $this->descuento=$descuento;
           
        }
        // Getters y setters para cada atributo
        
        public function getCodigo(){
            return $this->codigo;
        }
        
        public function getNombre() {
            return $this->nombre;
        }
        public function getDescripcion(){
            return $this->descripcion;
        }
        public function getCategoriapadre() {
            return $this->categoriapadre;
        } public function getCategoriahijo() {
            return $this->categoriahijo;
        }
        public function getPrecio () {
            return $this->precio;
        }
        public function getDescuento () {
            return $this->descuento;
        }
        public function getImagen() {
            return $this->ruta;
        }
    
    
        /////////   

    /////////   
    /*
    public function setDni($dni) {
        $this->dni = $dni;
    }
     public function setCorreo($email) {
        $this->email = $email;
    }
    public function setTelefono($telefono) {
    
        $this->telefono = $telefono;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }*/
}
