<?php

require_once("../config/db.php");
require_once("../config/conexion.php");
require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario

$daterange = mysqli_real_escape_string($con, (strip_tags($_REQUEST['range'], ENT_QUOTES)));
$filtro = mysqli_real_escape_string($con, (strip_tags($_REQUEST['filtro'], ENT_QUOTES)));

$tables = "facturas";

	$campos = "vendedor,(Select nombre from tvendedor where facturas.vendedor=tvendedor.codven) as Nombre_Vendedor,sum(totalfactu) as Total_Ventas";


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
$sWhere = "fecha between '$fecha_inicial' and '$fecha_final' and anulada=0 and tipo='F'";


	$sWhere.=" group by vendedor order by sum(totalfactu) desc";






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
</head>

<body>

	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Ventas</h3>
				</div><!-- /.box-header -->
				<div class="box-body">

						<div>
							<canvas id="barChart" style="display: block; height: 50px; width: 100px;" width="368" height="183" class="chartjs-render-monitor"></canvas>
						</div>
						

				</div><!-- /.box-body -->

			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->


	<script>
	$(function() {
	

var ctx = $("#barChart");

var data = {
	labels : [<?php foreach ($data as $d) : ?>
						//?php if ($filtro == 2) { ?>

							"<?php echo $d->Nombre_Vendedor ?>",

						//?php } else if ($filtro == 0 or $filtro==1) { ?>

							//?php echo $d->Nombre_Cliente ?>",

						//?php } ?>
					<?php endforeach; ?>],
	datasets : [
		{
			label : "TeamA score",
			data : [<?php foreach ($data as $d) : ?>

//?php if ($filtro == 2) { ?> 
	
	"<?php echo $d->Total_Ventas ?>",

		//?php } else if ($filtro == 0 or $filtro==1) { ?> 
		
	//?php echo $d->Total_Ventas_Clientes ?>",

//?php } ?>

<?php endforeach; ?>],
			backgroundColor : [
				getRandomColorHex(),
				getRandomColorHex(),
				getRandomColorHex(),
				getRandomColorHex(),
				getRandomColorHex()
			],
			borderColor : [
				"#111",
				"#111",
				"#111",
				"#111",
				"#111"
			],
			borderWidth : 1
		}
	]
};

var options = {
	title : {
		display : true,
		position : "top",
		text : "Total Ventas por Vendedor",
		fontSize : 18,
		fontColor : "#111"
	},
	legend : {
		display : false
	},
	scales : {
		yAxes : [{
			ticks : {
				min : 0
			}
		}]
	}
};

var chart = new Chart( ctx, {
	type : "bar",
	data : data,
	options : options
});

/**
 * function to generate random color in hex form
 */
function getRandomColorHex() {
	var hex = "0123456789ABCDEF",
		color = "#";
	for (var i = 1; i <= 6; i++) {
		color += hex[Math.floor(Math.random() * 16)];
	}
	return color;
}

});

</script>





	
</body>

</html>