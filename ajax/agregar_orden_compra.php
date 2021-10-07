<?php
setlocale(LC_MONETARY,'en_US');	
include("is_logged.php");//Archivo comprueba si el usuario esta logueado

$curr='USD';

$purchase_order_id=$_SESSION['numerofac'];
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);
echo "$numfacturacompleta";
if (isset($_POST['id'])){$id=$_POST['id'];;}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){floatval($descuento=$_POST['descuento']);}



	/* Connect To Database*/
	require_once ("../config/db.php");//Ccodigoontiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	$pordesc1=($qty*$unit_price)-(($qty*$unit_price)-($descuento/100));	

	$subtotal = ($qty * $unit_price);
	$insert = mysqli_query($con, "INSERT INTO zicomtec_dataz.moddetfacturasped(id,nrofact,idproducto,descripcion,cant,preciouni,subtotal,pordesc1)
VALUES (null,'$numfacturacompleta','$id','',$qty,$unit_price,$subtotal,$pordesc1)");

	//$insert=mysqli_query($con,"INSERT INTO zicomtec_dataz.detfacturasped(empresa,sucursal,nrolinea,nrofactura,codigo,numordent,fechav,observa,bodega,cantidad,cantidadc,preciouni,porcdesc1,porcdesc2,costotal,preciooc,lp,ancho,largo,saldo,tipo,iva,promocion,ordent,tecnico,tiempo,tiempor,facturaot,pvp,factor,refvidrio) VALUES ('01','01',1,'$numfacturacompleta','$id',NULL,NULL,NULL,'01','$qty','$qty','$unit_price','$pordesc1',0,0,0,NULL,NULL,NULL,'$qty','F',0,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,NULL)");

//ELIMINAR

echo mysqli_error($con);
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM detfacturasped WHERE codigo=$id and nrofactura='$numfacturacompleta'");

}

/////////////

if (isset($_REQUEST['campo'])){
	$campo=intval($_REQUEST['campo']);
	if ($campo==1){
		$valor=$_REQUEST['valor'];

		$rec=mysqli_query($con,"select * from tclientes where ruc='".$valor."'");
	while($row=mysqli_fetch_array($rec))
	{
	$id_cliente=$row['codcliente'];
			
	}	
		$str_update="cliente='".$id_cliente."'";
	} elseif ($campo==2){
		$valor=intval($_REQUEST['valor']);
		$str_update="anulada='".$valor."'";
	} elseif ($campo==4){
		$valor=intval($_REQUEST['valor']);
		$str_update="vendedor='".$valor."'";
	
	} elseif ($campo==5){
		$valor= mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$str_update="observacion='".$valor."'";}
	$update=mysqli_query($con,"UPDATE zicomtec_dataz.facturasped SET $str_update WHERE numerofac='".$numfacturacompleta."'");
	
}


//ACTUALIZA ITEM

if (isset($_REQUEST['item'])){
	$item=intval($_REQUEST['item']);
	$product_item=$_REQUEST['product_item'];


	if ($item==1){
		$valor=intval($_REQUEST['valor']);

		echo "VALOR CANTIDADDDD   ".$valor;
		$update=mysqli_query($con,"update detfacturasped set cantidad='$valor' where nrofactura='$numfacturacompleta' and codigo='$product_item'");
	}
	if ($item==2){
		$valor=mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$valor=str_replace(",","",$valor);
		$update=mysqli_query($con,"update purchase_order_product set unit_price='$valor' where id='$product_item'");
	}
	if ($item==3){
		$valor=intval($_REQUEST['valor']);
		if ($valor==1){
			$new_value=0;
		} else {
			$new_value=1;
		}
		$update=mysqli_query($con,"update purchase_order_product set oc='$new_value' where id='$product_item'");
	}
	 if ($item==4){
		 $new_value=intval($_REQUEST['valor']);
		$update=mysqli_query($con,"update purchase_order_product set qty_rec='$new_value' where id='$product_item'");
	}
	if ($item==5){
		 $new_value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$update=mysqli_query($con,"update purchase_order_product set status_oc='$new_value' where id='$product_item'");
	}
}


