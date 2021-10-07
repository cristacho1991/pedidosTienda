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

		$campos = "tipo,numerofac,fecha,(Select razonsocia from tclientes where facturas.cliente=tclientes.codcliente) as Nombre_Cliente,descuentos,excentos,noexcentos,valoriva,totalfactu,(Select nombre from tvendedor where facturas.vendedor=tvendedor.codven) as Vendedor";

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
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 5; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row = mysqli_fetch_array($count_query)) {
		$numrows = $row['numrows'];
	} else {
		echo mysqli_error($con);
	}
	$total_pages = ceil($numrows / $per_page);

	$reload = './permisos.php';

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

						<h3 class="box-title">Listado de Pedidos</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table  table-condensed table-hover table-striped ">
								<tr>
									<th>Tipo</th>
									<th>Número Doc.</th>
									<th>Fecha Emi.</th>
									<th class='text-center'>Cliente</th>
									<th>Descuentos</th>
									<th>Excentos</th>
									<th>No Excentos</th>
									<th>Valor IVA</th>
									<th>Total Fact.</th>
									<th>Vendedor</th>

								</tr>
								<?php
								$finales = 0;

								while ($row = mysqli_fetch_array($query)) {
									$tipo = $row['tipo'];
									$numerofac = $row['numerofac'];
									$fecha = $row['fecha'];
									$nomcliente = $row['Nombre_Cliente'];
									$descuentos = $row['descuentos'];
									$excentos = $row['excentos'];
									$noexcentos = $row['noexcentos'];
									$valoriva = $row['valoriva'];
									$totalfactu = $row['totalfactu'];
									$vendedor = $row['Vendedor'];
									$finales++;
								?>
									<tr>
										<td class='text-center'><?php echo $tipo; ?></td>
										<td class='text-center'><?php echo $numerofac; ?></td>
										<td class='text-center'><?php echo $fecha; ?></td>
										<td class='text-center'><?php echo $nomcliente; ?></td>
										<td class='text-center'><?php echo $descuentos; ?></td>
										<td class='text-center'><?php echo $excentos; ?></td>
										<td class='text-center'><?php echo $noexcentos; ?></td>
										<td class='text-center'><?php echo $valoriva; ?></td>

										<td class='text-center'><?php echo $totalfactu; ?></td>
										<td class='text-center'><?php echo $vendedor; ?></td>


									</tr>
								<?php } ?>
								<tr>
									<td colspan='9'>
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