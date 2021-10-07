<?php
	if (!isset($con)){
		exit;
	}
  include("./config/permisos.php");
?>
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/admin.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $_SESSION['nombre']; ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENÚ</li>
            <li class="<?php if (isset($home) and $home==1){echo "active";}?>">
              <a href="index.php">
                <i class="fa fa-home"></i> <span>Inicio</span> 
              </a>
              
            </li>
			
		   
<?php

////////////////////PEDIDOS//////////////////////////////
$user_id = $_SESSION['codven'];
$permisos=get_cadena($user_id);
    
$pedidos=1;
$facturacion=2;
$cobros=3;
$productos=4;
$clientes=5;
$rutas=6;
$informes=7;
$documentos=8;

    //for($i=0;$i2 < sizeof($permisos); $i++){
      if (in_array($pedidos, $permisos)) {
    ?>
			<li class="<?php if (isset($purchase_order) and $purchase_order==1){echo "active";}?>">
              <a href="purchase_order.php">
                <i class="fa fa-shopping-cart"></i> <span>Pedidos</span>
              </a>
      </li>

      <?php } if (in_array($facturacion, $permisos)) {?>

            <li class="<?php if (isset($purchase_order) and $purchase_order==1){echo "active";}?> treeview">
            <a href="#">
            <i class="fa fa-credit-card"></i> 
                <span>Facturación</span>
                <i class="fa fa-angle-left pull-right"></i> 
                </a>
              <ul class="treeview-menu">
      	
              <li class="<?php if (isset($products) and $products==1){echo "active";}?>"><a href="factura.php"><i class="glyphicon glyphicon-barcode"></i> Facturación</a></li>
                 <li class="<?php if (isset($products) and $products==1){echo "active";}?>"><a href="factura_servicios.php"><i class="glyphicon glyphicon-barcode"></i> Facturación de Servicios</a></li>

			      </ul>
            
            </li>

            <?php } if (in_array($cobros, $permisos)) {?>

            <li class="<?php if (isset($catalog) and $catalog==1){echo "active";}?> treeview">
              <a href="#">
                <i class="fa fa-th-large"></i>
                <span>Cobros</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
      	
               	<li class="<?php if (isset($products) and $products==1){echo "active";}?>"><a href="cobro_normal.php"><i class="glyphicon glyphicon-barcode"></i> Recibo Temporal</a></li>
                 <li class="<?php if (isset($products) and $products==1){echo "active";}?>"><a href="cobro_normal.php"><i class="glyphicon glyphicon-barcode"></i> Recibo Definitivo</a></li>

			      </ul>
            
            </li>
			
            <?php } if (in_array($productos, $permisos)) {?>
      	
                 <li class="<?php if (isset($products) and $products==1){echo "active";}?>">
                 <a href="products.php">
                   <i class="glyphicon glyphicon-barcode"></i> Productos</a></li>
			 
			  </a>
            </li>
		
            <?php } if (in_array($clientes, $permisos)) {?>
			
			<li class="<?php if (isset($suppliers) and $suppliers==1){echo "active";}?>">
              <a href="clientes.php">
                <i class="fa fa-users"></i> <span>Clientes</span>
              </a>
            </li>
      
            <?php } if (in_array($rutas, $permisos)) {?>

            <li class="<?php if (isset($catalog) and $catalog==1){echo "active";}?> treeview">
              <a href="#">
                <i class="fa fa-th-large"></i>
                <span>Rutas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
      	
               	<li class="<?php if (isset($rutas_vend) and $rutas_vend==1){echo "active";}?>"><a href="Rutas_vend.php"><i class="fa fa-road"></i> Ruta Vendedor Real</a></li>
                 <li class="<?php if (isset($rutas_desp) and $rutas_desp==1){echo "active";}?>"><a href="Rutas_despacho.php"><i class="fa fa-truck"></i> Ruta de Despacho</a></li>
                 <li class="<?php if (isset($rutas_desp2) and $rutas_desp2==1){echo "active";}?>"><a href="Rutas_despacho2.php"><i class="fa fa-truck"></i> Ruta de Despacho 2</a></li>
                 <li class="<?php if (isset($rutas) and $rutas==1){echo "active";}?>"><a href="Rutas_v.php"><i class="fa fa-truck"></i> Ruta Vendedor</a></li>
                 <li class="<?php if (isset($generador) and $generador==1){echo "active";}?>"><a href="Generador_rutas.php"><i class="fa fa-truck"></i> Generador Rutas</a></li>
                 <li class="<?php if (isset($consultarutas) and $consultarutas==1){echo "active";}?>"><a href="consulta_rutas.php"><i class="fa fa-truck"></i> Consultar Rutas</a></li>

			      </ul>
            
            </li>

            <?php } if (in_array($informes, $permisos)) {?>

            <li class="<?php if (isset($estadistico) and $estadistico==1){echo "active";}?>">
              <a href="graficos.php">
                <i class="fa fa-line-chart"></i> <span>Informes</span>
              </a>
            </li>

            <?php } if (in_array($documentos, $permisos)) {?>

            <li class="<?php if (isset($c) and $c==1){echo "active";}?> treeview">
              <a href="#">
                <i class="fa fa-th-large"></i>
                <span>Documentos electrónicos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
      	
               	<li class="<?php if (isset($xml) and $xml==1){echo "active";}?>"><a href="facturaxml.php"><i class="fa fa-road"></i> Autorización y envio de factura <br> electrónica</a></li>
                 <li class="<?php if (isset($pdf) and $pdf==1){echo "active";}?>"><a href="factura_ride.php"><i class="fa fa-truck"></i> Impresion RIDE factura</a></li>

			      </ul>
            
            </li>

            <?php } ?>

          </ul>
        </section>
        <!-- /.sidebar -->