$includes_tax=0;
$tax=12;

	
?>
<table class="table">
<tr>
	<th>CODIGO</th>
	<th>DESCRIPCION</th>
	<th class='text-center'>CANTIDAD PEDIDA</th>
	<th class='text-center'>DISPONIBLE</th>


	<th>PRECIO UNITARIO</th>

	<th>DESCUENTO</th>
	<th>              </th>
	<th><span class="pull-right">SUBTOTAL</span></th>
	<th><span class="pull-right">IVA</span></th>
	<th>   </th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	
</tr>
<?php
$subtotalgrava=0;
$subtotalnograva=0;
$totaliva=0;
	$sumador_total=0;
	$sql = mysqli_query($con, "select id,idproducto,descripcion,cant,preciouni,pordesc1,(select costos.cantidad from costos where costos.codstock=moddetfacturasped.idproducto order by codstock desc limit 1) as disponible from moddetfacturasped where nrofact='$numfacturacompleta'");
	while ($row=mysqli_fetch_array($sql))
	{
		$id = $row['id'];
		$product_id = $row['idproducto'];
		$product_name = $row['descripcion'];
		$qty = $row['cant'];
		$unit_price = $row['preciouni'];
		$descuento = $row['pordesc1'];
		$disponible = $row['disponible'];
		$qtyfact = $row['cant'];
	$unit_price=moneyformat($unit_price,$curr);
	$subtotal=(($unit_price*$qty)-(($unit_price*$qty)*($descuento/100)));
	$subtotal=moneyFormat($subtotal,$curr);
	$sumador_total+=$subtotal;//Sumador
	$sumador_total=number_format($sumador_total,2,'.','');
		?>
		<tr>
			<!--CODIGO DEL PRODUCTO-->
			<td class='text-center'><?php echo $product_id;?></td>
			<!--NOMBRE DEL PRODUCTO-->
			<td><?php echo $product_name;?></td>

			<!--CANTIDAD-->
			<td class='text-center col-md-1'>
				<input type="number" style="text-align:center" value="<?php echo $qty;?>" class="form-control input-sm" onblur="actualizar_item(this.value,1,'<?php echo $id;?>')">
			</td>



			<td class='col-md-1'>
								
								<?php
									
									$query2=mysqli_query($con,"SELECT * FROM  costos where codstock=".$product_id." order by codstock desc limit 1");
									while ($list=mysqli_fetch_array($query2)){
										$stock=$list['cantidad'];
										?>
										 <input type="text" style="text-align:right" class="form-control input-sm" id="stock_<?php echo $id; ?>" value="<?php echo $stock;?>" disabled="disabled">
										<?php
									}
								?>
			</td>
			<!--PRECIO UNITARIO-->
			<td class="col-md-1">
				<input type="text" style="text-align:right" value="<?php echo $unit_price;?>" class="form-control input-sm" onblur="actualizar_item(this.value,2,'<?php echo $id;?>')" >
			</td>
			<!--DESCUENTO-->
			<td class="col-md-1">
			<input style="text-align:right" value="<?php echo $descuento;?>" class="form-control input-sm"  onblur="actualizar_item(this.value,3,'<?php echo $id;?>')" >
			</td>

			<!--OBTENGO IVA-->
				<?php
						$rec=mysqli_query($con,"select * from tstock where codstock='".$product_id."'");
						while($row=mysqli_fetch_array($rec))
						{
						$includes_tax=$row['iva'];
				
				?>
				<td><span class="pull-right" style="display:none "></span></td>
				<?php			
						}
				?>
	
		
		<!--SUBTOTAL-->
			<td><span class="pull-right">
			<?php echo $subtotal;?></span></td>
			
		<!--IVA 12% O 0%-->
			<?php
				
				if($includes_tax==1){
					$iva=moneyFormat(($subtotal*0.12),$curr);
					$subtotalgrava+=$subtotal;
					$totaliva+=$iva;
					?>

					<td><span class="pull-right"><?php echo "12%";?></span></td>
					<?php
					
				}if($includes_tax==0){
					
					$iva=0;
					$subtotalnograva+=$subtotal;
					$subtotalnograva=number_format($subtotalnograva,2,'.','');

					?>
					<td><span class="pull-right"><?php echo "0%";?></span></td>
					<?php
				}
			?>
		<!--PRECIO TOTAL-->
			<td class="col-md-1">
					<?php 
  					$totalind=moneyFormat((($subtotal)+$iva),$curr);
					?>
					<td><span class="pull-right"><?php echo $totalind;?></span></td>			
			</td>

			<td>
			<span class="pull-right"><a href="#" onclick="eliminar('<?php echo $product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span>
			</td>
			
		</tr>	
		<?php
		
	}
	
	
	$subtotalgravar=moneyFormat($subtotalgrava,$curr);
	$subtotalnogravar=moneyFormat($subtotalnograva,$curr);
	$totalivar=moneyFormat($totaliva,$curr);

	$total_compra=moneyFormat(($subtotalnograva+$subtotalgrava+$totaliva),$curr);
	
	//$update=mysqli_query($con,"update facturasped set totalfactu='$total_compra',excentos='$subtotalgrava',noexcentos='$subtotalnograva', valoriva='$totaliva', saldo='$total_compra' where numerofac='$numfacturacompleta'");
