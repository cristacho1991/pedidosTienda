<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	//include inventory functions
	include("../libraries/inventory.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('codstock','nombre');//Columnas de busqueda
		 $sTable = "tstock";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere.= ')';
			$sWhere.=" and baja=0";
			
		} else{
			$sWhere = " where baja=0";
		}
		
		
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './index.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Código</th>
					<th>Producto</th>
					<th>Unidad de medida</th>
					<th>Unidad por caja</th>
					<th>Stock</th>
					<th><span class="pull-right">Cantidad</span></th>
					<th><span class="pull-right">Precio Unitario</span></th>
					<th><span class="pull-right">Descuento</span></th>
					<th><span class="pull-right">Total</span></th>
					<th style="width: 36px;"></th>
				</tr>
				<?php
			
				while ($row=mysqli_fetch_array($query)){
					$id_producto=$row['codstock'];

					$product_code=$row['alterno1'];
					$product_name=$row['nombre'];
					$unimed=$row['unimed'];
					$upc=$row['upb'];
					//$descuento=$row['descu1'];
					?>

					<tr>
						<td><?php echo $id_producto; ?></td>
						<td><?php echo $product_name; ?></td>
						<td ><?php echo $unimed; ?></td>
						<td ><?php echo $upc; ?></td>
						
						<!--STOCK AJAX-->
						
						<td class='col-xs-2'>
								
						<?php
							
							$query2=mysqli_query($con,"SELECT * FROM  costos where codstock=".$id_producto." order by codstock limit 1");
							while ($list=mysqli_fetch_array($query2)){
								$stock=$list['cantidad'];
								?>
               				  <input class="form-control" id="stock_<?php echo $id_producto; ?>" value="<?php echo $stock;?>" disabled="disabled">
							    <?php
							}
						?>
							
						</td>
						<!--CANTIDAD AJAX-->
						<td class='col-xs-1'>
							<div class="pull-right">
								<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>"  value="0"  onchange="calcular('<?php echo $id_producto ?>');">
							</div>
						</td>
						<!--PRECIO VENTA AJAX-->
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><?php echo '$';?></div>

							<?php

							$consulta=mysqli_query($con,"select * from precios where codstock=".$id_producto."");
							while ($row2=mysqli_fetch_array($consulta)){	
								$buying_price=$row2['precio'];
								$buying_price=number_format($buying_price,4,'.','');
							?>

							<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $id_producto; ?>" disabled="disabled" 
							value="<?php echo $buying_price;?>" >
							<?php
							}

							?>
						
						</div>
						</td>
					<!--DESCUENTO AJAX-->
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><?php echo '%';?></div>
							<input type="text" class="form-control" style="text-align:right" id="descuento_<?php echo $id_producto; ?>"  value="0" onchange="calcular('<?php echo $id_producto ?>');" >
						</div>
						</td>

					<!--TOTAL AJAX-->
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><?php echo '$';?></div>
							<output type="text" class="form-control" style="text-align:right" id="total_<?php echo $id_producto; ?>"  value="0"   >
						</div>
						</td>

						<!--AGREGAR PRODUCTO AJAX-->
						<td><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-shopping-cart " style="font-size:30px;color: #5CB85C;"></i></a></span></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload,$page, $total_pages, $adjacents);
					?></span>
					</td>
				</tr>
			  </table>
			</div>
				
			<?php
		}
	}

	
?>