<?php

include_once 'Conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();


//recepción de datos enviados mediante POST desde ajax
$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';


$consulta = "SELECT * FROM user_details WHERE username='$usuario'";
$resultado = $conexion->prepare($consulta);
$resultado->execute();



if($resultado->rowCount() >= 1){

    $data = $resultado->fetch(PDO::FETCH_ASSOC); //FETCH O FETCHALL para solo la primera row o todas de la consulta
   // echo $data['username'];
   
    if (password_verify($password, $data['password'])) { //SE VERIFICA LA CONTRASEÑA QUE DEIGITA EL USUARIO
      
        session_name("CJ_JUDAS"); //NOMBRE DE LA SESION PHP EN EL SERVIDOR
        session_set_cookie_params(28800);  //TIEMPO FIJO DE CIERRE DE SESIÓN EN COOKIES - 8 HORAS POR DEFECTO
        session_start(); //INICIA LA SESIÓN
        
        //USO DE VARIABLES DE SESIÓN, SOLO SERÁN USABLES MIENTRAS LA SESIÓN ESTE ABIERTA
        $_SESSION["Segundos_de_vida"] = 900; //SEGUNDOS DE VIDA DE LA SESIÓN - 15 MINUTOS (900s) POR DEFECTO
        $_SESSION["Hora_de_login"] = time(); //TIEMPO EN QUE SE CONECTA EL USUARIO
        $_SESSION["s_usuario"] = $usuario; // SE CAPTURA EL NOMBRE DE USUARIO CONECTADO
        $_SESSION["s_Nombres"] = $data['first_name']; //SE CAPTURA NOMBRE DEL USUARIO CONECTADO
        $_SESSION["s_Apellidos"] = $data['last_name']; //SE CAPTURA APELLIDOS DE USUARIO CONECTADO
      
        // setcookie ("CJ_Nombre_Usuario",$data['first_name']);
        // setcookie ("CJ_Apellidos_Usuario",$data['last_name']);

        
        
    }else{
        $_SESSION["s_usuario"] = null;
        $data=null;
    }
    
    
}else{
    $_SESSION["s_usuario"] = null;
    $data = null;
}


print json_encode($data);
$objeto = null;
$consulta = null;
//$resultado -> closeCursor;
$resultado = null;
$conexion = null;

//usuarios de pruebaen la base de datos
//usuario:admin pass:12345
//usuario:demo pass:demo