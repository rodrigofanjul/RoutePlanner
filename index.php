
<!DOCTYPE html>
<html>

<!-- Mirrored from gebweb.net/optimap/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Oct 2020 22:46:21 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<meta name="description" content="Planificador de viajes con múltiples destinos. Hasta 50 paradas.">
<title>Hoja de ruta | Google Maps</title>

<link rel="stylesheet" href="css/bootstrap-4.5.3-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="jquery-ui-1.12.0.custom/jquery-ui.min.css">
<link rel="stylesheet" href="css/style.css"  type="text/css" media="screen">
<link rel="stylesheet" href="css/print.css"  type="text/css" media="print">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="css/bootstrap-4.5.3-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/BpTspSolver.js"></script>
<script type="text/javascript" src="js/directions-export.js"></script>
<script type="text/javascript" src="js/tspaecb.js"></script>
<script type="text/javascript">
jQuery.noConflict();

function onBodyLoad() {
  var lat = 999;
  var lng = 999;
  var zoom = 8;
  // Try HTML5 geolocation.
  if (lat == 999 && lng == 999 && navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      lat = position.coords.latitude;
      lng = position.coords.longitude;
      continueLoad(lat, lng, zoom);
    }, function() {
      continueLoad(lat, lng, zoom);
    });
  } else {
    continueLoad(lat, lng, zoom);
  }
}

function continueLoad(lat, lng, zoom) {
  if (lat == 999 && lng == 999) {
    lat = 37.4419;
    lng = -122.1419;
  }
  loadAtStart(lat, lng, zoom);
}

function toggle(divId) {
  var divObj = document.getElementById(divId);
  if (divObj.innerHTML == "") {
    divObj.innerHTML = document.getElementById(divId + "_hidden").innerHTML;
    document.getElementById(divId + "_hidden").innerHTML = "";
  } else {
    document.getElementById(divId + "_hidden").innerHTML = divObj.innerHTML;
    divObj.innerHTML = "";
  }
}

function save() {
  var waypoints = tsp.getWaypoints();
  var addresses = tsp.getAddresses();
  var labels = tsp.getLabels();
  var storeObj = {};
  storeObj.destinations = new Array();
  storeObj.route = new Array();
  storeObj.mode = 0;
  storeObj.walk = (document.getElementById('walking').checked ? 1 : 0);
  storeObj.bike = (document.getElementById('bicycling').checked ? 1 : 0);
  storeObj.avoid = (document.getElementById('avoidHighways').checked ? 1 : 0);
  storeObj.avoidTolls = (document.getElementById('avoidTolls').checked ? 1 : 0);
  storeObj.metricUnits = (document.getElementById('metricUnits').checked ? 1 : 0);
  for (var i = 0; i < waypoints.length; ++i) {
    storeObj.destinations.push({ geo: { lat: waypoints[i].lat(), lng: waypoints[i].lng() } });
    if (addresses[i] != null && addresses[i] != "") {
      storeObj.destinations[i]['addr'] = addresses[i];
    }
    if (labels[i] != null && labels[i] != "") {
      storeObj.destinations[i]['label'] = labels[i];
    }
  }
  var order = tsp.getOrder();
  if (order != null) {
    for (var j = 0; j < order.length; ++j) {
      var i = order[j];
      storeObj.route.push({ geo: { lat: waypoints[i].lat(), lng: waypoints[i].lng() } });
      if (addresses[i] != null && addresses[i] != "") {
        storeObj.route[j]['addr'] = addresses[i];
      } else {
      }
      if (labels[i] != null && labels[i] != "") {
        storeObj.route[j]['label'] = labels[i];
      }
    }
  }

  jQuery.post('store.html', JSON.stringify(storeObj), function(data) {
    jQuery('#saveLink').val('https://optimap.net/index.php?id=' + data.id);
    jQuery('#saveLink').select();
    jQuery('#dialogSave').dialog("open");
  }, 'json');
}

