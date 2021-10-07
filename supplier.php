<?php

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
		
		$title="Cliente | Sistema de Pedidos";
		$suppliers=1;
		$contacts=1;
		include('view/suppliers/list.php');//Include file with the view
	}
	else
	{
		header("location: login.php");
		exit;		
	}
?>