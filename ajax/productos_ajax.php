<?php

	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	//Inicia Control de Permisos
	/*/include("../config/permisos.php");
	$user_id = $_SESSION['user_id'];
	get_cadena($user_id);
	$modulo="Productos";
	permisos($modulo,$cadena_permisos);/*/
	//Finaliza Control de Permisos
	$curr='USD';
	/*/if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$query_validate=mysqli_query($con,"select product_id from inventory where product_id='".$id."'");
	$count=mysqli_num_rows($query_validate);
	
	if ($count==0){
		if($delete=mysqli_query($con, "DELETE FROM products WHERE product_id='$id'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
			}
			else
			{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
	} 
	else 
		{
			$aviso="Aviso!";
			$msj="Error al eliminar los datos. El producto se encuentra vinculado al inventario";
			$classM="alert alert-danger";
			$times="&times;";
		}
	
	} else {//No cuenta con los permisos
		$aviso="Acceso denegado!";
		$msj="No cuentas con los permisos necesario para acceder a este m?dulo.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}/*/
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	echo $product_code = mysqli_real_escape_string($con,(strip_tags($_REQUEST['product_code'], ENT_QUOTES)));
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	//$manufacturer_id = intval($_REQUEST['manufacturer_id']);
	$tables="tstock,precios,costos";
	$campos="tstock.codstock,tstock.nombre,tstock.alterno1,precios.precio,costos.cantidad";
	//$sWhere="products.manufacturer_id=manufacturers.id";
	$sWhere=" tstock.codstock=precios.codstock";
	$sWhere.=" and precios.codstock=costos.codstock";
	$sWhere.=" and tstock.nombre LIKE '%".$query."%'";
	$sWhere.=" and tstock.alterno1 LIKE '%".$product_code."%'";
	
	$sWhere.=" order by tstock.codstock";
	
	
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
	$reload = './permisos.php';
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if (isset($_REQUEST["id"])){
	?>
			<div class="<?php echo $classM;?>">
				<button type="button" class="close" data-dismiss="alert"><?php echo $times;?></button>
				<strong><?php echo $aviso?> </strong>
				<?php echo $msj;?>
			</div>	
	<?php
		}	
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de Productos</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>Código Producto</th>
							<th>Nombre Producto </th>
							<th>Código Alterno </th>
							<th class='text-center'>Stock</th>
							<th class='text-right'>Precio</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						//$campos="tstock.codstock,tstock.nombre,tstock.alterno1,precios.precio,costos.cantidad";

						while($row = mysqli_fetch_array($query)){	
							$product_id=$row['codstock'];
							$product_name=$row['nombre'];
							$product_code=$row['alterno1'];
							$stock=$row['cantidad'];
							$selling_price=$row['precio'];
													
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $product_id;?></td>
							<td><?php echo $product_name;?></td>
							<td><?php echo $product_code;?></td>
							<td><?php echo $stock;?></td>
							<td class='text-right'><?php echo moneyformat($selling_price,$curr);?></td>
							
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_product.php?id=<?php echo $product_id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $product_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
                    		</td>
						</tr>
						<?php }?>
						<tr>
							<td colspan='9'> 
								<?php 
									$inicios=$offset+1;
									$finales+=$inicios -1;
									echo "Mostrando $inicios al $finales de $numrows registros";
									echo paginate($reload, $page, $total_pages, $adjacents);
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
		  
