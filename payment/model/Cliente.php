<?php

/**
 * Clase Cliente
 * @author David
 */
class Cliente {

    private $id;
    private $nombre;
    private $apellido;
    private $usuario;
    private $email;
    private $direccion;
    private $direccion2;
    private $ciudad;
    private $pais;
    private $referido;
    private $medioPago;
    private $estado_cuenta;
    private $estado_pago;
    private $contrasena;
    private $numero_ref_1;
    private $numero_ref_2;
    private $hora_inicio;
    private $hora_fin;

    /**
     * Metodo constructor de la clase Cliente
     * @param type $idCliente
     * @param type $nombre
     */
    function Cliente($idCliente = "def") {
        $this->id = $idCliente;
    }

    /**
     * 
     * @param type $id
     * @return Cliente
     */
    function getCliente() {
        require_once "database.php";
        $pdo = Database::connect();
        $query = "select id,nombre,usuario,referido from clientes where id=? order by nombre asc";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        $resultado = New Cliente($result['id']);
        $resultado->setNombre($result['nombre']);
        $resultado->setUsuario($result['usuario']);
        $resultado->setReferido($result['referido']);
        return $resultado;
    }

    /**
     * Metodo devuelve un array con la lista de todas las clientes
     * @return Array <Cliente>
     */
    function getClientes() {
        require_once "database.php";
        $pdo = Database::connect();
        //$query = "select * from clientes order by nombre asc";
        $result = $pdo->query($query);
        Database::disconnect();
        return $result;
    }

    /**
     * Metodo que actualiza el cliente en la base de datos
     * y si no esta lo crea
     * @return id de el cliente creada
     */
    function mergeCliente() {
        try {
            require_once "database.php";
            $pdo = Database::connect();
            $query = "insert into clientes set nombre = ?,apellido = ?,"
                    . "usuario = ?,email = ?,direccion = ?, "
                    . "direccion2 = ?,ciudad=?,pais = ?, referido=? ,medioPago = ?, "
                    . "estado_cuenta = ?,estado_pago = ?,contrasena = ? ,"
                    . "numero_ref_1 = ?,numero_ref_2 = ?,fecha_inicio=?,fecha_fin=? "
                    . "ON DUPLICATE KEY UPDATE nombre = ?,apellido = ?,"
                    . "usuario=?,direccion = ?, "
                    . "direccion2 = ?,ciudad=?,pais = ?,medioPago = ?, "
                    . "estado_cuenta = ?,estado_pago = ?,contrasena = ? ,"
                    . "numero_ref_1 = ?,numero_ref_2 = ?,fecha_inicio=?,fecha_fin=? ";

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $pdo->prepare($query);
            //para insert
            $stmt->bindParam(1, $this->nombre);
            $stmt->bindParam(2, $this->apellido);
            $stmt->bindParam(3, $this->usuario);
            $stmt->bindParam(4, $this->email);
            $stmt->bindParam(5, $this->direccion);
            $stmt->bindParam(6, $this->direccion2);
            $stmt->bindParam(7, $this->ciudad);
            $stmt->bindParam(8, $this->pais);
            $stmt->bindParam(9, $this->referido);
            $stmt->bindParam(10, $this->medioPago);
            $stmt->bindParam(11, $this->estado_cuenta);
            $stmt->bindParam(12, $this->estado_pago);
            $stmt->bindParam(13, $this->contrasena);
            $stmt->bindParam(14, $this->numero_ref_1);
            $stmt->bindParam(15, $this->numero_ref_2);
            $stmt->bindParam(16, $this->hora_inicio);
            $stmt->bindParam(17, $this->hora_fin);
            // para update
            $stmt->bindParam(18, $this->nombre);
            $stmt->bindParam(19, $this->apellido);
            $stmt->bindParam(20, $this->usuario);
            $stmt->bindParam(21, $this->direccion);
            $stmt->bindParam(22, $this->direccion2);
            $stmt->bindParam(23, $this->ciudad);
            $stmt->bindParam(24, $this->pais);
            $stmt->bindParam(25, $this->medioPago);
            $stmt->bindParam(26, $this->estado_cuenta);
            $stmt->bindParam(27, $this->estado_pago);
            $stmt->bindParam(28, $this->contrasena);
            $stmt->bindParam(29, $this->numero_ref_1);
            $stmt->bindParam(30, $this->numero_ref_2);
            $stmt->bindParam(31, $this->hora_inicio);
            $stmt->bindParam(32, $this->hora_fin);

            $resultado = $stmt->execute();
            $this->id = $pdo->lastInsertId();
            $cliente = new Cliente($this->id);
            Database::disconnect();
            if ($resultado) {
                return $cliente;
            } else {
                return "*1* Error al tratar de crear Usuario:  " . $resultado;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                echo "*5* Error al tratar de crear usuario: Usuario ya Existe. ";
            } else {
                echo "*4* Error al tratar de crear Usuario: " . $e->getMessage();
            }
        } catch (Exception $e) {
            echo "*3* Error al tratar de crear Usuario:  " . $e->getMessage();
        }
    }

