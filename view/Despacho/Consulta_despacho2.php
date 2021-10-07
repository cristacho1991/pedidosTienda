<?php 

if (isset($_POST['placa'])){$placa=$_POST['placa'];}
if (isset($_POST['fecha'])){$fecha=$_POST['fecha'];}

if (!empty($placa) and !empty($fecha)){
	$db_host="95.216.196.50:3306";
	$db_user="zicomtec_useradmin";
	$db_pass='$_v@l&t077_$'	;
	$db_name="zicomtec_dataz";
$con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$calculohora[]="";

?>
<table class="table table-striped">
<tr>
	
	<th class='text-center'>RAZONSOCIAL</th>
	<th class='text-center'>DIRECCION</th>
	<th>FECHA</th>
	
</tr>
<?php

	$sql=mysqli_query($con,"select DISTINCT rutasd.codcliente ,(select tclientes.longitud from tclientes where tclientes.codcliente=rutasd.codcliente)as longitud,
	(select tclientes.latitud from tclientes where tclientes.codcliente=rutasd.codcliente)as latitud,(select tclientes.razonsocia from tclientes 
	where tclientes.codcliente=rutasd.codcliente)as razonsocia,(select tclientes.direccion from tclientes where tclientes.codcliente=rutasd.codcliente)as direccion,rutasd.fecha
	from rutasd where codtransp='$placa' and fecha='$fecha'group by codcliente");
	$i=0;
	while ($row=mysqli_fetch_array($sql))
	{
	
	$razonsocia=$row['razonsocia'];
	$direccion=$row['direccion'];
	$fecha=$row['fecha'];
	

	?>
		<tr>
			
			<td class='text-center'><?php echo $razonsocia;?></td>
			<td><?php echo $direccion;?></td>
			<td><?php echo $fecha;?></td>

			
		</tr>	
		<?php
		
	}


	?>


</table>

<?php
			
	}
	?>
