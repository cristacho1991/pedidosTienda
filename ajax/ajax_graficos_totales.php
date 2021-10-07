<?php
include("is_logged.php"); //Archivo comprueba si el usuario esta logueado
/* Connect To Database*/
require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
	
	$filtro = mysqli_real_escape_string($con, (strip_tags($_REQUEST['filtro'], ENT_QUOTES)));

	if($filtro==1){
	$query = mysqli_real_escape_string($con, (strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$SQL = mysqli_query($con,"SELECT * FROM  tvendedor where nombre like '%$query%'");

	while($row = mysqli_fetch_array($SQL)){

		$cod_ven = $row['codven'];
	}
} else if($filtro==2){
	$query = mysqli_real_escape_string($con, (strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$SQL = mysqli_query($con,"SELECT * FROM  tclientes where razonsocia like '%$query%'");

	while($row = mysqli_fetch_array($SQL)){

		$cod_cliente = $row['codcliente'];
	}
}


	$daterange = mysqli_real_escape_string($con, (strip_tags($_REQUEST['range'], ENT_QUOTES)));


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

	$tables = "facturas";

	if ($filtro == 0 || $filtro==1 || $filtro==2) {

		$campos = "sum(descuentos) as Descuentos,sum(excentos) as Excentos,sum(noexcentos) as NoExcentos,sum(valoriva) as Total_IVA,sum(totalfactu) as Total";

		//$campos = "vendedor,(Select nombre from tvendedor where facturas.vendedor=tvendedor.codven) as Nombre_Vendedor,sum(totalfactu) as Total_Ventas";
	} 

	//$sWhere="facturasped.cliente=tclientes.codcliente and facturasped.vendedor=tvendedor.codven";
	$sWhere = "fecha between '$fecha_inicial' and '$fecha_final' and anulada=0 and tipo='F' ";

		if ($filtro == 1) {

			$sWhere .= "and vendedor like '%$cod_ven%'";
		
		}
		if ($filtro == 2) {

			$sWhere .= "and cliente like '%$cod_cliente%'";
		
		}


	

	include 'pagination.php'; //include pagination file
	
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row = mysqli_fetch_array($count_query)) {
		$numrows = $row['numrows'];
	} else {
		echo mysqli_error($con);
	}
	//main query to fetch the data
	$sql="SELECT $campos FROM  $tables where $sWhere";
	$query = mysqli_query($con, "SELECT $campos FROM  $tables where $sWhere");
	//loop through fetched data


	if ($numrows > 0) {

?>

		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">


						<?php  if ($filtro == 0 ||$filtro==1 || $filtro==2 ) {
					?>

						<h3 class="box-title">Totales</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table  table-condensed table-hover table-striped ">
								
								<?php
								$finales = 0;

								while ($row = mysqli_fetch_array($query)) {
									$descuentos= $row['Descuentos'];
									$exc = $row['Excentos'];
									$noexc = $row['NoExcentos'];
									$iva = $row['Total_IVA'];
									$total = $row['Total'];
									
								?>
									<tr>
										<td class='text-center'>Descuentos:   <?php echo $descuentos; ?></td>
										<td class='text-center'>Excentos: <?php echo $exc; ?></td>
										<td class='text-center'>No Excentos: <?php echo $noexc; ?></td>
										<td class='text-center'>Total IVA:<?php echo $iva; ?></td>
										<td class='text-center'>Total: <?php echo $total; ?></td>
										

									</tr>
								<?php } ?>
								
							</table>

						</div>

				<?php
						}
					} else {

						echo "NO HAY DATOS QUE MOSTRAR";
					}
				?>
					</div><!-- /.box-body -->

				</div><!-- /.box -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	<?php
}

	?>