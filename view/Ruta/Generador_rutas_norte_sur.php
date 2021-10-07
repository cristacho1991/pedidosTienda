
<?php

//if (isset($_POST['valor'])){
//	$valor=$_POST['valor'];}
# conectare la base de datos
$db_host = "95.216.196.50:3306";
$db_user = "zicomtec_useradmin";
$db_pass = '$_v@l&t077_$';
$db_name = "zicomtec_dataz";
$con = @mysqli_connect($db_host, $db_user, $db_pass, $db_name, 3306);
$sqlzonas = "select distinct zonas.nombrezona from zonas";
$resultzonas = mysqli_query($con, $sqlzonas);

$features = [];
$data = [];
$nombrezonas = [];
$nombrezonass = [];
$h = 0;

while ($row = mysqli_fetch_array($resultzonas)) {

	$nombrezonass[$h] = $row['nombrezona'];
	$h++;
}

$k = 0;
for ($i = 0; $i < sizeof($nombrezonass); $i++) {

	$nombre = $nombrezonass[$i];
	$sql = "select zonas.latitud,zonas.longitud from zonas where nombrezona='$nombre'";

	$result = mysqli_query($con, $sql);

	while ($row = mysqli_fetch_array($result)) {

		$arreglo_datos = array('lat' => $row['latitud'], 'lng' => $row['longitud']);
		$features += ["$k" => $arreglo_datos];
		$k++;
	}

	echo json_encode($features);
}


?>