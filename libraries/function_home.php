<?php
function recently_products(){
	global $con;

	
	$sql=mysqli_query($con,"select * from  tstock where tstock.baja <1 order by codstock desc limit 0, 5");
	?>
	<ul class="products-list product-list-in-box">
	<?php
	while ($rw=mysqli_fetch_array($sql)){
		$product_id=$rw['codstock'];
		$product_name= $rw['nombre'];
		$cod_alterno= $rw['alterno1'];
		$selling_price= $rw['costoprom'];
		$unimed= $rw['unimed'];
		//$image_path	= $rw['image_path'];
		?>
		<li class="item">
         <!--<div class="product-img">
                <img src="<?//php echo $image_path;?>" alt="Product Image">
            </div>-->
            <div class="product-info">
				<a href="edit_product.php?id=<?php echo $product_id;?>" class="product-title"><?php echo $product_name;?> <span class="label label-info pull-right"><?php echo $selling_price;?></span></a>
                <span class="product-description">
                    <?php echo $unimed;?>
                </span>
            </div>
        </li><!-- /.item -->		
		<?php
	}
	?>
	</ul>
	<?php
	
}
function latest_order(){
	global $con;
	
	
	$sql=mysqli_query($con,"select * from facturasped where anulada=0 order by 	numerofac desc limit 0,10");
	while ($rw=mysqli_fetch_array($sql)){

		$purchase_order_id=$rw['numerofac'];
		
		$idcliente=$rw['cliente'];
		$sql_s=mysqli_query($con,"select razonsocia from tclientes where codcliente='".$idcliente."'");
		$rw_s=mysqli_fetch_array($sql_s);
		$nombre_cliente=$rw_s['razonsocia'];
		$date_added=$rw['fecha'];
		list($date,$hora)=explode(" ",$date_added);
		list($Y,$m,$d)=explode("-",$date);
		$fecha=$d."-".$m."-".$Y;
		$total=$rw['totalfactu'];

		

		?>
	<tr>
        <td><a href="edit_purchase_order.php?id=<?php echo $purchase_order_id;?>"><?php echo $purchase_order_id;?></a></td>
        <td><?php echo $nombre_cliente;?></td>
        <td><?php echo $fecha;?></td>
        <td class='text-right'><?php echo $total;?></td>
    </tr>
		<?php
		
	}
}


	
?>