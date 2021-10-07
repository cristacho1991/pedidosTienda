<?php

// include the configs / constants for the database connection
require_once("config/db.php");
// load the login class
require_once("classes/Login.php");
// create a login object. when this object is created,it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();
	// ... ask if we are logged in here:
	if ($login->isUserLoggedIn() == true) 
	{	
		
		require_once ("config/conexion.php");
		
		$title="Agregar orden de compra | Sistema de Pedidos";
		$purchases=1;
		$purchase_order=1;
		
		include('view/purchases/add_order.php');//Include file with the view
		
		
	}
	else
	{
		header("location: login.php");
		exit;		
	}
?>