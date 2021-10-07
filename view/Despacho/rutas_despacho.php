<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="robots" content="noindex, nofollow" />
    <meta
      name="viewport"
      content="initial-scale=1,maximum-scale=1,user-scalable=no"
    />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"> </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1PxZswZmqRDxFMRgjtONx9SvBgcNvofU&libraries=drawing&callback=initMap" async> </script>
    
    
    
    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        position:relative;
        width: 550px;
         height: 550px;
         left: 30px;
            }
      .marker {
        background-image: url('../PedidosZicomtec/view/Ruta/mapbox-icon.png');
        background-size: cover;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
      }
      .mapboxgl-popup {
        max-width: 200px;
      }
      .mapboxgl-popup-content {
        text-align: center;
        font-family: 'Open Sans', sans-serif;
      }
         #coordenadas {     
        display: block;
        position: relative;
        margin: 0px auto;
        width: 40%;
        padding: 5px;
        border: none;
        border-radius: 7px;
        font-size: 15px;
		font-family: Montserrat;
        text-align: center;
        color: #000;
        background: #D6EAF8;
    }
    </style>

<?php include("head.php");?>
	<!-- daterange picker -->
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
        <h1><i class='fa fa-edit'></i> RUTAS DE DESPACHO</h1>
        </section>
        
    	<!-- Main content -->
      <section class="content">
          <!-- Default box -->
          <div class="box">
          <div class="box-body">
                          <div class="row">
                              <div class="col-md-4">

                                  <label>CAMIÓN</label>
                                  <select class="form-control vselect2" id="placa" name="placa" >
                                    <option value="">Seleccione Camión</option>
                                    
                                  </select>
                              </div>
                              <div class="col-md-3">
                                  <label>Fecha</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="fecha" name="purchase_date"  value="<?php echo date("Y/m/d")?>" >

                                    <div class="input-group-addon">
                                      <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                  </div>
                              </div>
                              <br/>

                              <div class="col-md-4">
                                <label></label>
                                   <button class="btn btn-default" type="button"  onclick="initMap();consulta()" > Consultar    <i class='fa fa-search'></i>
                                </button>
                              </div> 
                          </div>   
                           <br/><br/><br/>
                      <div class="row">  
                      <div class="col-md-5" >

                              <div id="map"></div>

                      </div> 

                      <div class="col-md-2" >
                      </div>
                      <div class="col-md-5" >

                              <div id="resultados"></div>
                          
                      </div>
                      </div>  
                      </div><!-- /.box-body -->
                 </div><!-- /.box -->	
             </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
    <?php include("footer.php");?>
  </div><!-- ./wrapper -->

<?php include("js.php");?>
<script src="dist/js/VentanaCentrada.js"></script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script src="plugins/select2/select2.full.min.js"></script>
  


<script>

$(document).ready(function(){
  $(".vselect2").select2();
		
		});
</script>

 

<script type="text/javascript">
    function initMap() {
     var placa=document.getElementById('placa').value;
      var fecha=document.getElementById('fecha').value;
        var consulta=$.ajax({
                                  type: "POST",                                  
                                  url: '../PedidosZicomtec/view/Despacho/chart_despachos.php',
                                  data: "placa="+placa+"&fecha="+fecha,  
                                  async: false
                                  
                                  }).responseText;
                                  
                                  var obj = jQuery.parseJSON(consulta);


      var map;
      var bounds = new google.maps.LatLngBounds();
      var mapOptions = {
        mapTypeId: 'roadmap'
      };

      map = new google.maps.Map(document.getElementById('map'), {
        mapOptions
      });

      map.setTilt(50);

      // Crear múltiples marcadores desde la Base de Datos 
      var marcadores =obj;      

      // Creamos la ventana de información con los marcadores 
      var mostrarMarcadores = new google.maps.InfoWindow(),
        marcadores, i;
      var pos = [];
      var pos2 = [];
      var ventanaInfo = [];
      // Colocamos los marcadores en el Mapa de Google 
      for (i = 0; i < marcadores.length; i++) {
      ventanaInfo[i]='<div class=\"info_content\"><h3>Cliente:'+ marcadores[i].razon+'</h3><p>' + marcadores[i].direccion +'  </p></div>';

        var position = new google.maps.LatLng(marcadores[i].latitud, marcadores[i].longitud);
        bounds.extend(position);
        marker = new google.maps.Marker({
          position: position,
          map: map,
          title: marcadores[i].direccion,
          label: `${i + 1}`,
          optimized: false,
        });

         // Colocamos la ventana de información a cada Marcador del Mapa de Google 
         google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                      mostrarMarcadores.setContent(ventanaInfo[i]);
                      mostrarMarcadores.open(map, marker);
                  }
              })(marker, i));

              pos[i] = parseFloat(marcadores[i].latitud);
        pos2[i] = parseFloat(marcadores[i].longitud);
        // Centramos el Mapa de Google para que todos los marcadores se puedan ver 
        map.fitBounds(bounds);

      }

      var poso = Array();

      for (let index = 0; index < pos.length; index++) {

        var latitude = pos[index];
        var longitude = pos2[index];

        poso.push({
          lat: latitude,
          lng: longitude
        });


      }

      linea = new google.maps.Polyline({


        path: poso,
        geodesic: true,
        strokeColor: "#385dcf",
        strokeOpacity: 1.0,
        strokeWeight: 2,
        
      });
      linea.setMap(map);

     

      // Aplicamos el evento 'bounds_changed' que detecta cambios en la ventana del Mapa de Google, también le configramos un zoom de 14 
      var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(12);
        google.maps.event.removeListener(boundsListener);
      });

    }

    // Lanzamos la función 'initMap' para que muestre el Mapa con Los Marcadores y toda la configuración realizada 
    //google.maps.event.addDomListener(window, 'load', initMap);
  </script>


<script>
function consulta ()
		{
      var codtrans=document.getElementById('placa').value;
  var fecha=document.getElementById('fecha').value;
		  var parametros = {'placa':codtrans,'fecha':fecha};
      $.ajax({
			type: "POST",
      url: '../PedidosZicomtec/view/Despacho/Consulta_despacho.php',
			data: "&placa="+codtrans+"&fecha="+fecha,
			beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			},
			success: function(datos){
			$("#resultados").html(datos);
			}
				});
    }
    
  </script> 
    

<script type="text/javascript">
	$(document).ready(function() {
		$( ".vselect2" ).select2({        
		ajax: {
			url: "ajax/camion_select2.php",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				// parse the results into the format expected by Select2.
				// since we are using custom formatting functions we do not need to
				// alter the remote JSON data
				return {
					results: data
				};
			},
			cache: true
			
			
			
		},
		minimumInputLength: 2
		
		}).on('change', function (e) {
			var value=this.value;
			//actualizar_campos(value,1);
			
		});
		});
</script>

</body>
</html>

