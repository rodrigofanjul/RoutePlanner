
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="description" content="Planificador de viajes con múltiples destinos. Hasta 50 paradas.">
<title>Route Planner | Google Maps</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="assets/jquery-ui-1.12.0.custom/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/style.css"  type="text/css" media="screen">
<link rel="stylesheet" href="assets/css/print.css"  type="text/css" media="print">
<link rel="stylesheet" href="assets/css/navbar-icon-top.css"  type="text/css" media="screen">

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script type="text/javascript" src="assets/js/BpTspSolver.js"></script>
<script type="text/javascript" src="assets/js/directions-export.js"></script>
<script type="text/javascript" src="assets/js/tspaecb.js"></script>
<script type="text/javascript">

  let inputList = "";

  $(document).ready(function() {

    // Calcular
    $("#calculateButton").click(function () {
      $("#modalTitle").html("<i class='fa fa-cogs'></i> Opciones del camino");
      $.get('includes/calculate.php', function (data) {
        $('#modalBody').html(data);
      });
      $("#modalButton").html("Calcular");
      $("#modalButton").click(clickedSolve);
      $("#modalButton").attr('enable',true);
      $("#modalButton").show();
      $("#modal").modal('show');
    });

    // Exportar
    $("#exportButton").click(function () {
      $("#modalTitle").html("<i class='fa fa-download'></i> Descargar");
      $('#modalBody').html($("#dialogExport").html());
      $("#modalButton").hide();
      $("#modal").modal('show');
    });
    
    // Inserción múltiple
    $("#bulkButton").click(function () {
      $("#modalTitle").html("<i class='fa fa-list'></i> Inserción múltiple");
      $.get('includes/insert.php', function (data) {
        $('#modalBody').html(data);
      });
      if(inputList.length > 0) $("#inputList").val(inputList);
      $("#modalButton").html("Agregar");
      $("#modalButton").click(clickedAddList);
      $("#modalButton").attr('enable',true);
      $("#modalButton").show();
      $("#modal").modal('show');
    });

    // Ayuda
    $("#helpButton").click(function () {
      $("#modalTitle").html("<i class='fa fa-question-circle'></i> Ayuda");
      $.get('includes/help.php', function (data) {
        $('#modalBody').html(data);
      });
      if(inputList.length > 0) $("#inputList").val(inputList);
      $("#modalButton").hide();
      $("#modal").modal('show');
    });

    $(".myMap").height($(window).height() - 100);
    
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
  jQuery('#setLabelCancel').click(function() {
    jQuery('#dialogSetLabel').dialog("close");
  });
  jQuery('#editButton').click(function() {
    jQuery('#dialogEdit').dialog('open');
  });
});
</script>
</head>

<body>
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="."><img src="assets/images/logo.png" width="50" height="50"> Route Planner</a>
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
          <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" onClick="clickedAddAddress()"><i class="fa fa-plus"></i></button>
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
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modalButton"></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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

  <div id="exportData_hidden" class='hidden'></div>
  <div id="exportLabelData_hidden" class='hidden'></div>
  <div id="exportAddrData_hidden" class='hidden'></div>
  <div id="exportOrderData_hidden" class='hidden'></div>
  <div id="durationsData_hidden" class='hidden'></div>

  <div id="dialogSetLabel" title="Set name">
    <p>Enter name for location: <input id='setLabelInput' type='text' style="width: 100%;" value=""/></p>
    <input id='setLabelCancel' type='button' value='Cancel'/>
    <input id='setLabelOk' type='button' value='Ok'/>
  </div>

  <div id='dialogEdit' title='Edit Route'>
    <div id="routeDrag"></div>
    <div id="reverseRoute"></div>
  </div>

  <?php include 'includes/export.php' ?>

</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?v=weekly&key=AIzaSyCRgK3LhRQrlxsm1xrPNwdtW-akcbhps08&callback=onBodyLoad"></script>

<script src="assets/jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>

</body>
</html>