<?php
		include("libraries/inventory.php");

	
$purchase_order_id=intval($_GET['id']);
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);



list ($empresa,$sucursal,$numerofac,$fecha,$cliente,$porviva,$vendedor,$vendidopor,$anulada,$motivoanul,$totalfactu,$excentos,$noexcentos,$valoriva, $saldo, $servicio, $notacredit,$promocion,$descuentos,$porcdesc1, $porcdesc2, $bodega, $actualizad, $tipo, $impreso,$vence,$fechacont,$costog,$costong,$forpago,$tipof,$transporte,$numerofacf,$codprecio,$centrocos, $vidrio,$observacion)=get_data("zicomtec_dataz.facturasped","numerofac",$numfacturacompleta);
	$fecha=date("d/m/Y",strtotime($fecha));
	$_SESSION['numerofac']=$purchase_order_id;
	list($empresa,$sucursal,$codcliente,$razonsocia,$nombrecom,$ruc)=get_data("zicomtec_dataz.tclientes","codcliente",$cliente);
	
	
	$employee_id=$_SESSION['nombre'];

	$update=mysqli_query($con,"UPDATE zicomtec_dataz.facturasped SET anulada=1 WHERE numerofac='".$numfacturacompleta."'");

?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  	<?php 
		
		include("modal/buscar_productos.php");
		
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
		  <h1><i class='fa fa-edit'></i>Factura</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Factura</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                        

                        <!-- *********************** Purchase ************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title ">Detalles de la Factura</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                	<div class="row">

											<div class="col-md-4">

												<label>Cliente</label>
												<select class="form-control select2" disabled="" name="supplier_id" <?php echo $disabled;?>>
													<option value="<?php echo $codcliente;?>"><?php echo $razonsocia;?></option>
													
												</select>
											</div>
											<div class="col-md-2">
												<label>Fecha</label>
												<div class="input-group">
													<input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $fecha;?>" disabled="">

													<div class="input-group-addon">
														<a href="#"><i class="fa fa-calendar"></i></a>
													</div>
												</div>
											</div>
											<div class="col-md-3">

												<label>Factura Nº</label>
											<input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $purchase_order_id;?>" disabled="">
											</div>

									</div>


								<div class="row">
									
									
                                    <div class="col-md-1">
										<label>Vendedor</label>
										
									<?php
									$rec=mysqli_query($con,"select * from tvendedor where nombre='".$employee_id."'");
									while($row=mysqli_fetch_array($rec))
												{
													$codven=$row['codven'];
													?>


												<input type="text" class="form-control" name="vendedor" value="<?php echo $codven;?>" disabled="disabled" onblur="actualizar_campos(this.value,4)" >

												<?php			
												}
									
									?>
                                   
									
									</div>

										
									<div class="col-md-6">
										<label>Comentarios u observaciones</label>
										<input type="text" class="form-control" disabled="" name="comentarios" value="<?php echo $observacion;?>" onblur="actualizar_campos(this.value,5)" >
                                    </div>
									
									
                                    </div>
									

                                </div><!-- /.box-body -->
                                    </div>


                                
                            </div>
                            <!-- /.box -->
                            </form>
                        </div>
                        <!--/.col end -->
						


                    </div>
					<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
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
        //Initialize Select2 Elements
		$(".select2").select2();
		$( "#resultados" ).load("./ajax/agregar_factura.php");
		
	});
	
		$(document).ready(function(){
			load(1);
			
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_compras.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}

		
	
function agregar (id)
		{
			var precio_venta=document.getElementById('precio_venta_'+id).value;
			var cantidad=document.getElementById('cantidad_'+id).value;
			var descuento=document.getElementById('descuento_'+id).value;
			var stock=document.getElementById('stock_'+id).value;

	
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			

						//Fin validacion
			
			$.ajax({
        type: "POST",
        url: "./ajax/agregar_factura.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
		
	
	</script>
	

	
  </body>
</html>
