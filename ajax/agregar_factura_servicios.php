<?php
setlocale(LC_MONETARY, 'en_US');
include("is_logged.php"); //Archivo comprueba si el usuario esta logueado

$curr = 'USD';

$purchase_order_id = $_SESSION['numerofac'];
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);


if (isset($_POST['codigo'])) {
	$codigo = $_POST['codigo'];
}
if (isset($_POST['descripcion'])) {
	$descripcion = $_POST['descripcion'];
}
if (isset($_POST['iva'])) {
	$ivaa = intval($_POST['iva']);
}
if (isset($_POST['cant'])) {
	$cant = intval($_POST['cant']);
}
if (isset($_POST['precio'])) {
	$precio = floatval($_POST['precio']);
}


/* Connect To Database*/
require_once("../config/db.php"); //Ccodigoontiene las variables de configuracion para conectar a la base de datos
require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario
//include("../currency.php");//Archivo que obtiene los datos de la moneda

if (!empty($codigo) and !empty($descripcion) and !empty($cant) and !empty($precio)) {




	$subtotal = $cant * $precio;
	echo "Subtotal" . $subtotal;


	//$insert=mysqli_query($con,"INSERT INTO detfacprin(empresa,sucursal,nrofact,tipo,nrolin,cantidad,trabajo,materiales,valortot,refvidrio,trial074)
	//VALUES ('01','01','$numfacturacompleta','F',0,$cant,'$descripcion','',$subtotal,'','')");

	$insert = mysqli_query($con, "INSERT INTO zicomtec_dataz.modfacprint(cantidad,trabajo,valor,trial086,nrofact,codigo,iva,preciouni) VALUES ($cant,'$descripcion',$subtotal,'','$numfacturacompleta','$codigo','$ivaa',$precio)");



	//$insert2=mysqli_query($con,"INSERT INTO detfacturas(empresa,sucursal,nrolinea,nrofactura,codigo,numordent,fechav,bodega,cantidad,cantidadc,factor,bonifica,preciouni,pvp,porcdesc1,porcdesc2,costotal,lp,ancho,largo,saldo,tipo,iva,promocion,ordent,tecnico,tiempo,tiempor,facturaot)
	//VALUES ('01','01',1,'$numfacturacompleta','$codigo','',null,'01',$cant,0,0,0.00,$precio,0.0000,0.00,0.00000000,$subtotal,'',0.00,0.00,$cant,'F','$ivaa',0,0,'',0.00,0.00,0)");



	//ELIMINAR

	echo mysqli_error($con);
}
/*/
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM detfacturasped WHERE codigo=$id and nrofactura='$numfacturacompleta'");
$delete2=mysqli_query($con, "DELETE FROM facturasped WHERE codigo=$id and nrofactura='$numfacturacompleta'");

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

if (isset($_REQUEST['item'])){
	$item=intval($_REQUEST['item']);
	$product_item=intval($_REQUEST['product_item']);
	if ($item==1){
		$valor=intval($_REQUEST['valor']);
		echo "VALOR CANTIDADDDD   ".$valor;
		$update=mysqli_query($con,"update detfacturasped set cantidadc='$valor' where nrofactura='$numfacturacompleta' and codigo='$product_item'");
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
/*/

$tax = 12;


?>
<table class="table">
	<tr>
		<th>CODIGO</th>
		<th>DESCRIPCION</th>
		<th class='text-center'>CANTIDAD FACTURADA</th>
		<th>PRECIO UNITARIO</th>
		<th><span class="pull-right">SUBTOTAL</span></th>
		<th><span class="pull-right">IVA</span></th>
		<th> </th>
		<th><span class="pull-right">PRECIO TOTAL</span></th>

	</tr>
	<?php
	$subtotalgrava = 0;
	$subtotalnograva = 0;
	$totaliva = 0;
	$sumador_total = 0;
	//$sql=mysqli_query($con, "select * from detfacprin,detfacturas where detfacprin.nrofact=detfacturas.nrofactura and detfacprin.nrofact='$numfacturacompleta'");
	$sql = mysqli_query($con, "select * from modfacprint where modfacprint.nrofact='$numfacturacompleta'");

	while ($row = mysqli_fetch_array($sql)) {

		$product_id = $row['codigo'];
		$nrofact = $row['nrofact'];
		$descripcion = $row['trabajo'];
		$cantidad = $row['cantidad'];
		$valortot = $row['valor'];
		$includes_tax = $row['iva'];
		$precio_unitario = $row['preciouni'];
		$unit_price = moneyformat($precio_unitario, $curr);
		$subtotal = $valortot;



		$sumador_total += $subtotal; //Sumador
		$sumador_total = number_format($sumador_total, 2, '.', '');

	?>
		<tr>
			<!--CODIGO DEL PRODUCTO-->
			<td class='text-center col-md-1'>
				<input type="number" id="nro_fact" style="text-align:center" disabled="" value="<?php echo $nrofact	; ?>" class="form-control input-sm">
			</td>
			<!--NOMBRE DEL PRODUCTO-->
			<td><?php echo $descripcion; ?></td>
			<!--CANTIDAD-->
			<td class='text-center col-md-1'>
				<input type="number" style="text-align:center" disabled="" value="<?php echo $cantidad; ?>" class="form-control input-sm">
			</td>
			<td class='text-center col-md-1'>
				<input type="number" style="text-align:center" disabled="" value="<?php echo $precio_unitario; ?>" class="form-control input-sm">
			</td>



			<!--SUBTOTAL-->
			<td><span class="pull-right">
					<?php echo $subtotal; ?></span></td>

			<!--IVA 12% O 0%-->
			<?php

			if ($includes_tax == 1) {
				$iva = moneyFormat(($subtotal * 0.12), $curr);
				$subtotalgrava += $subtotal;
				$totaliva += $iva;
			?>

				<td><span class="pull-right"><?php echo "12%"; ?></span></td>
			<?php

			} elseif ($includes_tax == 0) {

				$iva = 0;
				$subtotalnograva += $subtotal;
			?>
				<td><span class="pull-right"><?php echo "0%"; ?></span></td>
			<?php
			}
			?>
			<!--PRECIO TOTAL-->
			<td class="col-md-2">
				<?php
				$totalind = moneyFormat((($subtotal) + $iva), $curr);
				?>
			<td><span class="pull-right"><?php echo $totalind; ?></span></td>
			</td>
			<td>
				<span class="pull-right"><a href="#" onclick="eliminar('<?php echo $product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span>
			</td>
		</tr>
	<?php

	}


	$subtotalgravar = moneyFormat($subtotalgrava, $curr);
	$subtotalnogravar = moneyFormat($subtotalnograva, $curr);
	$totalivar = moneyFormat($totaliva, $curr);

	$total_compra = moneyFormat(($subtotalnograva + $subtotalgrava + $totaliva), $curr);

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


<input class="btn btn-success" type="button" name="agregar_registros" value="Guardar Factura" onClick="guardar_factura_serv();" />
<input class="btn btn-danger" type="button" name="borrar_registros" value="Cancelar" onClick="BorrarRegistro();" />

<script>
	function guardar_factura_serv() {

		if (confirm('Realmente desea guardar esta factura?')) {

			var factura = document.getElementById('nro_fact').value;
			var subgrava = document.getElementById('subtotalgrava_').value;
			var subnograva = document.getElementById('subtotalnograva_').value;
			var totiva = document.getElementById('totaliva_').value;
			var totcompra = document.getElementById('totalcompra_').value;

			console.log(factura);
			$.ajax({
				type: "POST",
				url: "./ajax/guardar_factura_servicios.php",
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