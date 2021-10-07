<?php
		include("libraries/inventory.php");

	
$purchase_order_id=intval($_GET['id']);
$numfacturacompleta = str_pad($purchase_order_id, 7, "0", STR_PAD_LEFT);


list ($empresa,$sucursal,$numerofac,$fecha,$cliente,$porviva,$vendedor,$vendidopor,$anulada,$motivoanul,$totalfactu,$excentos,$noexcentos,$valoriva, $saldo, $servicio, $notacredit,$promocion,$descuentos,$porcdesc1, $porcdesc2, $bodega, $actualizad, $tipo, $impreso,$vence,$fechacont,$costog,$costong,$forpago,$tipof,$transporte,$numerofacf,$codprecio,$centrocos, $vidrio,$observacion)=get_data("facturasped","numerofac",$numfacturacompleta);
	$fecha=date("d/m/Y",strtotime($fecha));
	$_SESSION['numerofac']=$purchase_order_id;
	list($empresa,$sucursal,$codcliente,$razonsocia,$nombrecom,$ruc)=get_data("tclientes","codcliente",$cliente);
	
	
	$employee_id=$_SESSION['nombre'];	
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
		  <h1><i class='fa fa-edit'></i> Editar Pedido</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Editar Pedido</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                        

                        <!-- *********************** Purchase ************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title ">Detalles del Pedido</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                	<div class="row">

											<div class="col-md-4">

												<label>Cliente</label>
												<select class="form-control select2" name="supplier_id" <?php echo $disabled;?>>
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

												<label>Orden de Compra Nº</label>
											<input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $purchase_order_id;?>" disabled="">
											</div>

									</div>


								<div class="row">
									<div class="col-md-2">
										<label>Estado</label>
										<select class="form-control"  id="estado" onchange="activarBoton()">
											<option value="0" selected="">Pendiente</option>
											<option value="1">Aceptada</option>
											<option value="2">Rechazada</option>
										</select>

                                        
                                    </div>
									
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
										<input type="text" class="form-control" name="comentarios" value="<?php echo $observacion;?>" onblur="actualizar_campos(this.value,5)" >
                                    </div>
									
									
                                    </div>
									

                                </div><!-- /.box-body -->
                                    </div>


                                <div class="box-footer pull-right">
									
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class='fa fa-plus'></i> Agregar productos</button>
									
									
									<button type="button" id="compra" class='btn btn-default' disabled="" onclick="orderToPurchase('<?php echo $purchase_order_id;?>');"> <i class='fa fa-shopping-cart'></i> Convertir en factura</button>
									
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
		$( "#resultados" ).load("./ajax/agregar_orden_compra.php");
		
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

		function calcular (id) {
	var precio_venta=document.getElementById('precio_venta_'+id).value;
			var cantidad=document.getElementById('cantidad_'+id).value;
			var descuento=document.getElementById('descuento_'+id).value;
			var stock=document.getElementById('stock_'+id).value;
    		var total = 0;	
    		var curr='USD';
	
    total = document.getElementById('total_'+id).innerHTML;
	

	if(descuento==0){

		total = (cantidad*precio_venta);

	}else{
		total =((cantidad*precio_venta)-((cantidad*precio_venta)*(descuento/100)));
	}
	
	document.getElementById('total_'+id).innerHTML = total;
	
	//if (stock<cantidad){
	//alert('No hay stock disponible');
	//return false;
	//					}
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
        url: "./ajax/agregar_orden_compra.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
		
		function actualizar_campos(valor, campo){
		parametros={"valor":valor,"campo":campo};
		$.ajax({
        url: "./ajax/agregar_orden_compra.php",
        data: parametros,
			 success: function(data){
			$("#resultados").html(data);
			}
		});
		}
		
		function actualizar_item(valor, item, product_item){
		parametros={"valor":valor,"item":item,"product_item":product_item};
		$.ajax({
        url: "./ajax/agregar_orden_compra.php",
        data: parametros,
			 success: function(data){
			$("#resultados").html(data);
			}
		});
		}
		
		function eliminar(id){
			parametros={"id":id};
			$.ajax({
			url: "./ajax/agregar_orden_compra.php",
			data: parametros,
				 success: function(data){
				$("#resultados").html(data);
				}
			});
			
		}
		
					
				

	</script>
	
	<script type="text/javascript">
	$(document).ready(function() {
		$( ".select2" ).select2({        
		ajax: {
			url: "ajax/supplier_select2.php",
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
			actualizar_campos(value,1);
			
		});
		});
</script>
	<script>
		function imprimir(purchase_order_id){
			VentanaCentrada('purchase-order-print-pdf.php?purchase_order_id='+purchase_order_id,'Orden de Compra','','1024','768','true');
		}
	</script>
	<script>
		function orderToPurchase(purchase_order_id){
			if (confirm('Realmente desea convertir el pedido Nº '+purchase_order_id+' en factura?'))
			{
				location.replace("transformar_factura.php?id=<?php echo $numfacturacompleta;?>");//Redirecciono al modulo de compras

			}
		} 
	</script>
	


	<script>

	function activarBoton(){

var lista = document.getElementById("estado");
var boton = document.getElementById("compra");
if(lista.selectedIndex == 1 )
  boton.disabled = false;
else{
  boton.disabled = true;
}

}
</script>

  </body>
</html>
