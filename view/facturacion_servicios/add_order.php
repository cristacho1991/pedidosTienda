<?php
setlocale(LC_MONETARY, 'en_US');

//if ($permisos_ver==1){
//$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = 'facturasped'";
$next = "SELECT * FROM  maenum";

$query_next = mysqli_query($con, $next);
$rw_next = mysqli_fetch_array($query_next);
$next_insert = $rw_next['factura'];
$purchase_order_id = $next_insert;
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);

$employee_id = $_SESSION['nombre'];
$rec = mysqli_query($con, "select * from tvendedor where nombre='" . $employee_id . "'");
while ($row = mysqli_fetch_array($rec)) {
	$id_empleado = $row['codven'];
}
$created_at = date("Y-m-d H:i:s");
$_SESSION['numerofac'] = $purchase_order_id;


$_SESSION['includes_tax'] = 0;

?>
<!DOCTYPE html>
<html>
<head>
	<?php include("head.php"); ?>
</head>

<body class="hold-transition <?php echo $skin; ?> sidebar-mini">
	<?php
	//if ($permisos_ver==1){
	include("modal/servicios.php");
	//}
	?>
	<div class="wrapper">
		<header class="main-header">
			<?php include("main-header.php"); ?>
		</header>
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<?php include("main-sidebar.php"); ?>
		</aside>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->

			<section class="content-header">
				<h1><i class='fa fa-edit'></i> Agregar nueva factura de Servicio</h1>

			</section>
			<!-- Main content -->
			<section class="content">
				<!-- Default box -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Nueva Factura de Servicio</h3>

					</div>

					<div class="box-body">
						<div class="row">

							<div class="col-md-12 col-sm-12">
								<form method="post">
									<div class="box box-info">
										<div class="box-header box-header-background-light with-border">
											<h3 class="box-title  ">Detalles de la Factura</h3>
										</div>

										<div class="box-background">
											<div class="box-body">
												<div class="row">

													<div class="col-md-4">

														<label>Cliente</label>
														<select class="form-control select2" name="supplier_id">
															<option value="">Selecciona Cliente</option>

														</select>
													</div>

													<div class="col-md-3">
														<label>Fecha</label>
														<div class="input-group">
															<input type="text" class="form-control datepicker" name="purchase_date" value="<?php echo date("d/m/Y") ?>" disabled="">

															<div class="input-group-addon">
																<a href="#"><i class="fa fa-calendar"></i></a>
															</div>
														</div>
													</div>

													<div class="col-md-3">

														<label>Orden de Compra Nº</label>
														<input type="text" class="form-control datepicker" name="purchase_date" value="<?php echo $purchase_order_id; ?>" disabled="">
													</div>



												</div>


												<div class="row">



													<div class="col-md-1">
														<label>Vendedor</label>

														<?php
														$rec = mysqli_query($con, "select * from tvendedor where nombre='" . $employee_id . "'");
														while ($row = mysqli_fetch_array($rec)) {
															$codven = $row['codven'];
														?>


															<input type="text" class="form-control" name="vendedor" value="<?php echo $codven; ?>" disabled="disabled" onblur="actualizar_campos(this.value,4)">

														<?php
														}

														?>


													</div>

													
													<div class="col-md-6">
														<label>Comentarios u observaciones</label>
														<input type="text" class="form-control" name="comentarios" onblur="actualizar_campos(this.value,5)">
													</div>


												</div>


											</div><!-- /.box-body -->
										</div>
										<div class="box-footer pull-right">
											<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal2"><i class='fa fa-plus'></i> Agregar Servicio</button>
										</div>
									</div>
									<!-- /.box -->
								</form>
							</div>
							<!--/.col end -->
						</div>
						<div id="resultados" class='col-md-12' style="margin-top:4px"></div>
					</div><!-- /.box-body -->

				</div><!-- /.box -->

			</section><!-- /.content -->

		</div><!-- /.content-wrapper -->
		<?php include("footer.php"); ?>
	</div><!-- ./wrapper -->
	<?php include("js.php"); ?>
	<!-- Select2 -->

	<script src="plugins/select2/select2.full.min.js"></script>
	<script src="dist/js/VentanaCentrada.js"></script>
	<script>
		$(function() {

			$(".select2").select2();
		});

		$(document).ready(function() {
			load(1);

		});

		function load(page) {
			//var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url: './ajax/servicios.php?action=ajax&page=' + page,
				beforeSend: function(objeto) {
					$('#loader2').html('<img src="./img/ajax-loader.gif"> Cargando...');
				},
				success: function(data) {
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader2').html('');

				}
			})
		}

		function calcular_total() {
			var cant = document.getElementById('cantidad_').value;
			var precio = document.getElementById('precio_').value;
			var total = 0;
			var curr = 'USD';
			total = document.getElementById('total_').innerHTML;
			total = (cant * precio);
			document.getElementById('total_').innerHTML = total;

		}


		function agregar_serv() {
			var codigo = document.getElementById('codigo_').value;
			var descripcion = document.getElementById('descripcion_').value;
			var iva = document.getElementById('iva_').value;
			var cant = document.getElementById('cantidad_').value;
			var precio = document.getElementById('precio_').value;

			//console.log(codigo);
			//console.log(descripcion);
			//console.log(iva);
			//console.log(cant);
			//console.log(precio);
			//Inicia validacion
			if (isNaN(cant)) {
				alert('Esto no es un numero');
				document.getElementById('cantidad_').focus();
				return false;
			}
			if (isNaN(precio)) {
				alert('Esto no es un numero');
				document.getElementById('precio_').focus();
				return false;
			}


			$.ajax({
				type: "POST",
				url: "./ajax/agregar_factura_servicios.php",
				data: "codigo=" + codigo + "&descripcion=" + descripcion + "&iva=" + iva + "&cant=" + cant + "&precio=" + precio,
				beforeSend: function(objeto) {
					$("#resultados").html("Mensaje: Cargando...");
				},
				success: function(datos) {
					$("#resultados").html(datos);
					document.getElementById('codigo_').value = "";
					document.getElementById('descripcion_').value = "";
					document.getElementById('cantidad_').value = "";
					document.getElementById('precio_').value = "";
					document.getElementById('total_').value = "";


				}
			});
		}


		function actualizar_campos(valor, campo) {
			parametros = {
				"valor": valor,
				"campo": campo
			};
			$.ajax({
				url: "./ajax/agregar_factura_servicios.php",
				data: parametros,
				success: function(data) {
					$("#resultados").html(data);
				}
			});
		}

		function actualizar_item(valor, item, product_item) {
			parametros = {
				"valor": valor,
				"item": item,
				"product_item": product_item
			};
			$.ajax({
				url: "./ajax/agregar_factura_servicios.php",
				data: parametros,
				success: function(data) {
					$("#resultados").html(data);
				}
			});
		}



		function eliminar(id) {
			parametros = {
				"id": id
			};
			$.ajax({
				url: "./ajax/agregar_factura_servicios.php",
				data: parametros,
				success: function(data) {
					$("#resultados").html(data);
				}
			});

		}
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".select2").select2({
				ajax: {
					url: "ajax/supplier_select2.php",
					dataType: 'json',
					delay: 250,
					data: function(params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function(data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 2

			}).on('change', function(e) {
				var value = this.value;
				actualizar_campos(value, 1);

			});
		});
	</script>


</body>

</html>