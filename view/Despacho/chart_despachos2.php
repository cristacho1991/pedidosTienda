<?php 

/*/$json = (file_get_contents('php://input',true));
$post = json_decode($json,true);
$placa=$post['placa'];
$fecha=$post['fecha'];
/*/

if (isset($_POST['placa'])){$placa=$_POST['placa'];;}
if (isset($_POST['fecha'])){$fecha=$_POST['fecha'];}

	  # conectare la base de datos
	  
	  $db_host="95.216.196.50:3306";
	  $db_user="zicomtec_useradmin";
	  $db_pass='$_v@l&t077_$';
	  $db_name="zicomtec_dataz";
	$con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
	$sql="select DISTINCT rutasd.codcliente ,(select tclientes.longitud from tclientes where tclientes.codcliente=rutasd.codcliente)as longitud,
	(select tclientes.latitud from tclientes where tclientes.codcliente=rutasd.codcliente)as latitud,(select tclientes.razonsocia from tclientes 
	where tclientes.codcliente=rutasd.codcliente)as razonsocia,(select tclientes.direccion from tclientes where tclientes.codcliente=rutasd.codcliente)as direccion
	from rutasd where codtransp='$placa' and fecha='$fecha'group by codcliente";
	

	$result=mysqli_query($con,$sql);
	$features = [];		
	$i=0;
	while ($row = mysqli_fetch_array($result)) {
		
		$arreglo_datos=array ('direccion' => $row['direccion'] ,'latitud' => $row['latitud'], 'longitud'=> $row['longitud'],'razon'=> $row['razonsocia']);
		$features += ["$i" =>$arreglo_datos ];
		$i++;	
	}

	echo json_encode($features);

	/*/$features = [];		
	$i=0;
	while($row=mysqli_fetch_array($query)){
		 $lat=$row['latitud'];
		$long=$row['longitud'];
		$propiedades1=array ('title'=> $row['razonsocia'],'description'=> $row['ruc']);
		$arreglo_datos=array ('type' => 'Feature','properties' => $propiedades1,'geometry' =>  array ('type' => 'Point','coordinates' => array (0 => $long,1 => $lat)));
        $features += ["$i" =>$arreglo_datos ];
		$i++;
	}

$array_multi=$features;
$data=
array ('type' => 'FeatureCollection','features' => $features);

echo json_encode($data);/*/

?>