jQuery(function() {
  jQuery("input:button").button();
  var ww = jQuery(window).width()-20;
  jQuery('#addressStr').width(Math.min(480, 0.75*ww));
  jQuery("#dialogProgress" ).dialog({
    height: Math.min(140, ww),
	  modal: true,
	  autoOpen: false
  });
  jQuery("#progressBar").progressbar({ value: 0 });
  jQuery("#dialogTomTom" ).dialog({
    height: 480,
  	width: Math.min(640, ww),
  	modal: true,
  	autoOpen: false
  });
  jQuery("#dialogGarmin").dialog({
    height: 480,
  	width: Math.min(640, ww),
  	modal: true,
  	autoOpen: false
  });
  jQuery('#dialogSave').dialog({
    height: 240,
    width: Math.min(480, ww),
    modal: true,
    autoOpen: false,
    buttons : {
      Ok: function() {
        jQuery(this).dialog("close");
      }
    }
  });
  jQuery('#dialogBulk').dialog({
    height: 320,
    width: Math.min(480, ww),
    modal: true,
    autoOpen: false
  });
  jQuery("#dialogHelp").dialog({
    height: 480,
    width: Math.min(640, ww),
    modal: true,
    autoOpen: false
  });
  jQuery("#dialogAbout").dialog({
    height: 480,
    width: Math.min(640, ww),
    modal: true,
    autoOpen: false
  });
  jQuery("#dialogOptions").dialog({
    height: 270,
    width: Math.min(340, ww),
    modal: true,
    autoOpen: false
  });
  jQuery("#dialogEdit").dialog({
    height: 480,
    width: Math.min(640, ww),
    modal: true,
    autoOpen: false
  });
  jQuery('#dialogSetLabel').dialog({
    height: 200,
    width: Math.min(480, ww),
    modal: true,
    autoOpen: false
  });
  jQuery("#dialogExport").dialog({
    height: 560,
    width: Math.min(340, ww),
    modal: true,
    autoOpen: false
  });

  jQuery('#setLabelCancel').click(function() {
    jQuery('#dialogSetLabel').dialog("close");
  });
  jQuery('#bulkButton').click(function() {
    jQuery('#dialogBulk').dialog('open');
    document.listOfLocations.inputList.focus();
    document.listOfLocations.inputList.select();
  });
  jQuery('#calculateButton').click(function() {
    jQuery('#dialogOptions').dialog('open');
  });
  jQuery('#helpButton').click(function() {
    jQuery('#dialogHelp').dialog('open');
  });
  jQuery('#aboutButton').click(function() {
    jQuery('#dialogAbout').dialog('open');
  });
  jQuery('#editButton').click(function() {
    jQuery('#dialogEdit').dialog('open');
  });
  jQuery('#exportButton').click(function() {
    jQuery('#dialogExport').dialog('open');
  });
  jQuery('.myMap').height(jQuery(window).height() - 200);
});
</script>

</head>

<body>
<div id="fb-root"></div>

<nav class="navbar navbar-light bg-light justify-content-between">
  <a class="navbar-brand"> Investigación operativa 2020 </a>
  <form class="form-inline">
  <!-- <td><div id="facebookShare"><div class="fb-like" data-send="true" data-layout="button_count" data-width="200" data-show-faces="true" data-font="arial"></div></div></td> -->
        <!-- <td><div id="twitterShare"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></div></td> -->
        <img src="icons\simbolo0.png" width="50" height="50">
  </form>
</nav>

<div class='container-fluid'>

<br>

</div>

<div class='row noprint'>
  <div class='col-md-9'>
    <form name="address" onSubmit="clickedAddAddress(); return false;">
      <table>
        <tr>
        
      <div class="input-group mb-3">
      <input id='addressStr' name="addressStr" type="text" class="form-control" placeholder="Inserte una dirección"/>
      <div class="input-group-append">
      <button type="button" value="Find!" onClick="clickedAddAddress()" class="btn btn-outline-secondary">Buscar</button>
      </div>
  </div>
         
        </tr>
      </table>
    </form>
  </div>
  <div class='col-md-3'>
    <table style="float: right;">
      <tr>
        <td><button type='button' value='Bulk add addresses' id='bulkButton' class="btn btn-info"> Volcar direcciones </button> </td>
        <!-- <td><button type='button' value='Help' id='helpButton' class="btn btn-info"> Ayuda </button></td> -->
        <!-- <td><button type='button' value='About' id='aboutButton' class="btn btn-info"> About </button></td> -->
      </tr>
    </table>
  </div>
