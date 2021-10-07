<?php
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}
//$pattern = '/^\d+(?:\.\d{2})?$/';
		
		if (empty($_POST['codcli'])){
			$errors[] = "ID del cliente está vacío";
		} else if (empty($_POST['razonsocia'])){
			$errors[] = "Razon Social está vacío";
		}else if (empty($_POST['ruc'])){
			$errors[] = "Ruc está vacío";
		} else if (empty($_POST['email'])){
			$errors[] = "Email está vacío";
		} else if (empty($_POST['fono'])){
			$errors[] = "Telefono está vacío";
		} else if (empty($_POST['dir'])){
			$errors[] = "Dirección está vacío";
		}   elseif (
			!empty($_POST['razonsocia'])
			&& !empty($_POST['ruc'])
			&& !empty($_POST['email'])
			&& !empty($_POST['fono'])
			&& !empty($_POST['dir'])
			) {
		
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			// escaping, additionally removing everything that could be (html/javascript-) code
				$cod_cli=($_POST['codcli']);
                $razonsocia = mysqli_real_escape_string($con,(strip_tags($_POST["razonsocia"],ENT_QUOTES)));
				$ruc = mysqli_real_escape_string($con,(strip_tags($_POST["ruc"],ENT_QUOTES)));
				$email= mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
				$fono= mysqli_real_escape_string($con,(strip_tags($_POST["fono"],ENT_QUOTES)));
				$dir= mysqli_real_escape_string($con,(strip_tags($_POST["dir"],ENT_QUOTES)));
			
				// update data
                    $sql = "UPDATE zicomtec_dataz.tclientes SET razonsocia='".$razonsocia."',ruc='".$ruc."',direccion='".$dir."', telefono='".$fono."', mail='".$email."',
					fruc='".$ruc."', frazons='".$razonsocia."', fmail='".$email."', fdireccion='".$dir."', ffono='".$fono."' WHERE codcliente='171281'";
                    $query = mysqli_query($con,$sql);

                    // if user has been update successfully
                    if ($query) {
                        $messages[] = "Los datos han sido procesados exitosamente.";
                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo. ".mysqli_error($con);
                    }
                
			
		} else {
			$errors[] = " Desconocido";	
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