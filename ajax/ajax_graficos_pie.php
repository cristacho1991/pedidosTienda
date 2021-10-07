<?php

require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario

$daterange = mysqli_real_escape_string($con, (strip_tags($_REQUEST['range'], ENT_QUOTES)));
$filtro = mysqli_real_escape_string($con, (strip_tags($_REQUEST['filtro'], ENT_QUOTES)));

$tables = "facturas";


	$campos = "cliente,(Select razonsocia from tclientes where facturas.cliente=tclientes.codcliente) as Nombre_Cliente,sum(totalfactu) as Total_Ventas_Clientes";


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
$sWhere = "fecha between '$fecha_inicial' and '$fecha_final' and tipo='F' and anulada=0	";

	$sWhere .= " group by cliente order by Total_Ventas_Clientes desc limit 10";

$sql = "SELECT $campos FROM  $tables where $sWhere";
$query = $con->query($sql); // Ejecutar la consulta SQL
$data = array(); // Array donde vamos a guardar los datos
while ($r = $query->fetch_object()) { // Recorrer los resultados de Ejecutar la consulta SQL
	$data[] = $r; // Guardar los resultados en la variable $data

}


?>
<!DOCTYPE html>
<html>

<head>
	<script src="plugins/chartjs/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

</head>

<body>

	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Ventas por Cliente</h3>
				</div><!-- /.box-header -->
				<div class="box-body">

					
						<div>
							<canvas id="pieChart" style="display: block; height: 500px; width: 1000px;" width="368" height="183" class="chartjs-render-monitor"></canvas>
						</div>

				</div><!-- /.box-body -->

			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->

	<script>
$(document).ready(function () {


var data1 = {
	labels : [	<?php foreach ($data as $d) : ?>

							"<?php echo $d->Nombre_Cliente ?>",

					<?php endforeach; ?>],
	datasets : [
		{
			label : "TeamA score",
			data : [<?php foreach ($data as $d) : ?>
	
					"<?php echo $d->Total_Ventas_Clientes ?>",

					<?php endforeach; ?>],
			backgroundColor : [
				"#DEB887",
				"#A9A9A9",
				"#9b10b0",
				"#F4A460",
				"#d11b40",
				"#1b33d1",
				"#1bd13f",
				"#dee851",
				"#d11bc8",
				"#2E8B57"
			],
			borderColor : [
				"#CDA776",
				"#989898",
				"#CB252B",
				"#d11bc8",
				"#dee851",
				"#d11b40",
				"#1b33d1",
				"#1bd13f",
				"#E39371",
				"#1D7A46"
			],
			borderWidth : [1, 1, 1, 1, 1]
		}
	]
};


var options = {
	title : {
		display : true,
		position : "top",
		fontSize : 18,
		fontColor : "#111"
	},
	legend : {
		display : true,
		position : "bottom"
	},
	plugins: {
    datalabels: {
      formatter: (value, ctx1) => {

        let sum = ctx1.dataset._meta[0].total;
        let percentage = (value * 100 / sum).toFixed(2) + "%";
        return percentage;


      },color: '#fff',
	 }
  }
};

var ctx1 = $("#pieChart");

var chart1 = new Chart( ctx1, {
	type : "pie",
	data : data1,
	options : options
});



});
</script>




</body>

</html>