<?php
include("is_logged.php"); //Archivo comprueba si el usuario esta logueado
/* Connect To Database*/
require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario


$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
	$daterange = mysqli_real_escape_string($con, (strip_tags($_REQUEST['range'], ENT_QUOTES)));

	$tables = "facturas";
	//$campos="purchases_order.currency_id, purchases_order.purchase_order_id, purchases_order.created_at, suppliers.name,  suppliers.work_phone, users.fullname, purchases_order.status, purchases_order.subtotal, purchases_order.tax";
	$campos = "facturas.sucursal,facturas.numerofac, facturas.fecha, facturas.fecha, (select tclientes.razonsocia from tclientes where tclientes.codcliente=facturas.cliente) as Detalle, facturas.totalfactu,(Select tclientes.mail from tclientes where tclientes.codcliente=facturas.cliente )as Email";

	if (!empty($daterange)) {
		list($f_inicio, $f_final) = explode(" - ", $daterange); //Extrae la fecha inicial y la fecha final en formato español
		list($dia_inicio, $mes_inicio, $anio_inicio) = explode("/", $f_inicio); //Extrae fecha inicial 
		$fecha_inicial = "$anio_inicio-$mes_inicio-$dia_inicio 00:00:00"; //Fecha inicial formato ingles
		list($dia_fin, $mes_fin, $anio_fin) = explode("/", $f_final); //Extrae la fecha final
		$fecha_final = "$anio_fin-$mes_fin-$dia_fin 23:59:59";
	} else {
		$fecha_inicial = date("Y-m") . "-01 00:00:00";
		$fecha_final = date("Y-m-d H:i:s");
	}
	$sWhere = "facturas.validado=0";
	$sWhere .= " and facturas.fecha between '$fecha_inicial' and '$fecha_final' ";


	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 5; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row = mysqli_fetch_array($count_query)) {
		$numrows = $row['numrows'];
	} else {
		echo mysqli_error($con);
	}
	$total_pages = ceil($numrows / $per_page);
	$reload = './quotes.php';
	//main query to fetch the data
	$query = mysqli_query($con, "SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data

	if (isset($_REQUEST["id"])) {
?>
		<div class="<?php echo $classM; ?>">
			<button type="button" class="close" data-dismiss="alert"><?php echo $times; ?></button>
			<strong><?php echo $aviso ?> </strong>
			<?php echo $msj; ?>
		</div>
	<?php
	}

	if ($numrows > 0) {

	?>

		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Listado de Facturas</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table  table-condensed table-hover table-striped " id="tabladin">
								<tr>
									<th class='text-center'>Tipo de Documento</th>
									<th class='text-center'>Tipo</th>
									<th class='text-center'>Sucursal</th>

									<th class='text-center'>Número de Factura</th>
									<th class='text-center'>Fecha</th>
									<th class='text-center'>Detalle </th>
									<th class='text-center'>Valor </th>
									<th class='text-center'>Email </th>
									<th class='text-center'>Descargar PDF</th>

								</tr>
								<?php
								$j = 0;
								$finales = 0;
								while ($row = mysqli_fetch_array($query)) {
									$tipo_doc = 'FACTURAS';
									$tipo = 'OTROS INGRESOS';
									$sucursal = $row['sucursal'];
									$num_doc = $row['numerofac'];
									$created_at = $row['fecha'];

									$fecha = date("d/m/Y", strtotime($created_at));
									$detalle = $row['Detalle'];
									$valor = $row['totalfactu'];
									$email = $row['Email'];

								?>
									<tr id='<?php echo $j; ?>''>
							<td class=' text-center'><?php echo $tipo_doc; ?></td>
										<td class='text-center'><?php echo $tipo; ?></td>
										<td class='text-center'><?php echo $sucursal; ?></td>
										<td class='text-center' id="numero<?php echo $j; ?>"><?php echo $num_doc; ?></td>
										<td class='text-center' id="fecha<?php echo $j; ?>"><?php echo $fecha; ?></td>
										<td class='text-center'><?php echo $detalle; ?></td>
										<td class='text-center'><?php echo $valor; ?></td>
										<td class='text-center'><?php echo $email; ?></td>
										<td class='text-center'><a class="fa fa-file-pdf-o" aria-hidden="true" onclick="reporte(<?php echo $j; ?>);"></a></td>
							

									<?php
									$j++;
								}

									?>


									</tr>


								<?php  } ?>
								<tr>
									<td colspan=9>
										<?php
										$inicios = $offset + 1;
										$finales += $inicios - 1;
										echo "Mostrando $inicios al $finales de $numrows registros";
										echo paginate($reload, $page, $total_pages, $adjacents);
										?>
									</td>
								</tr>
							</table>
						</div>

						<div class="col-md-5" >

<div id="resp"></div>

</div>
					</div><!-- /.box-body -->

				</div><!-- /.box -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	<?php
}
//}
	?>


	<script>
		$("#enviaride").click(function() {
			var arrTodo = new Array();
			var valores = '';
			/*Agrupamos todos los input con name=cbxEstudiante*/
			$('input[name="checkxml"]').each(function(element) {
				var item = {};
				item.id = this.value;


				$(this).parents("tr").find("#numero").each(function() {
					item.idfactura = $(this).html();
					//valores += $(this).html() + "\n";
				});
				item.status = this.checked;
				arrTodo.push(item);
			});

			/*Creamos un objeto para enviarlo al servidor*/
			var toPost = arrTodo
			console.log(toPost);

			
		});
	</script>

<script>
	function reporte(id){
		var numfac=document.getElementById('numero'+id).value;
		var fecha=document.getElementById('fecha'+id).value;

		//var numfac=$("#numero"+id).val();
		console.log(numfac);
		//var fecha=$("#fecha"+id).val();
		console.log(fecha);
		// VentanaCentrada('purchases-order-report-print.php?daterange='+daterange+"&employee_id="+employee_id,'Reporte ordenes compras','','1024','768','true');
		 VentanaCentrada('./pdf/documentos/factura.php?fecha='+fecha+"&numfac="+numfac,'PDF','','1024','768','true');

	}
</script>