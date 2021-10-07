<?php

include ("digito_verificador.php");
$json = (file_get_contents('php://input',true));
$datos = json_decode($json);
$cont = count($datos);
$nfact=Array();
$id=Array();
for ($i = 0; $i < $cont; $i++) {

    if ($datos[$i]->status == 1) {
        $id[$i]=$datos[$i]->id;
        array_push($nfact, $datos[$i]->idfactura);
    }
}


$db_host="95.216.196.50:3306";
$db_user="zicomtec_useradmin";
$db_pass='$_v@l&t077_$'	;
$db_name="zicomtec_dataz";
$con=@mysqli_connect($db_host, $db_user, $db_pass, $db_name,3306);

$razonsocia = '';
$nombrecom = '';
$obligado = '';
$ruc = '';
$establecimiento = '';
$dirmatriz = '';
$direst = '';
$fecha = '';
$detfacprint = array();
$detfacprintcant = array();
$detfacprintvaltot = array();
$preciounidetfacprin = array();

for($i=0;$i<sizeof($nfact);$i++){

$query1 = mysqli_query($con,"SELECT * FROM maempres");
while($row = mysqli_fetch_array($query1)){
$razonsocia=$row['nombre'];
$nombrecom=$row['nombrecomer'];
$obligado=$row['obligcontab'];
}

$query2 = mysqli_query($con,"SELECT * FROM tabsuc limit 1");
while($row = mysqli_fetch_array($query2)){
    $ruc=$row['ruc'];
}

$query3 = mysqli_query($con,"SELECT * FROM testable");
while($row = mysqli_fetch_array($query3)){
    $establecimiento=$row['codestab'];
    $dirmatriz=$row['direccion'];
    $direst=$row['direccion'];
$ptoemi=$row['ptoemi'];
}

$query4 = mysqli_query($con,"SELECT facturas.numerofac,facturas.totalfactu,facturas.descuentos,facturas.excentos,facturas.noexcentos,facturas.valoriva
,facturas.totalfactu,facturas.forpago,facturas.fecha,facturas.cliente,(Select frazons from tclientes where tclientes.codcliente=facturas.cliente)as Cliente,
(Select fruc from tclientes where tclientes.codcliente=facturas.cliente) as RucCliente FROM facturas where numerofac=$nfact[$i]");

while($row = mysqli_fetch_array($query4)){
    $secuencial=$row['numerofac'];
    $fechemi=$row['numerofac'];
    $totsinimp=$row['totalfactu'];
    $totdes=$row['descuentos'];
    $facexcentos=$row['excentos'];
    $facnoexcentos=$row['noexcentos'];
    $facvaliva=$row['valoriva'];
    $totfac=$row['totalfactu'];
    $formapago=$row['forpago'];
    $totfac=$row['totalfactu'];
    $razsoccom=$row['Cliente'];
    $idecom=$row['RucCliente'];
    $fecha=$row['fecha'];
}

$numfacturacompleta = str_pad($secuencial, 9, "0", STR_PAD_LEFT);
$fechaComoEntero = strtotime($fecha);
$anio = date("Y", $fechaComoEntero);
$mes = date("m", $fechaComoEntero);
$dia = date("d", $fechaComoEntero);

$query6 = mysqli_query($con,"SELECT * FROM detfacprin where nrofact=$nfact[$i]");
$j=0;
while($row = mysqli_fetch_array($query6)){
    
    $detfacprint[$j]=$row['trabajo'];
    $detfacprintcant[$j]=$row['cantidad'];
    $detfacprintvaltot[$j]=$row['valortot'];
$preciounidetfacprin[$j]=$detfacprintvaltot[$j]/$detfacprintcant[$j];
$j++;
}
print_r($detfacprint);
$puntoemision='MJC';
$mod='8';
$codest='MJC';
$tipoid='04';
$dig48=$dia.$mes.$anio.'01'.$ruc.'2'.$establecimiento.$ptoemi.$numfacturacompleta.'12345678'.'1';
$mod=getMod11Dv($dig48);

$xml = new DomDocument('1.0', "UTF-8");
$xml->formatOutput=true;

$Factura = $xml->createElement('Factura');
		$Factura = $xml->appendChild($Factura);

$cabecera=$xml->createAttribute('id');
$cabecera->value='comprobante';
$Factura->appendChild($cabecera);
$cabecerav=$xml->createAttribute('version');
$cabecerav->value='1.0.0';
$Factura->appendChild($cabecerav);

// INFORMACION TRIBUTARIA.
$infoTributaria = $xml->createElement('infoTributaria');
$infoTributaria = $Factura->appendChild($infoTributaria);

$cbc = $xml->createElement('ambiente','2');
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('tipoEmision', '1');
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('razonSocial', $razonsocia);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('nombreComercial', $nombrecom);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('ruc', $ruc);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('claveAcceso', $dia.$mes.$anio.'01'.$ruc.'2'.$establecimiento.$ptoemi.$numfacturacompleta.'12345678'.'1'.$mod);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('codDoc', '01');
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('estab', $codest);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('ptoEmi', '001');
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('secuencial',$secuencial);
$cbc = $infoTributaria->appendChild($cbc);
$cbc = $xml->createElement('dirMatriz' ,$dirmatriz);
$cbc = $infoTributaria->appendChild($cbc);

// INFORMACIOO DE FACTURA.
$infoFactura = $xml->createElement('infoFactura');
$infoFactura = $Factura->appendChild($infoFactura);
$cbc = $xml->createElement('fechaEmision',$fechemi);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('dirEstablecimiento',$direst);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('obligadoContabilidad', $obligado);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('tipoIdentificacionComprador',$tipoid);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('razonSocialComprador', $razsoccom);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('identificacionComprador', $idecom);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('totalSinImpuestos', $totsinimp);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('totalDescuento', $totdes);
$cbc = $infoFactura->appendChild($cbc);

if($facexcentos>0){
$totalConImpuestos = $xml->createElement('totalConImpuestos');
$totalConImpuestos = $infoFactura->appendChild($totalConImpuestos);
$totalImpuesto = $xml->createElement('totalImpuesto');
$totalImpuesto = $totalConImpuestos->appendChild($totalImpuesto);
$cbc = $xml->createElement('codigo', '2');
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('codigoPorcentaje', '7');
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('baseImponible', $facexcentos);
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('valor', '0');
$cbc = $totalImpuesto->appendChild($cbc);

}
if($facnoexcentos>0){
$totalConImpuestos = $xml->createElement('totalConImpuestos');
$totalConImpuestos = $infoFactura->appendChild($totalConImpuestos);
$totalImpuesto = $xml->createElement('totalImpuesto');
$totalImpuesto = $totalConImpuestos->appendChild($totalImpuesto);
$cbc = $xml->createElement('codigo', '2');
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('codigoPorcentaje', '2');
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('baseImponible',$facnoexcentos);
$cbc = $totalImpuesto->appendChild($cbc);
$cbc = $xml->createElement('valor',$facvaliva);
$cbc = $totalImpuesto->appendChild($cbc);

}


$cbc = $xml->createElement('propina', '0.00');
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('importeTotal' ,$totfac);
$cbc = $infoFactura->appendChild($cbc);
$cbc = $xml->createElement('moneda', 'DOLAR');
$cbc = $infoFactura->appendChild($cbc);


//DETALLES DE LA FACTURA.
$detalles = $xml->createElement('detalles');
$detalles = $Factura->appendChild($detalles);

for ($in=0; $in <sizeof($detfacprint) ; $in++) {


$detalle = $xml->createElement('detalle');
$detalle = $detalles->appendChild($detalle);
$cbc = $xml->createElement('codigoPrincipal','OI0001');
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('codigoAuxiliar', '1');
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('descripcion', $detfacprint[$in]);
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('cantidad', $detfacprintcant[$in]);
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('precioUnitario', $preciounidetfacprin[$in]);
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('descuento', '0');
$cbc = $detalle->appendChild($cbc);
$cbc = $xml->createElement('precioTotalSinImpuesto', $detfacprintvaltot[$in]);
$cbc = $detalle->appendChild($cbc);

$impuestos = $xml->createElement('impuestos');
$impuestos = $detalle->appendChild($impuestos);
$impuesto = $xml->createElement('impuesto');
$impuesto = $impuestos->appendChild($impuesto);
$cbc = $xml->createElement('codigo', '2');
$cbc = $impuesto->appendChild($cbc);
$cbc = $xml->createElement('codigoPorcentaje', '2');
$cbc = $impuesto->appendChild($cbc);
$cbc = $xml->createElement('tarifa', '12');
$cbc = $impuesto->appendChild($cbc);
$cbc = $xml->createElement('baseImponible', $detfacprintvaltot[$in]);
$cbc = $impuesto->appendChild($cbc);
$cbc = $xml->createElement('valor', '211');
$cbc = $impuesto->appendChild($cbc);
}

 

 $xml->formatOutput = true;
$strings_xml       = $xml->saveXML();


$xml->save('C:/sritemp/xaviermora/facturas/FOI'.$establecimiento.'-'.$ptoemi.'-'.$nfact[$i].'.xml');
chmod('C:/sritemp/xaviermora/facturas/FOI'.$establecimiento.'-'.$ptoemi.'-'.$nfact[$i].'.xml', 0777);
echo '<span style="color: #015B01; font-size: 15pt;">XML de Factura '.$nfact[$i].' creada.</span>&nbsp;';
echo '<hr width="100%"></div>';

$cadfactura='FOI'.$establecimiento.'-'.$ptoemi.'-'.$nfact[$i].'.xml';

  $fh = fopen("C:/sritemp/xaviermora/webservicesoffline/ejecutable/prueba2.bat", 'w') or die("Se produjo un error al crear el archivo");
  
  $texto = 'cd /d C:\sritemp\xaviermora\webservicesoffline\ejecutable
java -jar C:\sritemp\xaviermora\webservicesoffline\ejecutable\WebServicesOffline.jar C:\sritemp\xaviermora\facturas\\'.$cadfactura.' 1 NO Isi20102509';
  
  fwrite($fh, $texto) or die("No se pudo escribir en el archivo");
  
  fclose($fh);


  echo "Se ha escrito sin problemas";

  //exec('cmd /c C:\sritemp\xaviermora\webservicesoffline\ejecutable\prueba2.bat');

  ////////////////////

  
}
return $xml;
?>
