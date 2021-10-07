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
	<th>TOTAL FACTURA</th>
	<th>SALDO</th>
	<th>ORDEN</th>
	
</tr>
<?php

	$sql=mysqli_query($con, "select DISTINCT detrecimesruta.codcliente,detrecimesruta.fecha,(select tclientes.longitud from tclientes where tclientes.codcliente=detrecimesruta.codcliente)as longitud,
	(select tclientes.latitud from tclientes where tclientes.codcliente=detrecimesruta.codcliente)as latitud ,(select tclientes.razonsocia from tclientes 
	where tclientes.codcliente=detrecimesruta.codcliente)as razonsocia,(select tclientes.ruc from tclientes where tclientes.codcliente=detrecimesruta.codcliente)as ruc,
	sum(totalfactu) as totalfactu,sum(saldo) as saldo, detrecimesruta.orden from detrecimesruta where codtransp=$placa and detrecimesruta.fecha='$fecha'group by codcliente");
	$i=0;
	while ($row=mysqli_fetch_array($sql))
	{
	
	$razonsocia=$row['razonsocia'];
	$totalfactu=$row['totalfactu'];
	$saldo=$row['saldo'];
	$orden=$row['orden'];

	?>
		<tr>
			
			<td class='text-center'><?php echo $razonsocia;?></td>
			<td class='text-center'><?php echo $totalfactu;?></td>
			<td class='text-center'><?php echo $saldo;?></td>
			<td class='text-center'><?php echo $orden;?></td>

			
		</tr>	
		<?php
		
	}


	?>


</table>

<?php
			
	}
	?>
