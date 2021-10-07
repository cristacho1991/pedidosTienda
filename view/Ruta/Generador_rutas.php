<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title></title>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"> </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1PxZswZmqRDxFMRgjtONx9SvBgcNvofU&libraries=drawing,geometry&callback=initMap" async> </script>
  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #map {
      width: 1630px;
      height: 800px;
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

  <?php include("head.php"); ?>
  <!-- daterange picker -->
</head>

<body class="hold-transition <?php echo $skin; ?> sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <?php include("main-header.php"); ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <?php include("main-sidebar.php"); ?>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <section class="content-header">
        <h1><i class='fa fa-edit'></i> GENERADOR DE RUTAS</h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="box">
          <div class="box-body">
            <div class="row">

              <div class="col-md-1">
                <label>Código de Zona:</label>
              </div>

              <div class="col-md-3">
                <input type="text" class="form-control" name="codigo" id="codigo">
              </div>

              <div class="col-md-5">

                <button class="btn btn-default" id="guardazona" type="button"> Guardar Zona <i class='fa fa-search'></i>
                </button>
              </div>

              <div class="col-md-1">
                <label>Cargar Zona:</label>
              </div>

              <div class="col-md-2">
                <select class="form-control vselect2" id="codigo_zona" name="codigo_zona">
                  <option value="">Seleccione Código de Zona</option>
                </select>
              </div>

            </div>
            <br />
            <div class="row">
              <div class="col-md-12">

                <div id="map">
                </div>

              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                INFORMACION
                <div id="info">
                </div>

              </div>
            </div>
            <div class="row">
              <div>
                <textarea id="geojson"></textarea>
              </div>
            </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php include("footer.php"); ?>
  </div><!-- ./wrapper -->

  <?php include("js.php"); ?>
  <script src="dist/js/VentanaCentrada.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>


  <script>
    $(document).ready(function() {
      initMap();

    });
  </script>

  <script type="text/javascript">
    var imported = {
      type: "FeatureCollection",
      features: [{
        "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [
            -73.985603, 40.748429
          ],
        },
        properties: {
          activity: "Entry",
        }
      }, ]
    };
    // set default drawing styles
    var styles = {
      polygon: {
        fillColor: '#00ff80',
        fillOpacity: 0.3,
        strokeColor: '#008840',
        strokeWeight: 1,
        clickable: true,
        editable: true,
        draggable: true, // turn off if it gets annoying
        zIndex: 1
      }
    }

    var features = {
      polygons: []
    };

    const markerList = [];

    function initMap() {

      var consulta = $.ajax({
        type: "POST",
        url: '../PedidosZicomtec/view/Ruta/Generador_chart.php',
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
      var marcadores = obj;




      // Creamos la ventana de información con los marcadores 
      var mostrarMarcadores = new google.maps.InfoWindow(),
        marcadores, i;
      var pos = [];
      var pos2 = [];
      var ventanaInfo = [];
      var latMarkersInside = [];
      var lngMarkersInside = [];
      // Colocamos los marcadores en el Mapa de Google 
      for (i = 0; i < marcadores.length; i++) {
        ventanaInfo[i] = '<div class=\"info_content\"><h3>Cliente:' + marcadores[i].razon + '</h3><p>' + marcadores[i].direccion + '  </p></div>';


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
      var markersInside = Array();

      for (let index = 0; index < pos.length; index++) {

        var latitude = pos[index];
        var longitude = pos2[index];

        poso.push({
          lat: latitude,
          lng: longitude
        });
      }

      // Aplicamos el evento 'bounds_changed' que detecta cambios en la ventana del Mapa de Google, también le configramos un zoom de 14 
      var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(12);
        google.maps.event.removeListener(boundsListener);
      });


      // initialize drawing tools
      var drawingManager = new google.maps.drawing.DrawingManager({
        // uncomment below line to set default drawing mode

        drawingControl: true,
        drawingControlOptions: {
          position: google.maps.ControlPosition.TOP_CENTER,
          drawingModes: ['polygon']
        },
        polygonOptions: styles.polygon
      });
      drawingManager.setMap(map);

      // for each drawing mode, set a listener for end of drawing
      drawingManager.addListener('polygoncomplete', function(polygon) {
        // delete drawing if doesn't have enough points
        if (polygon.getPath().getLength() < 3) {
          alert('Polygons must have 3 or more points.');
          polygon.getPath().clear();
        }
        // otherwise create new feature and delete drawing
        else {
          addFeature('Polygon', polygon.getPath());
          polygon.setMap(null);
        }
      });


      function addFeature(type, path) {

        var polygon = new google.maps.Polygon(styles.polygon);
        polygon.setPath(path);

        // delete vertex using right click
        polygon.addListener('rightclick', function(e) {
          if (e.vertex == undefined) return;
          if (polygon.getPath().getLength() == 3) {
            polygon.setMap(null);
            features.polygons = features.polygons.filter(isValid);
          } else {
            polygon.getPath().removeAt(e.vertex);
            //outputAsGeoJSON();
          }
        });

        // add it to our list of features
        features.polygons.push(polygon);

        // and display it on the map
        polygon.setMap(map);


        $('#guardazona').click(function() {

          coordenadas = polygon.getPath().getArray();
          json = JSON.stringify(coordenadas);
          b = 0;
          if (confirm('Realmente desea guardar esta zona?')) {


            for (let index = 0; index < pos.length; index++) {
              var curPositionB = new google.maps.LatLng(pos[index], pos2[index]);

              const markersInsidePolygon = google.maps.geometry.poly.containsLocation(curPositionB, polygon);

              if (markersInsidePolygon == true) {

                markersInside.push({
                  lat: pos[index],
                  lng: pos2[index]
                });
              }
            }

            var codigo = document.getElementById("codigo").value;

            var parametros = {
              'codigo': codigo,
              'poligono': coordenadas,
              'marcadores': markersInside,

            };

            parametros2 = JSON.stringify(parametros);


            console.log(parametros2);
            var consulta2 = $.ajax({
              type: "POST",
              url: '../PedidosZicomtec/view/Ruta/guarda_zona.php',
              data: parametros2,
              async: false,

              beforeSend: function() {
                $("#info").html("Procesando, espere por favor...");
              },
              success: function(response) {
                window.alert("Zona guardada correctamente");
                $("#info").html(response);
              }
            });

          }

        });
      }

      // utility function for reuse any time someone right clicks
      function isValid(f) {
        return f.getMap() != null;
      }
    }
  </script>



<script type="text/javascript">

	$(document).ready(function() {
		$( ".vselect2" ).select2({        
		ajax: {
			url: "ajax/zona_select2.php",
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
      console.log(value);	
      initMap2(value);
		});
		});
</script>

<script type="text/javascript">
    function initMap2(value) {
     var valor=value;
      
        var consultaZona=$.ajax({
                                  type: "POST",                                  
                                  url: '../PedidosZicomtec/view/Ruta/Generador_carga_zonas.php',
                                  data: "valor="+valor,  
                                  async: false
                                  
                                  }).responseText;
                                  
                                  var obj = jQuery.parseJSON(consultaZona);

                                 console.log(obj);

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
      var mostrarMarcadores = new google.maps.InfoWindow(),marcadores, i;
      var pos = [];
      var pos2 = [];
      var ventanaInfo = [];
      // Colocamos los marcadores en el Mapa de Google 
      for (i = 0; i < marcadores.marcadores.length; i++) {
      ventanaInfo[i]='<div class=\"info_content\"><h3>Código Zona:'+ marcadores['nombrezona']+'</h3><p>Cliente:'+ marcadores['marcadores'][i]['razonsocia']+'</p><p>' + marcadores['marcadores'][i]['direccion'] +'  </p></div>';

        var position = new google.maps.LatLng(marcadores['marcadores'][i]['latitud'], marcadores['marcadores'][i]['longitud']);
        bounds.extend(position);
        marker = new google.maps.Marker({
          position: position,
          map: map,
          title: marcadores['marcadores'][i]['direccion'],
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

              pos[i] = parseFloat(marcadores['marcadores'][i]['latitud']);
        pos2[i] = parseFloat(marcadores['marcadores'][i]['longitud']);
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
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
      });
      var latlong=Array();
        var lat=Array();
        var long=Array();


      for (let index = 0; index < marcadores.poligono.length; index++) {

        //lat[index]=marcadores['poligono'][index]['lat'];
        //long[index]=marcadores['poligono'][index]['lng'];

        var latitud=parseFloat(marcadores['poligono'][index]['lat']);
        var longitud=parseFloat(marcadores['poligono'][index]['lng']);
        latlong.push({
                  lat: latitud,
                  lng: longitud
                });
              }

var poligono = new google.maps.Polygon({
        path:latlong,
        map: map,
        fillColor: '#00ff80',
        fillOpacity: 0.3,
        strokeColor: '#008840',
        strokeWeight: 4,
      });

    }

    // Lanzamos la función 'initMap' para que muestre el Mapa con Los Marcadores y toda la configuración realizada 
    //google.maps.event.addDomListener(window, 'load', initMap);
  </script>

</body>

</html>