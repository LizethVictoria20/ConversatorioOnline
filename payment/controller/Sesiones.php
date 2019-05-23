<?php

/**
 * Validar sesiones segun los diferentes roles
 * si el usuario esta autorizado para realizar una accion
 * lo deja continuar , de lo contrario , no lo deja acceder
 * 
 * @author David
 */
require_once 'model/Cliente.php';

    function user(){
        $user= new Cliente();
        $usuario=$user->getSesion();    
        if(is_object($usuario)){
             $usuario=$usuario->verificarUsuario();
             if ($usuario->getId()!=null) {
                return true;
             }             
        }
        $user->cerrarSesion();
        die('<script> window.location="login.html"; </script>');
    }
    
    
    
