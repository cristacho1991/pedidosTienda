<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	//include inventory functions
	include("../libraries/inventory.php");
	

			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Código</th>
					<th>Descripción</th>
					<th>Grava IVA?</th>
					<th>Cantidad</th>
					<th>Precio Unitario</th>
					<th>Total</th>
					
				</tr>
			
					<tr>
					<td class='col-xs-2'>
						
								<input type="text" class="form-control" style="text-align:right" id="codigo_"  value="" >
						
						</td>

						<td class='col-xs-3'>
						
							<textarea class="form-control" rows="16" id="descripcion_"></textarea>
						
						</td>

						<td class='col-xs-1.5'>
						
										<select name="iva" id="iva_" class='form-control'>
											<option value="1" >Si </option>
											<option value="0" >No</option>
										</select>
						
								
						</td>

						<td class='col-xs-1'>
						
								<input type="text" class="form-control" style="text-align:right" id="cantidad_"  value=""  >
						
						</td>
						
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><?php echo '$';?></div>
							<input type="text" class="form-control" style="text-align:right" id="precio_"  value="0" onchange="calcular_total()"  >
						</div>
						</td>
						
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><?php echo '$';?></div>
							<output type="text" class="form-control" style="text-align:right" id="total_"  value="0"   >
						</div>
						</td>
						<!--AGREGAR PRODUCTO AJAX-->
						
						<td><span class="pull-right"><a href="#" onclick="agregar_serv()"><i class="glyphicon glyphicon-plus " style="font-size:30px;color: #5CB85C;"></i></a></span></td>
					</tr>
			
			  </table>
			</div>
