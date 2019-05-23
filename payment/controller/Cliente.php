<?php
/*
 * incluye el modelo cliente, con los metodos y acceso 
 * a la base de datos
 */
include "../model/Cliente.php";
/**
 * recibe por POST el metodo segun
 * el proceso que valla a realizar
 */
if (isset($_POST["option"])) {
    $metodo = $_POST["option"];
}
//si no recibe metodo
else {
    die('301:Error, no existe dirección');
}

/**
 * Direcciona al metodo que se recibe
 */
switch ($metodo) {
    case "register":
        registrar();
        break;
    case "crearSesion":
        crearSesion();
        break;
    case "cerrarSesion":
        cerrarSesion();
        break; 
    case "validarSesion":
        validarSesion();
        break; 
    case "datosUsuario":
        datosUsuario();
        break; 
    default:
        die('No se encontro accion relacionada');
}

/**
 * Crear una cliente
 * @param Post nombreCliente recibe nombre de el cliente que 
 * va a crear
 */
function registrar() {

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $address2 = $_POST["address2"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $refer = $_POST["refer"];
    $paymentMethod = $_POST["paymentMethod"];

    $estadoCuenta = "INACTIVO";
    $estadoPago = "PENDIENTE";
    //$contraseña = "PROVISIONAL123";
    $contraseña=substr(md5(uniqid()), 0, 8);
    $numRef1 = "0";
    $numRef2 = "0";
    
    if ($refer=='AHNX7DIM086132') {
        $refer="Alejandro Lopera";
    }else if($refer=='Z4UNMSIM827476'){
        $refer="Smart Training Club";
    }else{
         $refer="Invertir Mejor";
    }

    $hora_inicial = $date = date("Y-m-d H:i:s");
    $cliente = new Cliente();
    $cliente->setNombre($firstName);
    $cliente->setApellido($lastName);
    $cliente->setUsuario($username);
    $cliente->setEmail($email);
    $cliente->setDireccion($address);
    $cliente->setDireccion2($address2);
    $cliente->setCiudad($city);
    $cliente->setPais($country);
    $cliente->setReferido($refer);
    $cliente->setMedioPago($paymentMethod);
    $cliente->setEstado_cuenta($estadoCuenta);
    $cliente->setEstado_pago($estadoPago);
    $cliente->setContrasena($contraseña);
    $cliente->setNumero_ref_1($numRef1);
    $cliente->setNumero_ref_2($numRef2);
    $cliente->setHora_inicio($hora_inicial);
    $cliente->setHora_fin(null);

    $respuesta = $cliente->validarPagoCliente();
    if ($respuesta != "Exito") {
        die("Ya se realizo compra exitosa con este correo!");
    }
    $respuesta = $cliente->validarUserName();
    if ($respuesta != "Exito") {
        die("Usuario ya existe, intente con otro!");
    }

    $respuesta = $cliente->mergeCliente();

    if (is_object($respuesta)) {
        echo "Exito";
    } else {
        echo $respuesta;
    }
}

    /**
    * Crear una sesion
    * @param Post nombreUsuario recibe nombre de la usuario que 
    * va a crear
    */
    function crearSesion(){
        $userName = $_POST["usuario"];        
        $clave = $_POST["clave"];        
        
        $usuario = new Cliente();
        $usuario->setUsuario($userName);
        $usuario->setContrasena($clave);
        $user = $usuario->getUsuarioClave();

        if ($user!=null) {    
            $userSesion=$user->crearSesion($user);
            if ($userSesion=="Exito") {
                echo "Exito";                
            }
            else {
                 echo $userSesion;
               }

        } else {
            echo "Error de usuario o contraseña";
        }
     }
     
     /**
    * confirma que exista la sesion de usuario 
    */
    function validarSesion(){        
        $usuario = new Cliente();
        $usuario= $usuario->getSesion();
        if (is_object($usuario)) {
           die("true");
        }
        else{
            die("false");
        }
        
    }
     
     /**
     * Cerrar una sesion
     */
    function cerrarSesion(){
        $usuario = new Cliente();
        $resultado = $usuario->cerrarSesion();        
        echo $resultado;
     }
     
     function datosUsuario(){
         $usuario = new Cliente();
         $usuario= $usuario->getSesion();
         if (!is_object($usuario)) {
               die("No hay sesion Abierta");
          }
        
        $usuario = new Cliente($usuario->getId());
        if (is_object($usuario)) {
            $resultado = $usuario->getCliente();        
            die ("Usuario: ".$resultado->getUsuario()." <br>Nombre: ".$resultado->getNombre()." <br>Referido: ".$resultado->getReferido()."<br><br>");
        }
        echo "Error";
         
         
     }
?>