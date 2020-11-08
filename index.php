
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="description" content="Planificador de viajes con múltiples destinos. Hasta 50 paradas.">
<title>Hoja de ruta | Google Maps</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="assets/jquery-ui-1.12.0.custom/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/style.css"  type="text/css" media="screen">
<link rel="stylesheet" href="assets/css/print.css"  type="text/css" media="print">
<link rel="stylesheet" href="assets/css/navbar-icon-top.css"  type="text/css" media="screen">

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script type="text/javascript" src="assets/js/BpTspSolver.js"></script>
<script type="text/javascript" src="assets/js/directions-export.js"></script>
<script type="text/javascript" src="assets/js/tspaecb.js"></script>
<script type="text/javascript">

  $(document).ready(function() {
    $("#bulkButton").click(function () {
      $("#modalTitle").html("<i class='fa fa-list'></i> Inserción múltiple");
      $("#modalBody").html("<textarea id='inputList' class='form-control' rows='10' cols='70' placeholder='Ingrese una dirección por línea'></textarea>")
      $("#modalButton").html("Agregar");
      $("#modalButton").click(clickedAddList);
      $("#modal").modal('show');
    });
  });

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
  jQuery('.myMap').height(jQuery(window).height() - 100);
});
</script>
</head>

<body>
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><img src="assets/images/logo.png" width="50" height="50"> Route Planner</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a id="calculateButton" class="nav-link" href="#"><i class="fa fa-calculator-alt"></i>Calcular</a>
      </li>
      <li class="nav-item">
        <a onClick='startOver()' class="nav-link" href="#"><i class="fa fa-trash"></i>Borrar</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i>Exportar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a id='exportButton' class="dropdown-item" href="#">Descargar</a>
          <div class="dropdown-divider"></div>
          <a onClick='window.print()' class="dropdown-item" href="#">Imprimir</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0 mr-auto">
      <div class="input-group">
        <input id="addressStr" type="text" class="form-control" placeholder="Inserte una dirección"/>
        <div class="input-group-append">
          <button class="btn btn-outline-info my-2 my-sm-0" type="button" onClick="clickedAddAddress()"><i class="fa fa-plus"></i></button>
        </div>
      </div>
    </form>
    <ul class="navbar-nav ">
      <li class="nav-item">
        <a id='bulkButton' class="nav-link" href="#"><i class="fa fa-list"></i>Inserción múltiple</a>
      </li>
      <li class="nav-item">
        <a id='helpButton' class="nav-link" href="#"><i class="fa fa-question-circle"></i>Ayuda</a>
      </li>
    </ul>
  </div>
</nav>

<div id="map" class="myMap"></div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalBody">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="modalButton"></button>
      </div>
    </div>
  </div>
</div>

<div class='container-fluid'>

<div class='row'>
  <div class='col-md-12 col-print-12'>
    <div id="path" class="pathdata"></div>
    <div id="my_textual_div"></div>
  </div>
</div>

<!-- Hidden stuff -->
<div id="dialogBulk" title='Volcar direcciones'>
  <form name="listOfLocations" onSubmit="clickedAddList(); return false;">
    <br>
    <input type="button" value="Add list of locations" >
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

<script src="assets/jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>

</body>
</html>