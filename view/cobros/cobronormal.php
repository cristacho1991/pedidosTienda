<?php
setlocale(LC_MONETARY, 'en_US');
	 
?>
<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  <?php 
		
		include("modal/modal_cobro.php");
		
	?>
    <div class="wrapper">
      <header class="main-header">
		<?php include("main-header.php");?>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
		<?php include("main-sidebar.php");?>
      </aside>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
		

        <section class="content-header">
		  <h1><i class='fa fa-edit'></i> Cobros</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo Cobro</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
               
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalles de cobro</h3>
                                </div>

								<div class="box-background">
                                <div class="box-body">
                                    <div class="row">

											<div class="col-md-4">

												<label>Cliente</label>
												<select class="form-control select2" id="select_cliente" name="supplier_id" >
													<option value="">Selecciona Cliente</option>
													
												</select>
											</div>


											<div class="col-md-3">
												<label>Fecha</label>
												<div class="input-group">

											
												<?php
									$rec=mysqli_query($con,"select * from maenum order by cliente desc limit 1;	");
									while($row=mysqli_fetch_array($rec))
												{
													$fecha=$row['fechaproc'];
													?>


												<input type="text" class="form-control" name="fecha" value="<?php echo $fecha;?>" disabled="disabled" >

												<?php			
												}
									
									?>
													<div class="input-group-addon">
														<a href="#"><i class="fa fa-calendar"></i></a>
													</div>
												</div>
											</div>
											
									
									  <div class="col-md-3">
											<label>Recibo Referencia</label>
											<input type="text" class="form-control" name="comentarios" 
											 >
										</div>
									
									
										</div>
										
                                </div><!-- /.box-body -->
                                    </div>
									 
									<div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalle</h3>
								</div>
			                     </div>
                            <!-- /.box -->
                            </form>
                        </div>
                        <!--/.col end -->
					    </div>
						<div id="codigo_cliente" class='col-md-12' style="margin-top:4px"></div>

					<div id="resultados" class='col-md-12' style="margin-top:4px"></div>
					<div id="filas" class='col-md-12' style="margin-top:4px"></div>

            </div><!-- /.box-body -->
            
          </div><!-- /.box -->	
     
        </section><!-- /.content -->
	
      </div><!-- /.content-wrapper -->
      <?php include("footer.php");?>
    </div><!-- ./wrapper -->
	<?php include("js.php");?>
	<!-- Select2 -->
	
	<script src="plugins/select2/select2.full.min.js"></script>
	<script src="dist/js/VentanaCentrada.js"></script>
	<script>

	$(function () {

		$(".select2").select2();
	});
	
		$(document).ready(function(){
			load(1);
			
		});

		function load(page){
			var q= $("#q").val();
	
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/cliente_facturas.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#resul_cliente').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#resul_cliente').html('');
					
				}
			})
		}

	function agregar ()
		{
				$.ajax({
			type: "POST",
			url: "./ajax/agregar_cobronormal.php",
			beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			},
			success: function(datos){
			$("#resultados").html(datos);
			}
				});
		}
		
		
	

		function valor_cliente(valor){
		parametros={"valor":valor};
		$.ajax({
			

        url: "./ajax/cliente_facturas.php",
        data: parametros,
			 success: function(data){
				$("#codigo_cliente").html(data);
			}
		});
		}


	</script>
	
	<script type="text/javascript">
	$(document).ready(function() {
		$( ".select2" ).select2({        
		ajax: {
			url: "ajax/cobro_cliente_select2.php",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
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
		
		}).on('change', function (e) {
			var value=this.value;
			valor_cliente(value);

		});
		});
</script>
	

  </body>
</html>