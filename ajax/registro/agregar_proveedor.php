<?php
include("is_logged.php");//Archivo comprueba si el usuario esta logueado

$q="select * from maenum order by cliente desc limit 1;";

	$query_next=mysqli_query($con,$q);
	$rw_next=mysqli_fetch_array($query_next);
	$next_insert=$rw_next['cliente'];
	$cod_cliente=$next_insert+1;
	$codclientecompleto = str_pad($cod_cliente, 6, "0", STR_PAD_LEFT);

	$sql="INSERT INTO zicomtec_dataz.tclientes(empresa,sucursal,codcliente,razonsocia,nombrecom,ruc,pasaporte,cuentac,cuentaca,cuentacgas,excento,fechainici,cliendesde,direccion,telefono,mail,ciudad,ciudad2,zona2,dcalleprin,dnumero,dtransvers,provincia,localpropi,cupocredit,cupoa,zona,vendedor,obs,observa,calificaci,actividad,forpago,descuento,listapreci,contespeci,descprod,representa,direcciónr,teléfonor,movilr,fechadom,fechanacim,verificado,baja,fechultcom,barrio,noventa,vtacontado,fruc,frazons,fmail,fdireccion,ffono,fechasal,fmail2,fmail3,ciextran,diascred,refcli,region,latitud,longitud) 
	VALUES ('01','01','$codclientecompleto','','','',0,'','','',0,null,null,'','','','','','','','','','',null,null,null,'','','','',null,'','',null,'',null,'','','','','',null,null,'',0,null,'',0,0,'','','','','',null,'','',null,null,'','',0,0);";
		
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
        <div class="col-md-9">
		<form name="update_register" id="update_register" class="form-horizontal" method="post" enctype="multipart/form-data">
		
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#details" data-toggle="tab" aria-expanded="false">Detalles del cliente</a></li>
             
			  
            </ul>
            <div class="tab-content">
              <div id="resultados_ajax"></div>
           
             

              <div class="tab-pane active" id="details">

				  <div class="form-group">
                        <label for="razonsocia" class="col-sm-2 control-label">Nombre/Razón Social</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="razonsocia"  name="razonsocia" value="<?php echo $codclientecompleto;?>" required>
                        </div>
                    </div>
				 
					<div class="form-group">
                        <label for="ruc" class="col-sm-3 control-label">RUC/CI</label>
                        <div class="col-sm-9">
                          <input class="form-control" id="ruc" name="ruc" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Mail</label>
                        <div class="col-sm-9">
                          <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="fono" class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="fono" name="fono" required>
                        </div>
                      </div>
					 <div class="form-group">
                        <label for="dir" class="col-sm-3 control-label">Dirección</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="dir" name="dir" required>
                        </div>
                      </div>
               
				<div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
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
				url: "./ajax/modificar/producto.php",
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






<?php









        if (empty($_POST['razonsocia'])){
			$errors[] = "Nombre/Razón Social vacíos";
		}  elseif (empty($_POST['ruc'])) {
            $errors[] = "Ingresa RUC ó CI";
        }  elseif (empty($_POST['email'])) {
            $errors[] = "Ingresa Email";
        } elseif (empty($_POST['fono'])) {
            $errors[] = "Ingresa teléfono";
        } elseif (empty($_POST['dir'])) {
            $errors[] = "Ingresa Dirección";
        }  elseif (
			!empty($_POST['razonsocia'])
			&& !empty($_POST['ruc'])
			&& !empty($_POST['email'])
			&& !empty($_POST['fono'])
			&& !empty($_POST['dir'])
			
        ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
                $nombre = mysqli_real_escape_string($con,(strip_tags($_POST["razonsocia"],ENT_QUOTES)));
				$ruc = mysqli_real_escape_string($con,(strip_tags($_POST["ruc"],ENT_QUOTES)));
				$mail = mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
				$fono = mysqli_real_escape_string($con,(strip_tags($_POST["fono"],ENT_QUOTES)));
                $dir = mysqli_real_escape_string($con,(strip_tags($_POST["dir"],ENT_QUOTES)));
		
               
				if (!empty($ruc)){
					$sql_cliente=mysqli_query($con,"select * from tclientes where ruc='$ruc'");
					$count=mysqli_num_rows($sql_cliente);
				} else {
					$count=0;
				}
				if ($count==0){
				
					// write new  data into database
                   // $sql = "INSERT INTO suppliers (created_at,name,address1,city,state,postal_code,	country_id, work_phone, website, tax_number) VALUES('$created_at','$name','$address1','$city','$state','$postal_code', '$country_id','$work_phone','$website','$tax_number');";
                   
$sql="INSERT INTO zicomtec_dataz.tclientes(empresa,sucursal,codcliente,razonsocia,nombrecom,ruc,pasaporte,cuentac,cuentaca,cuentacgas,excento,fechainici,cliendesde,direccion,telefono,mail,ciudad,ciudad2,zona2,dcalleprin,dnumero,dtransvers,provincia,localpropi,cupocredit,cupoa,zona,vendedor,obs,observa,calificaci,actividad,forpago,descuento,listapreci,contespeci,descprod,representa,direcciónr,teléfonor,movilr,fechadom,fechanacim,verificado,baja,fechultcom,barrio,noventa,vtacontado,fruc,frazons,fmail,fdireccion,ffono,fechasal,fmail2,fmail3,ciextran,diascred,refcli,region,latitud,longitud) 
VALUES ('01','01','008999','$nombre','','$ruc',0,'','','',0,null,null,'$dir','$fono','$mail','','','','','','','',null,null,null,'','','','',null,'','',null,'',null,'','','','','',null,null,'',0,null,'',0,0,'$ruc','$nombre','$mail','$dir','$fono',null,'','',null,null,'','',0,0);";
	
					$query = mysqli_query($con,$sql);


                    // if has been added successfully
                    if ($query) {
                        $messages[] = "El cliente ha sido creado con éxito.";
						//$update=mysqli_query($con,"UPDATE maenum SET cliente=819");

					 } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";

					}
                } else {
					$errors[] = "Lo sentimos, el cliente ya se encuentra registrado.";
				}
			
		}else {
			$errors[] = "Error desconocido";	
		}	 
	
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}
?>			