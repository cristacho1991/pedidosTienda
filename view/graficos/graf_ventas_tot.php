<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title></title>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">


	<?php include("head.php"); ?>
	<!-- daterange picker -->
</head>

<body class="hold-transition <?php echo $skin; ?> sidebar-mini">
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
				<div class="row">

					<div class="col-xs-2">
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control pull-right" value="<?php echo "01" . date('/m/Y') . ' - ' . date('d/m/Y'); ?>" id="range" readonly>

						</div><!-- /input-group -->
					</div>

					<div class="col-xs-2">
						<select id="filtro" class='form-control' onchange="activa_nombre();">
							<option value="0">Todo </option>
							<option value="1">Por Vendedor</option>
							<option value="2">Por Cliente </option>
						</select>

					</div>

					<div class="col-xs-3">
						<input type="text" class="form-control" placeholder="Buscar por nombre " id='q' style="display:none">

					</div>
					<div class="col-xs-2">

						<input class="btn btn-primary" type="button" name="consultar" value="Consultar" onClick="totales();load(1);grafica_barras();grafica_pie();" />

					</div>


					<div class="col-xs-3">

						<div class="btn-group pull-right">


							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								Mostrar
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right">
								<li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
								<li onclick='per_page(25);' id='25'><a href="#">25</a></li>
								<li onclick='per_page(50);' id='50'><a href="#">50</a></li>
								<li onclick='per_page(100);' id='100'><a href="#">100</a></li>
								<li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
							</ul>

						</div>
					</div>


					<input type='hidden' id='per_page' value='15'>

				</div>
			</section>

			<!-- Main content -->
			<section class="content">
				<!-- Default box -->
				<div class="row">
					<div class="col-xs-12">
					<div class="totales"></div><!-- Datos ajax Final -->
					</div>
				</div>


				<div class="row">


					<div class="col-xs-6">
						<div class="outer_div"></div><!-- Datos ajax Final -->
					</div>
					<div class="col-xs-6">
						<div class="graf_pie"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">

						<div class="graf_barras"></div>

					</div>
				</div>

			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->
		<?php include("footer.php"); ?>
	</div><!-- ./wrapper -->

	<?php include("js.php"); ?>


	<script src="dist/js/VentanaCentrada.js"></script>
	<!-- Include Date Range Picker -->
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
	<script src="plugins/chartjs/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>


</body>

</html>


<script>
	$(function() {



		$('#range').daterangepicker({
			"locale": {
				"format": "MM/DD/YYYY",
				"separator": " - ",
				"applyLabel": "Aplicar",
				"cancelLabel": "Cancelar",
				"fromLabel": "Desde",
				"toLabel": "Hasta",
				"customRangeLabel": "Custom",
				"daysOfWeek": [
					"Do",
					"Lu",
					"Ma",
					"Mi",
					"Ju",
					"Vi",
					"Sa"
				],
				"monthNames": [
					"Enero",
					"Febrero",
					"Marzo",
					"Abril",
					"Mayo",
					"Junio",
					"Julio",
					"Agosto",
					"Septiembre",
					"Octubre",
					"Noviembre",
					"Diciembre"
				],
				"firstDay": 1
			},
			"linkedCalendars": false,
			"autoUpdateInput": false,
			"opens": "right"
		});
	});
</script>

<script>
	function activa_nombre() {
		var filtro = $("#filtro").val();
		console.log(filtro);

		if (filtro === '1' || filtro === '2') {

			$('#q').show();
			// $("#q").prop("disabled", false);

		} else if (filtro === '0') {

			$('#q').hide();
			//$("#q").prop("disabled", true);

		}


	}

	function load(page) {

		var filtro = $("#filtro").val();
		var range = $("#range").val();
		var query = $("#q").val();
		var per_page = $("#per_page").val();
		var query = $("#q").val();

		var parametros = {
			"action": "ajax",
			"page": page,
			'range': range,
			'filtro': filtro,
			'query': query,
			'per_page': per_page,
			'query': query
		};
		$("#loader").fadeIn('slow');
		$.ajax({
			url: './ajax/ajax_graficos_cons.php',
			data: parametros,
			beforeSend: function(objeto) {
				$("#loader").html("<img src='./img/ajax-loader.gif'>");
			},
			success: function(data) {
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}
</script>

<script>

function totales() {

var filtro = $("#filtro").val();
var range = $("#range").val();
var query = $("#q").val();
var per_page = $("#per_page").val();
var query = $("#q").val();

var parametros = {
	"action": "ajax",
	'range': range,
	'filtro': filtro,
	'query': query,
	'per_page': per_page,
	'query': query
};
$("#loader").fadeIn('slow');
$.ajax({
	url: './ajax/ajax_graficos_totales.php',
	data: parametros,
	beforeSend: function(objeto) {
		$("#loader").html("<img src='./img/ajax-loader.gif'>");
	},
	success: function(data) {
		$(".totales").html(data).fadeIn('slow');
		$("#loader").html("");
	}
})
}
</script>


<script>
	function grafica_barras() {
		var range = $("#range").val();
		var filtro = $("#filtro").val();
		var parametros = {
			'range': range,
			'filtro': filtro
		};
		$("#loader").fadeIn('slow');
		$.ajax({
			url: './ajax/ajax_graficos_barras.php',
			data: parametros,
			beforeSend: function(objeto) {
				$("#loader").html("<img src='./img/ajax-loader.gif'>");
			},
			success: function(data) {
				$(".graf_barras").html(data);

				console.log("okkkk");
			}
		})
	}
</script>

<script>
	function grafica_pie() {
		var range = $("#range").val();
		var filtro = $("#filtro").val();
		var parametros = {
			'range': range,
			'filtro': filtro
		};
		$("#loader").fadeIn('slow');
		$.ajax({
			url: './ajax/ajax_graficos_pie.php',
			data: parametros,
			beforeSend: function(objeto) {
				$("#loader").html("<img src='./img/ajax-loader.gif'>");
			},
			success: function(data) {
				$(".graf_pie").html(data);

				console.log("okkkk");
			}
		})
	}
</script>

