<?php


	/* Connect To Database*/
	require_once("../config/db.php"); //Ccodigoontiene las variables de configuracion para conectar a la base de datos
	require_once("../config/conexion.php"); //Contiene funcion que conecta a la base de datos
	require_once("../libraries/inventory.php"); //Contiene funcion que controla stock en el inventario
	//include("../currency.php");//Archivo que obtiene los datos de la moneda


?>
									<div class="lista-producto float-clear" style="clear:both;">
									<ul class="list-group">
									<li class="list-group-item">
										<?php
										$i = 0;
										?>
										
										<div class="float-left"><input type="text" id="numfac<?php echo $i; ?>" disabled name="numfac[]" placeholder="" value="0015555" class="form-control" /></div>

										<div class="float-left">
													<select class="form-control" id="medio<?php echo $i; ?>" name="medio" onchange="activarcombo(<?php echo $i; ?>)">

														<?php
														$sql = "Select * from tforpago";
														$query2 = mysqli_query($con, $sql);
														while ($row2 = mysqli_fetch_array($query2)) {
															echo "<option>";
															echo $row2['nombre'];
															echo "</option>";
														}
														echo "<\n>";
														?>

													</select>
												</div>

												<div class="float-left"><input type="text" id="valor<?php echo $i; ?>" name="valor[]" placeholder="$" class="form-control" /></div>
												<div class="float-left"><input type="text" id="emisor<?php echo $i; ?>" name="emisor[]" placeholder="" disabled="" class="form-control" /></div>

												<div class="float-left">
													<div class="input-group">
														<input type="text" id="fecdoc<?php echo $i; ?>" name="fecdoc[]" placeholder="" class="form-control" disabled />
														<div class="input-group-addon">
															<a href="#"><i class="fa fa-calendar"></i></a>
														</div>
													</div>

												</div>
												<div class="float-left"><input type="text" id="doc<?php echo $i; ?>" name="doc[]" placeholder="" disabled="" class="form-control" /></div>

												<div class="float-left">
													<select class="form-control" id="banco<?php echo $i; ?>" name="banco" disabled>

														<?php
														$sql = "Select * from tbancos";
														$query2 = mysqli_query($con, $sql);
														while ($row2 = mysqli_fetch_array($query2)) {
															echo "<option>";
															echo $row2['desbanco'];
															echo "</option>";
														}
														echo "<\n>";
														?>

													</select>
												</div>
												<div class="float-left"><input type="text" id="sri<?php echo $i; ?>" name="sri[]" placeholder="" class="form-control" /></div>

												<div class="float-left"><input type="button" name="cobro" id="cobro<?php echo $i; ?>" class="btn btn-info" value="Realizar Cobro" onclick="realiza_cobro(<?php echo $i; ?>)" /></div>

										
										<?php
											$i++;
										

										?>

</li>
 </ul> 
</div>