    function getClientePorEmail($email) {
        require_once "database.php";
        try {
            $pdo = Database::connect();

            //Se busca la contraseña para retornarla.            
            $query = 'select id,usuario,contrasena FROM clientes WHERE email=?';
            $stmt = $pdo->prepare($query);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            Database::disconnect();
            $cliente = new Cliente();
            $cliente->setId($result['id']);
            $cliente->setContrasena($result['contrasena']);
            $cliente->setUsuario($result['usuario']);
            return $cliente;
        } catch (Exception $e) {
            return "*3* Error al tratar de actualizar datos de Usuario:  " . $e->getMessage();
        }
    }

    function actualizarEstado($idCliente, $estado_cuenta, $estado_pago, $fecha_fin, $numero_ref_1, $numero_ref_2) {
        try {
            require_once "database.php";
            $pdo = Database::connect();
            $query = 'UPDATE clientes SET estado_cuenta=?, 
                    estado_pago=?,numero_ref_1=?,
                    numero_ref_2=?,fecha_fin=? WHERE id=?';

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $pdo->prepare($query);
            //para insert
            $stmt->bindParam(1, $estado_cuenta);
            $stmt->bindParam(2, $estado_pago);
            $stmt->bindParam(3, $numero_ref_1);
            $stmt->bindParam(4, $numero_ref_2);
            $stmt->bindParam(5, $fecha_fin);
            $stmt->bindParam(6, $idCliente);

            $resultado = $stmt->execute();
            Database::disconnect();
            if ($resultado) {
                return "Exito";
            } else {
                return "*1* Error al tratar de actualizar datos Usuario:  " . $resultado;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return "*5* Error al tratar de actualizar cliente usuario: Usuario Existente. ";
            } else {
                return "*4* Error al tratar de actualizar datos de Usuario: " . $e->getMessage();
            }
        } catch (Exception $e) {
            return "*3* Error al tratar de actualizar datos de Usuario:  " . $e->getMessage();
        }
    }

    function validarPagoCliente() {
        require_once "database.php";
        $pdo = Database::connect();
        $query = 'select count(*) total FROM clientes WHERE estado_pago="APROBADO" and email = ?';
        $stmt = $pdo->prepare($query);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if ($result['total'] > 0) {
            return 'Cliente ya realizo pago';
        } else {
            return 'Exito';
        }
    }

