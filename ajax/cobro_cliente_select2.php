<?php
include("is_logged.php");//Archivo comprueba si el usuario esta logueado
// connect to database
 require_once ("../config/db.php");
 require_once ("../config/conexion.php");
// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
$search = strip_tags(trim($_GET['q'])); 
// Do Prepared Query
$query = mysqli_query($con, "SELECT * FROM tclientes WHERE razonsocia LIKE '%$search%' OR ruc LIKE '%$search%' LIMIT 40");
// Do a quick fetchall on the results
$list = array();
while ($list=mysqli_fetch_array($query)){
	$data[] = array('id' => $list['ruc'], 'text' => $list['razonsocia']);
}
// return the result in jsonff
echo json_encode($data);
?>