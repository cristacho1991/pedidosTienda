
<?php
// Realizamos la conexion con el servidor MySQL
$connect = mysqli_connect("95.216.196.50:3306", "zicomtec_useradmin", '$_v@l&t077_$', "zicomtec_dataz");
// Contamos la cantidad de input generado por el usuario
//$number = count($_POST["factura"]);
$next="SELECT * FROM  maenumprod";

	$query_next=mysqli_query($connect,$next);
	$rw_next=mysqli_fetch_array($query_next);
	$next_insert=$rw_next['recajapp'];
	$recajapp=$next_insert;


	$numfac=$_REQUEST['numfac'];
    $medio=$_REQUEST['medio'];
    $valor=$_REQUEST['valor'];

echo "FACTURA".$numfac;
echo "MEDIO".$medio;
echo "VALOR".$valor;


    // Insertamos la informacion enviada por el formulario
    $sql = "INSERT INTO zicomtec_dataz.detrecimesped(empresa,sucursal,fecha,recibo,cliente,factura,formap,valor,emisor,numdocu,fecdocu,asiento,fechacon,tipo,tipor,saldo,trial708)
     VALUES('01','01',now(),'$recajapp','000072','$numfac','$medio','$valor','','32','2021-02-17','','2021-02-17','','',20,'')";
    mysqli_query($connect, $sql);
    


$update=mysqli_query($connect,"UPDATE maenumprod SET recajapp= '$recajapp'+1 where 1");

$recajapp=$recajapp+1;

    // Si todo es correcto, imprimimos informacion ingresada
echo "Información ingresada";




?>