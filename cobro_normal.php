<?php

require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
	
	if ($login->isUserLoggedIn() == true) 
	{	
		/* Connect To Database*/
		require_once ("config/conexion.php");
		
		
		$title="Cobros | Sistema de Pedidos";
		$catalog=1;
		$products=1;
		
		include('view/cobros/cobronormal.php');//Include file with the view
	}
	else
	{
		header("location: login.php");
		exit;		
	}
?>