<?php
	function add_inventory($product_id,$product_quantity,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."' ");//Consulta para verificar si el producto se encuentra reguistrado en  el inventario
		$count=mysqli_num_rows($sql);
		if ($count==0){
			$insert=mysqli_query($con,"insert into inventory (product_id, product_quantity,branch_id) values ('$product_id','$product_quantity','$branch_id')");//Ingresa un nuevo producto al inventario
		} else {
			$sql2=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."'");
			$rw=mysqli_fetch_array($sql2);
			$old_qty=$rw['product_quantity'];//Cantidad encontrada en el inventario
			$new_qty=$old_qty+$product_quantity;//Nueva cantidad en el inventario
			$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$new_qty."' WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");//Actualizo la nueva cantidad en el inventario
		}
	}
	
	function remove_inventory($product_id,$product_quantity,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."'");
		$rw=mysqli_fetch_array($sql);
		$old_qty=$rw['product_quantity'];//Cantidad encontrada en el inventario
		$new_qty=$old_qty-$product_quantity;//Nueva cantidad en el inventario
		$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$new_qty."' WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");//Actualizo la nueva cantidad en el inventario
	}
	function update_buying_price($product_id,$buying_price){
		global $con;//Variable de conexion
		$update=mysqli_query($con,"UPDATE products SET buying_price='".$buying_price."' WHERE product_id='".$product_id."'");
	}
	
	function get_stock($product_id, $branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"SELECT 	product_quantity FROM inventory WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");
		$rw=mysqli_fetch_array($sql);
		$stock=number_format($rw['product_quantity'],0,'.','');
		return $stock;
	}
	function is_service($product_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from products where product_id='".$product_id."' and is_service='1'");
		$count=mysqli_num_rows($sql);
		return $count;
	}

	function add_purchase_product($purchase_id,$product_id,$qty, $unit_price,$branch_id){
		global $con;//Variable de conexion
		$insert=mysqli_query($con, "INSERT INTO purchase_product (purchase_id,product_id,qty,unit_price,branch_id) VALUES ('$purchase_id','$product_id','$qty','$unit_price','$branch_id')");
	}
	
	function add_inventory_tweaks_product($inventory_tweak_id,$product_id,$qty, $unit_price,$branch_id){
		global $con;//Variable de conexion
		$sql="INSERT INTO inventory_tweaks_product (id, inventory_tweak_id, product_id, qty, unit_price, branch_id) VALUES 
		(NULL, '$inventory_tweak_id', '$product_id', '$qty', '$unit_price', '$branch_id');";
		$insert=mysqli_query($con, $sql);
	}

	
	//La siguiente funcion obtine un campo de la base de datos pasando como
	// parametros el nombre de la tabla, columna a retorna el campo a buscar dentro de  la dba_close
	// y el termino de bussqueda en la base de datos. Retorna solo (1) resultado
	function get_id($table,$row,$condition,$equal){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select $row from $table where $condition='$equal' limit 0,1");
		$rw=mysqli_fetch_array($sql);
		$result= $rw[$row];
		return $result;
	} 
	function update_table($table,$row,$value,$condition,$equal){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"update $table SET $row='$value' where $condition='$equal'");
	}

	function get_data($table, $row,$value){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from $table where $row='$value' ");
		$rw=mysqli_fetch_array($sql);
		return $rw;
	}
	
	function orderToPurchase($purchase_order_id,$user_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con, "select * from products,  purchase_order_product where products.product_id= purchase_order_product.product_id and  purchase_order_product.purchase_order_id='$purchase_order_id' and oc='1'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$qty=$row['qty'];
			$unit_price=$row['unit_price'];
			$branch_id=$row['branch_id'];
			//add_tmp($product_id, $qty, $unit_price, $user_id,0,$branch_id);
			
		}
	
	}


	function get_tax(){
		global $con;
		$sql=mysqli_query($con,"SELECT tax FROM  business_profile where  business_profile.id=1");
		$row=mysqli_fetch_array($sql);
		$tax=$row["tax"];
		return $tax;
	}
	function next_insert_id($table){
		global $con;
		$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = '$table'";
		$query_next=mysqli_query($con,$next);
		$rw_next=mysqli_fetch_array($query_next);
		$next_insert=$rw_next['AUTO_INCREMENT'];
	    return $next_insert;
	}
	
	function update_selling_price($product_id,$buying_price){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select profit from products where product_id='$product_id'");
		$rw=mysqli_fetch_array($sql);
		$utilidad=intval($rw['profit']);

		$utilidad=($buying_price * $utilidad) /100;
		$precio_venta=$buying_price + $utilidad;
		$selling_price=number_format($precio_venta,2,'.','');
		
		
		$update=mysqli_query($con,"UPDATE products SET selling_price='".$selling_price."' WHERE product_id='".$product_id."'");
	}
	
	function adjustment_inventory($product_id,$product_quantity){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."'");//Consulta para verificar si el producto se encuentra reguistrado en  el inventario
		$count=mysqli_num_rows($sql);
		if ($count==0){
			$insert=mysqli_query($con,"insert into inventory (product_id, product_quantity) values ('$product_id','$product_quantity')");//Ingresa un nuevo producto al inventario
		} else {
			$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$product_quantity."' WHERE product_id='".$product_id."'");//Actualizo la nueva cantidad en el inventario
		}
	}
	

	
	function list_branch_offices(){
		global $con;
		$query=mysqli_query($con,"SELECT id, code, name FROM  branch_offices where status=1 order by id");
		return $query;
	}
	
	//Funcion para sumar dias
	function sumardias($fecha,$dias){
		$nuevafecha = strtotime ( $dias." day" , strtotime ( $fecha ) ); 
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha ); //formatea nueva fecha 
		return $nuevafecha; //retorna valor de la fecha 
	}
	
	
	function currencyConverter($from_Currency,$to_Currency,$amount) {
		if ($from_Currency==$to_Currency){
			return $amount;
		}
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);
	$encode_amount = $amount;
	$get = file_get_contents("https://www.google.com/finance/converter?a=$encode_amount&from=$from_Currency&to=$to_Currency");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);
	$converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
	return $converted_currency;
	}
	
	
	function consulta_ventas_graficos($mes,$vendedor){
		global $con;
		$query=mysqli_query($con,"SELECT id, code, name FROM  branch_offices where status=1 order by id");
		return $query;
	}
?>	