</div>

<div class='row'>
  <div class='col-md-12 col-print-12'>
    <div id="map" class="myMap"></div>
  </div>
</div>

<div class='row noprint'>
  <div class='col-md-12 col-print-12'>
    <table>
      <tr>
        <td><button id='calculateButton' type='button' value='Calculate!' class="btn btn-primary"> Calcular </button></td>
        <!-- <td><button type='button' value='Edit' id='editButton' class="btn btn-primary"> Editar </button></td> -->
        <!-- <td><button type='button' value='Export' id='exportButton' class="btn btn-primary"> Exportar </button></td> -->
        <td><button type='button' value='Clear' onClick='startOver()' class="btn btn-primary"> Borrar Direcciones </button></td>
        <td><button type='button' value='Save' onClick='save()' class="btn btn-primary"> Guardar </button></td>
        <td><button type='button' value='Print' onClick='window.print()' class="btn btn-primary"> Imprimir </button></td>
      </tr>
    </table>
  </div>
</div>

<div class='row'>
  <div class='col-md-12 col-print-12'>
    <div id="path" class="pathdata"></div>
    <div id="my_textual_div"></div>
    <div id='likeText'>
      <p>Thank you for using OptiMap!
      I really appreciate if you share it.
      Wish you a safe trip -Geir (geir.engdahl@gmail.com)
      </p>
    </div>
  </div>
</div>

<!-- /33522877/dyn-header -->
<div class='row noprint'>
  <div id='div-gpt-ad-1469429582783-0' class='noprint col-md-12'></div>
</div>

<!-- Hidden stuff -->
<div id="dialogBulk" title='Volcar direcciones'>
  <form name="listOfLocations" onSubmit="clickedAddList(); return false;">
    <textarea name="inputList" rows="10" cols="70">Una dirección por linea</textarea><br>
    <input type="button" value="Add list of locations" onClick="clickedAddList();">
  </form>
</div>

<div id="exportData_hidden" class='hidden'></div>
<div id="exportLabelData_hidden" class='hidden'></div>
<div id="exportAddrData_hidden" class='hidden'></div>
<div id="exportOrderData_hidden" class='hidden'></div>
<div id="durationsData_hidden" class='hidden'></div>

<div id="dialogProgress" title="Calculando camino...">
  <div id="progressBar"></div>
</div>

<div id="dialogTomTom" title="Export to TomTom">
  <iframe name='tomTomIFrame' style='width: 580px; height: 400px'></iframe> 
</div>

<div id="dialogGarmin" title="Export to Garmin">
  <iframe name='garminIFrame' style='width: 580px; height: 400px'></iframe>
</div>

<div id="dialogSave" title="Your route link">
  <p>You can re-open this route later by going to
    <input id='saveLink' type='text' style="width: 100%;" value=""/></p>
  <p>You need to store this link somewhere (e.g in an email or document) to access your route later</p>
</div>

<div id="dialogSetLabel" title="Set name">
  <p>Enter name for location: <input id='setLabelInput' type='text' style="width: 100%;" value=""/></p>
  <input id='setLabelCancel' type='button' value='Cancel'/>
  <input id='setLabelOk' type='button' value='Ok'/>
</div>

