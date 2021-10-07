<?php 




	  # conectare la base de datos
	  $db_host="95.216.196.50:3306";
	  $db_user="zicomtec_useradmin";
	  $db_pass='$_v@l&t077_$'	;
	  $db_name="zicomtec_dataz";
    $con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);
	$sql="select tclientes.latitud,tclientes.longitud,tclientes.direccion,tclientes.razonsocia from tclientes where latitud is not null order by latitud desc";
	

	$result=mysqli_query($con,$sql);
	
	$features = [];		
	$i=0;
	while ($row = mysqli_fetch_array($result)) {
		
		$arreglo_datos=array ('direccion' => $row['direccion'] ,'latitud' => $row['latitud'], 'longitud'=> $row['longitud'], 'razon'=>$row['razonsocia']);
		$features += ["$i" =>$arreglo_datos ];
		$i++;
	}

	//$data=array ('features' => $features);
	echo json_encode($features);

?>