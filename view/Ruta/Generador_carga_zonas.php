<?php 

if (isset($_POST['valor'])){
	$valor=$_POST['valor'];}
	  # conectare la base de datos
	  $db_host="95.216.196.50:3306";
	  $db_user="zicomtec_useradmin";
	  $db_pass='$_v@l&t077_$'	;
	  $db_name="zicomtec_dataz";
    $con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);
	$sql="select zonas.nombrezona,zonas.latitud,zonas.longitud from zonas where zonas.nombrezona='$valor'";
	
	$result=mysqli_query($con,$sql);
	
	$features = [];		
	$i=0;
	while ($row = mysqli_fetch_array($result)) {
		
		$nombrezona=$row['nombrezona'];
		$arreglo_datos=array ('lat' => $row['latitud'], 'lng'=> $row['longitud']);
		$features += ["$i" =>$arreglo_datos ];
		$i++;
	}


	$sql2="select rcodificacion.codcliente, (Select tclientes.razonsocia from tclientes where tclientes.codcliente=rcodificacion.codcliente) as RazonSocia, 
	(Select tclientes.direccion from tclientes where tclientes.codcliente=rcodificacion.codcliente) as direccion,
	(Select tclientes.latitud from tclientes where tclientes.codcliente=rcodificacion.codcliente) as latitud,
	(Select tclientes.longitud from tclientes where tclientes.codcliente=rcodificacion.codcliente) as longitud from rcodificacion  where rcodificacion.codificacion='$valor'";
	
	$result2=mysqli_query($con,$sql2);
	
	$features2 = [];		
	$j=0;
	while ($row2 = mysqli_fetch_array($result2)) {
		
		$arreglo_datos2=array ('clientes' => $row2['codcliente'],'razonsocia' => $row2['RazonSocia'],'direccion' => $row2['direccion'],'latitud' => $row2['latitud'],'longitud' => $row2['longitud']);
		$features2 += ["$j" =>$arreglo_datos2];
		$j++;
	}

	$data=array ('nombrezona' => $nombrezona,'poligono' => $features, 'marcadores' => $features2);
	echo json_encode($data);

?>