<!-- TODO decir como usar la app -->
<div id='dialogHelp' title='Help'>
  <p>To add locations, simply left-click the map or enter an address
  either in the single address field, or in the bulk loader. </p>
  <p>The first location you add is considered to be the start 
  of your journey. If you click 'Calculate Fastest Roundtrip', it will
  also be the end of your trip. If you click 'Calculate Fastest A-Z Trip',
  the last location (the one with the highest number), will be the final
  destination.</p>
  <p>To remove or edit a location, click its marker.</p>
  <p>If more than 15 locations are specified, you are not guaranteed
  to get the optimal solution, but the solution is likely to be close
  to the best possible.</p>
  <p>You can re-arrange
  stops after the route is computed. To do this, open the 'Edit Route'
  section and drag or delete locations.</p>
  <p>Don't hesitate to contact me at
  <a href='mailto:geir.engdahl@gmail.com'>geir.engdahl@gmail.com</a>
  with questions, bugs and feedback!</p>
</div>

<div id='dialogAbout' title='About'>
  <p><span class="red">New!</span>&nbsp;<a href="https://gebweb.net/optimap-faq/">FAQ about Optimap</a>.
  <p>How it works: <a href="https://gebweb.net/blogpost/2007/07/05/behind-the-scenes-of-optimap/">Behind the Scenes of OptiMap</a></p>
  <p>Use on your website: <a href="https://gebweb.net/blogpost/2007/08/26/optimize-your-trips/">Optimize Your Trips</a></p>
  <p>
   You can specify a default starting position and zoom level,
   by adding http GET parameters center and zoom. E.g
   <a href="index826e.html?center=(60,10)&amp;zoom=6">https://gebweb.net/optimap/index.php?center=(60,10)&amp;zoom=6</a>.</p>
  <p>Up to 50 locations are accepted.</p>
</div>

<div id='dialogExport' title='Export'>
  <div id="exportGoogle"></div>
  <div id="garmin"></div>
  <div id="tomtom"></div>
  <br>
  <p>Advanced:</p>
  <div id="exportAddrButton"></div>
  <div id="exportAddrData"></div>
  <div id="exportDataButton"></div>
  <div id="exportData"></div>
  <div id="exportLabelButton"></div>
  <div id="exportLabelData"></div>
  <div id="exportOrderButton"></div>
  <div id="exportOrderData"></div>
</div>

<div id='dialogOptions' title='Opciones del camino'>
  <p>
    <form name="travelOpts">
      <!-- <input id='walking' type='checkbox'/> Caminando <span class='slowWarn red'></span><br> -->
      <input id='bicycling' type='checkbox'/> En Bicicleta <span class='slowWarn red'></span><br>
      <input id='avoidHighways' type='checkbox'/> Evitar carreteras <span class='slowWarn red'></span><br>
      <input id='avoidTolls' type='checkbox'/> Evitar peajes <span class='slowWarn red'></span><br>
      <!-- <input id='metricUnits' type='checkbox'/> Unidad Metrica (km) -->
    </form>
  </p>
  <p>
    <input class="calcButton" type="button" value="Calcular Agente Viajero" onClick="directions(0, false, document.forms['travelOpts'].bicycling.checked, document.forms['travelOpts'].avoidHighways.checked, document.forms['travelOpts'].avoidTolls.checked, true)"/>
    <input class="calcButton" type="button" value="Calcular Camino Economico" onClick="directions(1, false, document.forms['travelOpts'].bicycling.checked, document.forms['travelOpts'].avoidHighways.checked, document.forms['travelOpts'].avoidTolls.checked, true)"/>
    <!-- <input class="calcButton" type="button" value="Calculate In Order" onClick="orderedDirections(document.forms['travelOpts'].walking.checked, document.forms['travelOpts'].bicycling.checked, document.forms['travelOpts'].avoidHighways.checked, document.forms['travelOpts'].avoidTolls.checked, document.forms['travelOpts'].metricUnits.checked)"/> -->
  </p>
</div>

<div id='dialogEdit' title='Edit Route'>
  <div id="routeDrag"></div>
  <div id="reverseRoute"></div>
</div>

</div>

<!-- Non-blocking scripts -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?v=weekly&key=AIzaSyCRgK3LhRQrlxsm1xrPNwdtW-akcbhps08&callback=onBodyLoad&language=es">
</script>

<script src="jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>

</body>
</html>