<?php

require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
	
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
		$title="Clientes | Sistema de Pedidos";
		$catalog=1;
		$products=1;
		
		include('view/clientes/list.php');//Include file with the view
	}
	else
	{
		header("location: login.php");
		exit;		
	}
?>