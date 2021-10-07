<?php

$q="select * from maenum order by cliente desc limit 1;";

	$query_next=mysqli_query($con,$q);
	$rw_next=mysqli_fetch_array($query_next);
	$next_insert=$rw_next['cliente'];
	$cod_cliente=$next_insert+1;
	$codclientecompleto = str_pad($cod_cliente, 6, "0", STR_PAD_LEFT);
//var_dump($codclientecompleto);
	
  $sql=mysqli_query($con,"INSERT INTO zicomtec_dataz.tclientes(empresa,sucursal,codcliente,razonsocia,nombrecom,ruc,pasaporte,cuentac,cuentaca,cuentacgas,excento,fechainici,cliendesde,direccion,telefono,mail,ciudad,ciudad2,zona2,dcalleprin,dnumero,dtransvers,provincia,localpropi,cupocredit,cupoa,zona,vendedor,obs,observa,calificaci,actividad,forpago,descuento,listapreci,contespeci,descprod,representa,direcciónr,teléfonor,movilr,fechadom,fechanacim,verificado,baja,fechultcom,barrio,noventa,vtacontado,fruc,frazons,fmail,fdireccion,ffono,fechasal,fmail2,fmail3,ciextran,diascred,refcli,region,latitud,longitud) 
	VALUES ('01','01','171281','','','',0,'','','',0,null,null,'','','','','','','','','','',null,null,null,'','','','',null,'','',null,'',null,'','','','','',null,null,'',0,null,'',0,0,'','','','','',null,'','',null,null,'','',0,0);");

?>

<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
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
		  <h1><i class='fa fa-edit'></i> Agregar nuevo cliente</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
		<div class="row">
		
        
        <!-- /.col -->
        <div class="col-md-12" >
		<form name="update_register" id="update_register" class="form-horizontal" method="post" enctype="multipart/form-data" >
		
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#details" data-toggle="tab" aria-expanded="false">Detalles del cliente</a></li>
             
			  
            </ul>
            <div class="tab-content">
              <div id="resultados_ajax"></div>
           
             

              <div class="tab-pane active" id="details">


              <div class="form-group">
                        <label for="codcli" class="col-sm-2 control-label">Código del cliente</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="codcli"  name="codcli" value="<?php echo $codclientecompleto;?>" required>
                        </div>
                        </div>
                        <div class="form-group">

                        <label for="razonsocia" class="col-sm-2 control-label">Nombre/Razón Social*</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="razonsocia"  name="razonsocia" value="" required>
                        </div>
                    </div>

				  
				 
					<div class="form-group">
                        <label for="ruc" class="col-sm-2 control-label">RUC/CI*</label>
                        <div class="col-sm-3">
                          <input class="form-control" id="ruc" name="ruc" required>
                        </div>
                        </div>
         <div class="form-group">

                        <label for="email" class="col-sm-2 control-label">Mail*</label>
                        <div class="col-sm-5">
                          <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    
                      </div>
			
					  <div class="form-group">
                        <label for="fono" class="col-sm-2 control-label">Teléfono*</label>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" id="fono" name="fono" required>
                        </div>
                        </div>
                        <div class="form-group">

                        <label for="dir" class="col-sm-2 control-label">Dirección*</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="dir" name="dir" required>
                        </div>
                      </div>
               
				<div class="form-group">
                    <div class="col-sm-offset-4 col-sm-4">
                      <button type="submit" class="btn btn-primary actualizar_datos">Guardar datos</button>
                    </div>
                  </div>

              </div>
              <!-- /.tab-pane -->
			  
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
		  </form>
        </div>
        <!-- /.col -->
      </div>
     
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php include("footer.php");?>
    </div><!-- ./wrapper -->
	<?php include("js.php");?>


	<script>
		$( "#update_register" ).submit(function( event ) {
		  $('.actualizar_datos').attr("disabled", true);
		  var parametros = $(this).serialize();
		  $.ajax({
				type: "POST",
				url: "./ajax/modificar/cliente.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados_ajax").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados_ajax").html(datos);
				$('.actualizar_datos').attr("disabled", false);
				window.setTimeout(function() {
				$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();});}, 5000);
				
			  }
		});		
		  event.preventDefault();
		});
	</script>

  </body>
</html>
