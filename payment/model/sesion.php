<?php

class Sesion {

    private static $user = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function crearSesion($user) {
        // One connection through whole application
        if (null == self::$user) {
            try {
                if(!isset($_SESSION)){session_start();}                  
                self::$user = $user;
                $_SESSION['usuario']=self::$user;
                return "Exito";
            } catch (PDOException $e) {
                die("*1* Error al intentar crear sesion: " . $e->getMessage());
            }
        }
    }
    public static function cerrarSesion() {
       try { 
        if(!isset($_SESSION)){session_start();}
        session_regenerate_id();
        session_destroy();
        self::$user = null;
        return "Exito";
       } catch (PDOException $e) {
          die("*1*Error: No se cerro Sesion: " . $e->getMessage());
       }
    }
    public static function getSesion() {
        if(!isset($_SESSION)){session_start();}
        if(isset($_SESSION['usuario'])){
            $user=$_SESSION['usuario'];
            return $user;          
        }        
            return null;
    }    

}

?>