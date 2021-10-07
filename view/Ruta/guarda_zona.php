<?php 

$parametros2 = (file_get_contents('php://input',true));
$parametros3 = json_decode($parametros2, true );

var_dump($parametros3);



	  # conectare la base de datos
	  $db_host="95.216.196.50:3306";
	  $db_user="zicomtec_useradmin";
	  $db_pass='$_v@l&t077_$'	;
	  $db_name="zicomtec_dataz";
    $con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);

$codClientes=Array();


$codigo = $parametros3['codigo'];

foreach ($parametros3['poligono'] as $p) {

	$lat = $p['lat'];
	$lng = $p['lng'];
	$sql = mysqli_query($con, "insert into zonas(nombrezona,latitud,longitud) values ('$codigo',$lat,$lng); ");
}
$i = 0;
foreach ($parametros3['marcadores'] as $m) {

	$latCli = $m['lat'];
	$lngCli = $m['lng'];
	$sql2 = mysqli_query($con, "select * from tclientes where latitud LIKE '$latCli%' and longitud LIKE '$lngCli%';");
	
	while ($row = mysqli_fetch_array($sql2)) {

		$codClientes[$i] = $row['codcliente'];
		$i++;
	}
}

for ($j=0; $j < sizeof($codClientes) ; $j++) { 
	$codCli=strval($codClientes[$j]);
	$numeroConCeros = str_pad($codCli, 6, "0", STR_PAD_LEFT);


	$sql3 = mysqli_query($con, "insert into rcodificacion(codificacion,codcliente) values ('$codigo','$numeroConCeros'); ");
}

$data = "OK";
echo $data;

?>