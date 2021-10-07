<?php 

if (isset($_POST['vendedor_id'])){$vendedor_id=$_POST['vendedor_id'];}
if (isset($_POST['fecha'])){$fecha=$_POST['fecha'];}

if (!empty($vendedor_id) and !empty($fecha)){
	$db_host="95.216.196.50:3306";
	$db_user="zicomtec_useradmin";
	$db_pass='$_v@l&t077_$'	;
	$db_name="zicomtec_dataz";
$con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$calculohora[]="";

?>
<table class="table table-striped">
<tr>
	<th class='text-center'>CODIGO VENDEDOR</th>
	<th>FECHA</th>
	<th class='text-center'>HORA</th>
	<th>CLIENTE</th>
	<th>DIRECCIÓN</th>
	
	
</tr>
<?php

	$sql=mysqli_query($con, "select detrutasvend.codven,detrutasvend.fecha,detrutasvend.hora,
	detrutasvend.direccion,detrutasvend.codcliente,
	(select tclientes.razonsocia  from tclientes where tclientes.codcliente=detrutasvend.codcliente) as RazonSocial
	 from detrutasvend  where codven= $vendedor_id and fecha= '$fecha' order by detrutasvend.hora");
	

	$i=0;
	while ($row=mysqli_fetch_array($sql))
	{
	$codven=$row['codven'];
	$fecha=$row['fecha'];
	$hora=$row['hora'];
	$cliente=$row['codcliente'];
	$direccion=$row['direccion'];
	$razon=$row['RazonSocial'];

	$calculohora[$i]=$hora;

		?>
		<tr>
			
			<td class='text-center'><?php echo $codven;?></td>
		
			<td><?php echo $fecha;?></td>
			<td><?php echo $hora;?></td>
			<td><?php echo $razon;?></td>
			<td><?php echo $direccion;?></td>
			
		</tr>	
		<?php
	$i++;	
	}


	?>


</table>
<?php
	$posfin=(sizeof($calculohora)-1);
	$fin=$calculohora[$posfin];
	$inicio=$calculohora[0];
		$dif=date("H:i:s", strtotime("00:00:00") + strtotime($fin)-strtotime($inicio));
		echo "El tiempo usado fue de: ".$dif;
		
	}



	?>

	
	