    function validarUserName() {
        require_once "database.php";
        $pdo = Database::connect();
        $query = 'select count(*)total FROM clientes WHERE usuario=? and email != ?';
        $stmt = $pdo->prepare($query);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt->bindParam(1, $this->usuario);
        $stmt->bindParam(2, $this->email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        $cliente = new Cliente();
        if ($result['total'] > 0) {
            return 'Usuario ya existe';
        } else {
            return 'Exito';
        }
    }
    
    /**
     * Obtener Usuario con user y clave para luego validar
     * @return Usuario
     */
    function getUsuarioClave() {
        require_once "database.php";
        $pdo = Database::connect();
        $query = "select id,usuario from clientes where usuario=? and contrasena=? and estado_cuenta='ACTIVO'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $this->usuario);
        $stmt->bindParam(2, $this->contrasena);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        if (!empty($result)){
            $resultado = New Cliente($result['id']);
            $resultado->setUsuario($result['usuario']);
            $resultado->setReferido($result['referido']);
            return $resultado;
        }
        else {
            return null;
        }
        
    }
    
    /**
     * Metodo que crea la sesion del usuario
     * @return string Exito si crea la sesion correctamente , de lo contrario 
     * retorna el error generado
     */
    function crearSesion($user){
        try {
                require_once "sesion.php";
                $sesion = Sesion::crearSesion($user);
                if ($sesion=="Exito") {
                    return "Exito";
                }
        else { 
            return "*2* Error al tratar de crear sesion:  " . $sesion;
        }
        } catch (Exception $e) {
                echo "*3* Error al tratar de crear sesion:  " . $e->getMessage();
        }
    }
    
    /**
     * Metodo que cierra y elimina la sesion del usuario
     * @return string Exito si cierra la sesion correctamente , de lo contrario 
     * retorna el error generado
     */
    function cerrarSesion(){
        try {
                require_once "sesion.php";
                $sesion = Sesion::cerrarSesion();
                if ($sesion=="Exito") {
                    return "Exito";
                }
        else { 
            return "*1* Error al tratar de crear sesion:  " . $sesion;
        }
        } catch (Exception $e) {
                echo "*2* Error al tratar de crear sesion:  " . $e->getMessage();
        }
    }
    
     /**
     * Metodo que obtiene el usuario de la sesion actual
     * @return string Usuario que esta en sesion actualmente,
     * retorna el usuario con los privilegios
     */
    function getSesion(){
        try {
                require_once "sesion.php";
                $usuario = Sesion::getSesion();
                if ($usuario!=null) {
                    return $usuario;
                }
                else { 
                    return "*1* Error : no existe Sesion";
                }
        } catch (Exception $e) {
                return "*2* Error al tratar de obtener sesion:  " . $e->getMessage();
        }
    }
    
    
    /**
     * Verificar Usuario
     * @return Usuario
     */
    function verificarUsuario() {
        require_once "database.php";
        $pdo = Database::connect();
        $query = "select id from clientes where id=? and usuario=?";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();   

        $user = new Cliente($result['id']);        
        $user=$user->getCliente();
        return $user;
    }

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getEmail() {
        return $this->email;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getDireccion2() {
        return $this->direccion2;
    }

    function getPais() {
        return $this->pais;
    }

    function getMedioPago() {
        return $this->medioPago;
    }

    function getEstado_cuenta() {
        return $this->estado_cuenta;
    }

    function getEstado_pago() {
        return $this->estado_pago;
    }

    function getContrasena() {
        return $this->contrasena;
    }

    function getNumero_ref_1() {
        return $this->numero_ref_1;
    }

    function getNumero_ref_2() {
        return $this->numero_ref_2;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setDireccion2($direccion2) {
        $this->direccion2 = $direccion2;
    }

    function setPais($pais) {
        $this->pais = $pais;
    }

    function setMedioPago($medioPago) {
        $this->medioPago = $medioPago;
    }

    function setEstado_cuenta($estado_cuenta) {
        $this->estado_cuenta = $estado_cuenta;
    }

    function setEstado_pago($estado_pago) {
        $this->estado_pago = $estado_pago;
    }

    function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    function setNumero_ref_1($numero_ref_1) {
        $this->numero_ref_1 = $numero_ref_1;
    }

    function setNumero_ref_2($numero_ref_2) {
        $this->numero_ref_2 = $numero_ref_2;
    }

    function getCiudad() {
        return $this->ciudad;
    }

    function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    function getHora_inicio() {
        return $this->hora_inicio;
    }

    function getHora_fin() {
        return $this->hora_fin;
    }

    function setHora_inicio($hora_inicio) {
        $this->hora_inicio = $hora_inicio;
    }

    function setHora_fin($hora_fin) {
        $this->hora_fin = $hora_fin;
    }
    public function getReferido() {
        return $this->referido;
    }

    public function setReferido($referido) {
        $this->referido = $referido;
    }


    

}
