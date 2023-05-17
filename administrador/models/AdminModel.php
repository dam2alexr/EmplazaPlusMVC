<?php
class AdminModel {

    private $db;
    public function __construct() {
        // Traemos la única instancia de PDO
        $this->db = SPDO::singleton();
    }

    /*
    Función para el logueo de un usuario existente.
    Consulta la BBDD y valida los datos proporcionados.
    */
    public function iniciarsesion($correo) {
        $query = "SELECT * FROM usuarios WHERE correo = :correo";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':correo' => $correo));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    Función para el registro de un nuevo usuario.
    Ejecuta una consulta en la BBDD y añade un nuevo usuario.
    */
    public function registrar($data) {
        $usuario = $data['usuario'];
        $correo = $data['correo'];
        $contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (usuario, correo, contrasena, permisos, descripcion) VALUES (:usuario, :correo, :contrasena, 'usuario', 'Sin descripcion')";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':usuario' => $usuario, ':correo' => $correo, ':contrasena' => $contrasena));
    }

    /*
    Función que nos permitirá actualizar la contraseña de la cuenta de usuario
    que actualmente nos encontramos logueados.
    */
    public function reestablecer($data) {
        $correo = $data['correo'];
        $contrasenanueva = password_hash($data['contrasenanueva'], PASSWORD_DEFAULT);

        $query = "UPDATE usuarios SET contrasena=:contrasenanueva WHERE correo=:correo";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':correo' => $correo, ':contrasenanueva' => $contrasenanueva));
    }

    public function darBaja($data) {
        // Para desarrollar más tarde...
    }

    public function tablaMostrarUsuarios() {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->db->query($query);
        $usuarios = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    public function tablaEmplazamientos() {
        $query = "SELECT * FROM emplazamientos";
        $stmt = $this->db->query($query);
        $emplazamientos = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $emplazamientos[] = $row;
        }
        return $emplazamientos;
    }

    public function nuevoEmplazamiento($data) {
        $nombre = $data['nombre'];
        $descripcion_corta = $data['descripcion_corta'];
        $descripcion_larga = $data['descripcion_larga'];
        $categoria = $data['categoria'];
        $precio = $data['precio'];

        // Gestionamos la imagen a insertar en la BBDD
        $comprobacion = getimagesize($_FILES["imagen"]["tmp_name"]);

        if ($comprobacion !== false) {
            $imagen = $_FILES['imagen']['tmp_name'];
            $contenidoImagen = addslashes(file_get_contents($imagen));

            $fechaCreacion = date("Y-m-d H:i:s");
        }

        // Verificar si se ha enviado un archivo y si no hubo errores en la subida
        /*
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = $_FILES['imagen']['name'];
            $rutaImagen = 'src/img/imagenesEmplazamientos/' . $nombreImagen;

            // Mover el archivo cargado a la ruta de destino
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen);
        } else {
            // Colocar una imagen por defecto en caso de no existir ninguna o no cargarla bien
            $rutaImagen = 'src/img/imagenesEmplazamientos/imagenPorDefecto.jpg';
        }
        */

        $query = "INSERT INTO emplazamientos (nombre, descripcion_corta, descripcion_larga, categoria, precio, fecha_registro, imagenes) VALUES (:nombre, :descripcion_corta, :descripcion_larga, :categoria, :precio, :fecha_registro, :imagenes)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':nombre' => $nombre, ':descripcion_corta' => $descripcion_corta, ':descripcion_larga' => $descripcion_larga, ':categoria' => $categoria, ':precio' => $precio, ':fecha_registro' => $fechaCreacion, ':imagenes' => $contenidoImagen));
    }

    /*
    Función que nos permite consultar toda la lista de reservas realizadas.
    */
    public function consultarTotalReservas() {
        $query = "SELECT r.id, u.usuario, e.nombre, r.fecha_alta, r.fecha_baja, r.precio
        FROM usuarios u 
        JOIN reservas r ON u.id = r.id_usuario
        JOIN emplazamientos e ON r.id_emplazamiento = e.id";

        $stmt = $this->db->query($query);

        $reservas = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reservas[] = $row;
        }
        return $reservas;
    }
}
?>