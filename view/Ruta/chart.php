<?php 


if (isset($_POST['vendedor_id'])){$id=$_POST['vendedor_id'];;}
if (isset($_POST['fecha'])){$fecha=$_POST['fecha'];}

	  # conectare la base de datos
	  $db_host="95.216.196.50:3306";
	  $db_user="zicomtec_useradmin";
	  $db_pass='$_v@l&t077_$'	;
	  $db_name="zicomtec_dataz";
    $con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);
	$sql="select detrutasvend.latitud,detrutasvend.longitud,detrutasvend.ciudad,detrutasvend.direccion,detrutasvend.codcliente,detrutasvend.imagen,
	(select tclientes.razonsocia as RazonSocial from tclientes where tclientes.codcliente=detrutasvend.codcliente) as RazonSocial from detrutasvend  
	where codven=$id and fecha='$fecha'";
	

	$result=mysqli_query($con,$sql);
	
	$features = [];		
	$i=0;
	while ($row = mysqli_fetch_array($result)) {
		
		$arreglo_datos=array ('direccion' => $row['direccion'] ,'latitud' => $row['latitud'], 'longitud'=> $row['longitud'], 'razon'=>$row['RazonSocial']);
		$features += ["$i" =>$arreglo_datos ];
		$i++;
	}

	//$data=array ('features' => $features);
	echo json_encode($features);

?>