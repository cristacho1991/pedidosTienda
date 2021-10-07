<?php 
#compruebo si esta logueado
session_start();
if (!isset($_SESSION['nombre'])){
	header("location: ../");//Redirecciona 
	exit;
}



function moneyFormat($price,$curr) {
	$currencies['EUR'] = array(2, ',', '.');        // Euro
	$currencies['ESP'] = array(2, ',', '.');        // Euro
	$currencies['USD'] = array(2, '.', ',');        // US Dollar
	$currencies['COP'] = array(2, ',', '.');        // Colombian Peso
	$currencies['CLP'] = array(0,  '', '.');        // Chilean Peso

	return number_format($price, ...$currencies[$curr]);
}
?>