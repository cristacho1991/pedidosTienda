<?php
include("is_logged.php"); //Archivo comprueba si el usuario esta logueado

/* Connect To Database*/
require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario
//include inventory functions

$valor = $_REQUEST['valor'];
$rec = mysqli_query($con, "select * from tclientes where ruc='$valor'");
$row = mysqli_fetch_array($rec);
$id_cliente = $row['codcliente'];
$id_cliente2 = $id_cliente;
echo "El número de clientes es:  " . $id_cliente2;


$sTable = "facturas";
$sWhere = "where cliente='$id_cliente2'";

include 'pagination.php'; //include pagination file
//pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 10; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;
//Count the total number of row in your table*/
$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
$row = mysqli_fetch_array($count_query);
$numrows = $row['numrows'];
$total_pages = ceil($numrows / $per_page);
//$reload = './index.php';
//main query to fetch the data
$sql = "SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
$query = mysqli_query($con, $sql);
//loop through fetched data
if ($numrows > 0) {

?>

	<!doctype html>
	<html lang="es">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">


	</head>

	<body>

		<div class="container">

			<hr>
			<div class="row">
				<div class="col-12 col-md-12">
					<!-- Contenido -->
					<div class="container">
						<br />
						<div class="form-group">
							<form name="add_name" id="add_name">
								<div class="table-responsive align-middle">
								<table class="table table-bordered" id="dynamic_field">
										<thead>
										
											<tr>
												<th>Número de factura</th>
												<th>Saldo</th>

											</tr>
										</thead>
										<tbody>
											<?php

											$i = 0;
											while ($row = mysqli_fetch_array($query)) {
												$numfac = $row['numerofac'];
												$saldo = $row['saldo'];

											?>
												<tr>
													<td><input type="text" id="num_fac<?php echo $i; ?>" disabled name="num_fac[]" placeholder="" value="<?php echo $numfac; ?>" class="form-control col-md-2" /></td>
													<td><input type="text" id="saldo<?php echo $i; ?>" disabled name="saldo[]" placeholder="" value="<?php echo $saldo; ?>" class="form-control col-md-2" /></td>
													<td><button type="button" name="add<?php echo $i; ?>" id="add<?php echo $i; ?>" onclick="AgregarMas();" class="btn btn-success">Agregar Cobro</button></td>

												</tr>
											<?php
												$i++;
											}
											?>
									
							<?php
						}		
							?>
		
										
										</tbody>
									
									</table>
									
								</div>
								
								<input type="button" name="submit" id="submit" class="btn btn-info" value="Enviar Información" />
							
							</div>

							</form>
						</div>
					</div>
					<!-- Fin Contenido -->
				</div>

<div id="outer">
<div id="header">
<div class="float-left "> Número de factura</div>
<div class="float-left col-heading"> Medio de Pago</div>
<div class="float-left col-heading2"> Valor</div>
<div class="float-left col-heading2"> Emisor</div>
<div class="float-left col-heading"> Fecha Documento</div>
<div class="float-left col-heading2"> Número de documento</div>
<div class="float-left col-heading2"> Banco</div>
<div class="float-left col-heading2"> SRI</div>

</div>
<div id="productos">
<?php require_once("agregar_fila_cobro.php") ?>
</div>
<div class="btn-action float-clear">
<input class="btn btn-success" type="button" name="agregar_registros" value="Agregar Mas" onClick="AgregarMas();" />
<input class="btn btn-danger" type="button" name="borrar_registros" value="Borrar Campos" onClick="BorrarRegistro();" />
<span class="success"><?php if(isset($resultado)) { echo $resultado; }?></span>
</div>
<div style="position: relative;">
<input class="btn btn-primary" type="submit" name="guardar" value="Guardar Ahora" />
</div>
</div>

			</div>
		</div>
		<script src="dist/js/bootstrap.min.js"></script>

	</body>
</html>




	<script>
	function AgregarMas() {
	$("<div>").load("./ajax/agregar_fila_cobro.php", function() {
			$("#productos").append($(this).html());
	});	
}
	</script>









	<script>
		function agregar_cobro(id) {

			var numfac = document.getElementById('num_fac' + id).value;
			console.log(numfac);


			$.ajax({
				url: './ajax/agregar_cobronormal.php?&numfac=' + numfac,
				type: "POST",
				beforeSend: function(objeto) {
					$('#filas').html('<img src="./img/ajax-loader.gif"> Cargando...');
				},
				success: function(data) {
					$("#filas").html(data);
				}
			});

		}
	</script>


	<script>
		function activarcombo(id) {
			var cod = document.getElementById("medio" + id).value;
			obj = {
				"Codigo": cod
			};
			console.log(obj);
			if (cod === 'Tarjetas Crédito' || cod === 'Cheque') {
				$("#banco" + id).prop("disabled", false);
				$("#emisor" + id).prop("disabled", false);
				$("#doc" + id).prop("disabled", false);
				$("#fecdoc" + id).prop("disabled", false);

			} else {
				$("#banco" + id).prop("disabled", true);
				$("#emisor" + id).prop("disabled", true);
				$("#doc" + id).prop("disabled", true);
				$("#fecdoc" + id).prop("disabled", true);
			}

		}
	</script>
	<script>
		function realiza_cobro(id) {

			var numfac = document.getElementById('numfac' + id).value;
			var medio = document.getElementById('medio' + id).value;
			var valor = document.getElementById('valor' + id).value;
			var emisor = document.getElementById('emisor' + id).value;
			var fecdoc = document.getElementById('fecdoc' + id).value;
			var doc = document.getElementById('doc' + id).value;
			var banco = document.getElementById('banco' + id).value;
			var cobro = document.getElementById('cobro' + id).value;


			$.ajax({
				url: 'inserta_cobro.php?numfac=' + numfac + '&medio=' + medio + '&valor=' + valor + '&emisor=' + emisor + '&fecdoc=' + fecdoc + '&doc=' + doc + '&banco=' + banco + '&cobro=' + cobro,

				url: "inserta_cobro.php",
				method: "POST",
				success: function(data) {
					alert('Ingresado Correctamente');
				}
			});

		}
	</script>