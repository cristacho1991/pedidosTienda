<?php
// include the configs / constants for the database connection
require_once("config/db.php");
// load the login class
require_once("classes/Login.php");
// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();
	// ... ask if we are logged in here:
	if ($login->isUserLoggedIn() == true) 
	{	
		/* Connect To Database*/
		require_once ("config/conexion.php");
		//Inicia Control de Permisos
		/*/include("./config/permisos.php");
		$user_id = $_SESSION['user_id'];
		get_cadena($user_id);
		$modulo="Productos";
		permisos($modulo,$cadena_permisos);/*/
		//Finaliza Control de Permisos
		$title="Agregar cliente | Sistema de Pedidos";
		
		$catalog=1;
		$products=1;
		include('view/products/add.php');//Include file with the view
		
		
	}
	else
	{
		header("location: login.php");
		exit;		
	}
?>