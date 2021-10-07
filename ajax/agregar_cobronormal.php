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
					<br/>
					<div class="form-group">
						<form name="add_name" id="add_name">
							<div class="table-responsive">
								<table class="table table-bordered" id="dynamic_field">
									<tr>
										<td>
											<div class="float-left "> Número de factura</div>
										</td>
										<td>
											<div class="float-left "> Medio de Pago</div>
										</td>
										<td>
											<div class="float-left "> Valor</div>
										</td>
										<td>
											<div class="float-left "> Emisor</div>
										</td>
										<td>
											<div class="float-left "> Fecha Documento</div>
										</td>

										<td>
											<div class="float-left "> Número de documento</div>
										</td>
										<td>
											<div class="float-left "> Banco</div>
										</td>
										<td>
											<div class="float-left "> SRI</div>
										</td>
										<td><button type="button" name="add" id="add" class="btn btn-success">Agregar Cobro</button></td>

								</table>
								<input type="button" name="submit" id="submit" class="btn btn-info" value="Enviar Información" />
							</div>
						</form>
					</div>
				</div>
				<!-- Fin Contenido -->
			</div>
		</div>
		<!-- Fin row -->

	</div>

</body>

</html>

<script>
	$(document).ready(function() {
		var i = 0;
		$('#add').click(function() {
			i++;
			$('#dynamic_field').append(
				'<tr id="row' + i + '">' +
				'<td><input type="text" id="factura' + i + '" name="factura[]" placeholder="Ingrese factura" class="form-control name_list" /></td>' +
				'<td><select class="form-control" id="medio' + i + '" name="medio[]" onchange="activarcombo(' + i + ')" ><option value="0">Seleccione:</option><option value="1">Efectivo</option><option value="2">Cheque</option><option value="3">Tarjetas Crédito</option><option value="4">Retención 2% fuente</option><option value="5">Retención  30% IVA</option><option value="6">Retención 70% IVA</option><option value="7">100 % Ret Iva Crédit</option><option value="8">Retención 8% en la F</option><option value="9">Retencion 1% Fuente</option><option value="10">Transferencia</option><option value="11">N/D Bancarias Ch. Po</option><option value="12">Retencion 1.75% Fuen</option><option value="13">Retencion 2.75% Fuen</option></select></td>' +
				'<td><input type="text" id="valor' + i + '" name="valor[]" placeholder="$" class="form-control name_list" /></td>' +
				'<td><input type="text" id="fecdoc' + i + '" name="fecdoc[]" placeholder="" class="form-control name_list"/></td>' +
				'<td><input type="text" id="emisor' + i + '" name="emisor[]" placeholder="" disabled="" class="form-control name_list"  /></td>' +
				'<td><input type="text" id="doc' + i + '" name="doc[]" placeholder="" disabled="" class="form-control name_list" /></td>' +
				'<td><select class="form-control" id="banco' + i + '" name="banco[]" disabled=""><option value="0">Seleccione:</option><option value="1">BANCO DEL PICHINCHA</option><option value="2">PRODUBANCO</option><option value="3">AZUAY</option><option value="4">INTERNACIONAL</option><option value="5">MM JARAMILLO</option><option value="6">BANCO DE GUAYAQUIL</option><option value="7">PACIFICO</option><option value="8">MACHALA</option><option value="9">AUSTRO</option><option value="10">LOS ANDES</option><option value="11">BOLIVARIANO</option><option value="12">LOJA</option><option value="13">UNIBANCO</option><option value="14">CITIBANK</option><option value="15">PROCREDIT</option><option value="16">BANCO CAPITAL</option><option value="17">BANCO DESARROLLO</option><option value="18">BANECUADOR</option><option value="19">BANDESARROLLO</option><option value="20">BANCO RUMIÑAHUI</option><option value="21">BANCO AMAZONAS</option></select></td>' +
				'<td><input type="text" id="sri' + i + '" name="sri[]" placeholder="" class="form-control name_list" /></td>' +
				'<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');
		});

		$(document).on('click', '.btn_remove', function() {
			var button_id = $(this).attr("id");
			$('#row' + button_id + '').remove();
		});

		$('#submit').click(function() {
			$.ajax({
				url: "inserta_cobro.php",
				method: "POST",
				data: $('#add_name').serialize(),
				success: function(data) {
					alert(data);
					$('#add_name')[0].reset();
				}
			});
		});

	});
</script>

<script>
	function activarcombo(id) {
		var cod = document.getElementById("medio" + id).value;


		if (cod === '2' || cod === '3') {
			$("#banco" + id).prop("disabled", false);
			$("#emisor" + id).prop("disabled", false);
			$("#doc" + id).prop("disabled", false);

		} else {
			$("#banco" + id).prop("disabled", true);
			$("#emisor" + id).prop("disabled", true);
			$("#doc" + id).prop("disabled", true);
		}

	}
</script>