?>

<tr>
		<td colspan=11>
			<FONT SIZE=3><b><span class="pull-right"><?php echo  "Total Base Gravada"; ?> <?php echo "$"; ?></span></b></font>
		</td>
		<td class='col-md-1'>
			<input class="form-control" type="number" id="subtotalgrava_" value="<?php echo $subtotalgravar; ?>" style="text-align:right" readonly>
		</td>
		<td></td>
	</tr>

	<tr>
		<td colspan=11>
			<FONT SIZE=3><b><span class="pull-right"><?php echo  "Total Base No Gravada"; ?> <?php echo "$"; ?></span></b></font>
		</td>
		<td class='col-md-1'>
			<input class="form-control" type="number" id="subtotalnograva_" value="<?php echo $subtotalnogravar; ?>" style="text-align:right" readonly>
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan=11>
			<FONT SIZE=3><b><span class="pull-right"><?php echo strtoupper(tax_txt); ?><?php echo "$"; ?></span></b></font>
		</td>
		<td class='col-md-1'>
			<input class="form-control" type="number" id="totaliva_" value="<?php echo $totalivar; ?>" style="text-align:right" readonly>
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan=11>
			<FONT SIZE=3><b><span class="pull-right"><?php echo ucfirst(total_txt); ?> <?php echo "$"; ?></span></b></font>
		</td>
		<td class='col-md-1'>
			<input class="form-control" type="number" id="totalcompra_" value="<?php echo $total_compra; ?>" style="text-align:right" readonly>
		<td></td>
	</tr>
</table>



<input class="btn btn-success" type="button" name="agregar_registros" value="Guardar Factura" onClick="guardar_pedido();" />
<input class="btn btn-danger" type="button" name="borrar_registros" value="Cancelar" onClick="BorrarRegistro();" />

<script>
	function guardar_pedido() {

		if (confirm('Realmente desea guardar esta factura?')) {

			var factura = document.getElementById('num_fac').value;
			var subgrava = document.getElementById('subtotalgrava_').value;
			var subnograva = document.getElementById('subtotalnograva_').value;
			var totiva = document.getElementById('totaliva_').value;
			var totcompra = document.getElementById('totalcompra_').value;

			console.log(factura);
			console.log(subgrava);
			console.log(subnograva);
			console.log(totiva);
			console.log(totcompra);

			$.ajax({
				type: "POST",
				url: "./ajax/guardar_pedido.php",
				data: "factura=" + factura + "&subgrava=" + subgrava + "&subnograva=" + subnograva + "&totiva=" + totiva + "&totcompra=" + totcompra,
				beforeSend: function(objeto) {
					$("#resultados").html("Mensaje: Cargando...");
				},
				success: function(datos) {
					$("#resultados").html(datos);



				}
			});
		}



	}
</script>