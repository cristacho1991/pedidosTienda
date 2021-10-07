<?php
setlocale(LC_MONETARY,'en_US');	
include("is_logged.php");//Archivo comprueba si el usuario esta logueado

	/* Connect To Database*/
	require_once ("../config/db.php");//Ccodigoontiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	//include("../currency.php");//Archivo que obtiene los datos de la moneda
	
$curr='USD';
if (isset($_POST['factura'])) {
	$factura = $_POST['factura'];
	$factura = str_pad($factura, 7, "0", STR_PAD_LEFT);

}
if (isset($_POST['subgrava'])) {
	$subgrava = floatval($_POST['subgrava']);
}
if (isset($_POST['subnograva'])) {
	$subnograva = floatval($_POST['subnograva']);
}
if (isset($_POST['totiva'])) {
	$totiva = floatval($_POST['totiva']);
}
if (isset($_POST['totcompra'])) {
	$totcompra = floatval($_POST['totcompra']);
}

$next = "SELECT * FROM  maenum";
$query_next = mysqli_query($con, $next);
$rw_next = mysqli_fetch_array($query_next);
$next_insert = $rw_next['factura'];
$purchase_order_id = $next_insert;
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);
echo "NUM FACTURA".$numfacturacompleta;

$employee_id = $_SESSION['nombre'];
$rec = mysqli_query($con, "select * from tvendedor where nombre='" . $employee_id . "'");
while ($row = mysqli_fetch_array($rec)) {
	$id_empleado = $row['codven'];
}
$created_at = date("Y-m-d H:i:s");

$insert = mysqli_query($con, "INSERT INTO facturas(empresa,sucursal,numerofac,fecha,cliente,porviva,vendedor,vendidopor,anulada,motivoanul,totalfactu,excentos,noexcentos,valoriva,saldo,servicio,notacredit,promocion,descuentos,porcdesc1,porcdesc2,bodega,actualizad,tipo,impreso,vence,asiento,gravmes,numordent,asientoc,costog,costong,anticipo,forpago,centrocos,enviado,validado,tipof,transporte,tipoemision,acce,tira,grani,proforma,autorizado,pormail,recibido,formapago,numguiar,ordencompra,coduser)
VALUES ('01','01','$numfacturacompleta','$created_at','',0,'$id_empleado','',1,'',$totcompra,$subgrava,$subnograva,$totiva,0,0,0,0,0,0,0,'',0,'F',0,null,'',null,'','',0,0,0,'CON','',0,-1,0,0,'',null,null,null,'',0,0,0,'','','',null)");

$update = mysqli_query($con, "UPDATE maenum SET factura= '$purchase_order_id'+1 where 1");

$id	= [];
$product_id= [];
	$nrofact= [];
	$descripcion= [];
	$cant= [];
	$valorto= [];
	$pordesc= [];
	$precio_unitario= [];

if (!empty($factura))
{
	

	$sql=mysqli_query($con, "select * from moddetfacturas where moddetfacturas.nrofact='$factura'");
$i=0;
	while ($row=mysqli_fetch_array($sql))
	{
		$id[$i]=$row['id'];
		$nrofact[$i]=$row['nrofact'];
		$product_id[$i]=$row['idproducto'];
		$descripcion[$i]=$row['descripcion'];
		$cant[$i]=$row['cant'];
		$precio_unitario[$i]=$row['preciouni'];
		$valortot[$i]=$row['subtotal'];
		$pordesc[$i]=$row['pordesc1'];
		
	$i++;
	}

var_dump(sizeof($product_id));

	for($i=0;$i<sizeof($product_id);$i++){
		$subtotal=$cant[$i]*$precio_unitario[$i];
		//var_dump($subtotal);
		

		//$insert=mysqli_query($con,"INSERT INTO detfacprin(empresa,sucursal,nrofact,tipo,nrolin,cantidad,trabajo,materiales,valortot,refvidrio,trial074)
		//VALUES ('01','01','$nrofact[$i]','F',0,$cant[$i],'$descripcion[$i]','',$subtotal,'','')");
	   
	   $insert2=mysqli_query($con,"INSERT INTO detfacturas(empresa,sucursal,nrolinea,nrofactura,codigo,numordent,fechav,bodega,cantidad,cantidadc,factor,bonifica,preciouni,pvp,porcdesc1,porcdesc2,costotal,lp,ancho,largo,saldo,tipo,iva,promocion,ordent,tecnico,tiempo,tiempor,facturaot)
	   VALUES ('01','01',1,'$nrofact[$i]','$product_id[$i]','',null,'01',$cant[$i],0,0,0.00,$precio_unitario[$i],0.0000,$pordesc[$i],0.00000000,$subtotal,'',0.00,0.00,$cant[$i],'F','1',0,0,'',0.00,0.00,0)");
	  

	}

echo mysqli_error($con);
}
