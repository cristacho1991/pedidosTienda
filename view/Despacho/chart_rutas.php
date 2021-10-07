<?php 

$json = (file_get_contents('php://input',true));
$post = json_decode($json,true);
$placa=$post['placa'];
$fecha=$post['fecha'];


	  # conectare la base de datos
	
	$con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);
	$sql="select Facturas.cliente,(select tclientes.longitud from tclientes where tclientes.codcliente=facturas.cliente)as longitud
	,(select tclientes.latitud from tclientes where tclientes.codcliente=facturas.cliente)as latitud,
	(select tclientes.razonsocia from tclientes where tclientes.codcliente=facturas.cliente)as razonsocia,
	(select tclientes.ruc from tclientes where tclientes.codcliente=facturas.cliente)as ruc from FACTURAS,
	GUIASR  WHERE FACTURAS.NUMGUIAR=GUIASR.numeroguia AND GUIASR.codtransp=$placa AND GUIASR.fechaf='$fecha'";
	$query=mysqli_query($con,$sql);
	$features = [];		
	$i=0;
	while($row=mysqli_fetch_array($query)){
		 $lat=$row['latitud'];
		$long=$row['longitud'];
		//$propiedades1=array ('title'=> $row['razonsocia'],'description'=> $row['ruc']);
		$arreglo_datos=array ('type' => 'Feature','geometry' =>  array ('type' => 'LineString','coordinates' => array (0 => $long,1 => $lat)));
        $features += ["$i" =>$arreglo_datos ];
		$i++;
	}

$array_multi=$features;
$data=array ('type' => 'FeatureCollection','features' => $features);

echo json_encode($data);

?>