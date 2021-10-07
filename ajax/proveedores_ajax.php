<?php
	include("is_logged.php");

	require_once ("../config/db.php");
	require_once ("../config/conexion.php");

	$user_id = $_SESSION['nombre'];

	
	/*/if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$idc=intval($id);
	echo "El identificador es".$id;
	
	$query_validate=mysqli_query($con,"select cliente from facturasped where cliente='".$idc."'");
	$count=mysqli_num_rows($query_validate);
		if ($count==0)
		{
			if($delete=mysqli_query($con, "DELETE FROM tclientes WHERE codcliente='$id'")){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
			}else{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
		}
		else
		{
			$aviso="Aviso!";
			$msj="Error al eliminar los datos. El proveedor se encuentra vinculado al modulo de compras";
			$classM="alert alert-danger";
			$times="&times;";
		}
		
	
	}/*/
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$tables="tclientes";
	$campos="*";
	$sWhere=" razonsocia LIKE '%".$query."%'";
	$sWhere.=" order by codcliente";
	
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	//$reload = './permisos.php';
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	

	
	if ($numrows>0){

	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de Clientes</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<tr>
							<th>ID</th>
							<th>RUC</th>
							<th>Nombre </th>
							<th>Dirección </th>
							
							
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['codcliente'];
							$tax_number=$row['ruc'];
							$name=$row['razonsocia'];
							$work_phone=$row['telefono'];
							$dir=$row['direccion'];
							$email=$row['mail'];
							$city=$row['ciudad'];
							$prov=$row['provincia'];
							
							
							$sql_prov=mysqli_query($con,"select nombre from tprovincias  where codprov='$prov'");
							$rw=mysqli_fetch_array($sql_prov);
							$prov_name=utf8_encode($rw['nombre']);
							if (!empty($prov_name)){	$signo_coma=",";}
							else {$signo_coma=" ";}
							
							$finales++;
						?>	
						<tr>
							<td><?php echo $id;?></td>
							<td><?php echo $tax_number;?></td>
							<td>
								<?php echo $name;?><br>
								<?php if (!empty($work_phone)){?>
								<i class='fa fa-phone'></i> <?php echo $work_phone;?>
								<?php } ?>
								<?php if (!empty($email)){?>
								<br><i class='fa fa-envelope'></i> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
								<?php }?>
							</td>
							<td>
								<?php
								echo $dir." ".$city." <br>";
								//echo $state."$signo_coma ".$country_name;
								
								?>
							</td>
						
							
												
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									
									<li><a href="#" data-toggle="modal" data-target="#proveedor_edit" onclick="editar('<?php echo $id;?>');"><i class='fa fa-edit'></i> Editar</a></li>
									
									<li><a href="#" onclick="eliminar('<?php echo $id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
                    		</td>
						</tr>
						<?php }?>	
						<tr>
							<td colspan='7'> 
								<?php 
									$inicios=$offset+1;
									$finales+=$inicios -1;
									echo "Mostrando $inicios al $finales de $numrows registros";
									echo paginate($page, $total_pages, $adjacents);
								?>
							</td>
						</tr>	
					</table>
					</div>
				</div><!-- /.box-body -->
				
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->	
	<?php	
		
}
?>          
		  
