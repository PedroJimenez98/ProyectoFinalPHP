<?php
include "conexion.php";
	session_start() ;

	function destruir_session() {

		// Destruir variables de sesión
		$_SESSION[] = array() ;

		// Destruimos la sesión
		session_destroy() ;

		header("location:index.php") ;
	}

	//Comprobamos si existe una sesión previa
	if (!isset($_SESSION["id"])) { 
		header("location:index.php") ;
	}

	if ($_SESSION["admin"] != 1) {
		header("location:principal.php") ;
	}

	// Si se nos indica, destruimos la sesión.
	if (isset($_GET["destroy"])) {
		destruir_session() ; 
	}


	$noticias = $connection->query("SELECT * FROM noticias;") ;

	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Inicio</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
	<style type="text/css">

		h4 {
			width: 200px;
		}
		table{
			border-collapse: collapse;
			margin: 1em;
		}
		table, tr, th, td {
			border: 1px solid black;
			text-align: center;
		}
		.titulo {
			font-size: 20px;
		}
		.btn, .btn-info {
			margin: 2px;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {			
			var idtipo;
			var idnoticia;


//---------------------------------------------------
//DIALOGO DE BORRADO
	$( "#dialogoborrar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		//BOTON DE BORRAR
		"Eliminar": function() {			
			//Ajax con get
			$.get("noticia_borrar.php", {"idnoticia":idnoticia},function(){				
				$("#noticia_" + idnoticia).fadeOut(500,function(){$(this).remove();});
			})//get
			//Cerrar la ventana de dialogo				
			$(this).dialog("close");												
		},
		"Cancelar": function() {
				//Cerrar la ventana de dialogo
				$(this).dialog("close");
		}
		}//buttons

	});	

//Evento click que pulsa el boton borrar
$(document).on("click",".borrar",function(){
	//Obtenemos el idnoticia a eliminar
	//a traves del atributo idrecord del tr
	idnoticia = $(this).parents("tr").data("idnoticia");
	//Accion para mostrar el dialogo de borrar
	 $( "#dialogoborrar" ).dialog("open");		
});



//---------------------------------------------------
//MODIFICAR
$( "#dialogomodificar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Guardar": function() {			
			$.post("noticia_modificar.php", {
				idnoticiamodificar : idnoticia,
				titulomodificar : $("#titulomodificar").val(),
				textomodificar: $("#textomodificar").val(),
			},function(data,status){
				$("#noticia_" + idnoticia).html(data);
				
			})//get
					
			$(this).dialog( "close" );										
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});				

//Boton Modificar	
$(document).on("click",".modificar",function(){
	//Obtenemos el idnoticia de la fila
	idnoticia = $(this).parents("tr").data("idnoticia");
	//Para que ponga el campo modelo con su valor
	$("#titulomodificar").val($(this).parent().siblings("td.titulo").html());
	
	//Para que ponga el campo modelo con su valor
	$("#textomodificar").val($(this).parent().siblings("td.texto").html());

	
	//Muestro el dialogo
	$( "#dialogomodificar").dialog("open");
	
});

//---- NUEVO --------------
//Boton de nuevo inmueble 
//Crea nueva fila al final de la tabla
//Con dos nuevos botones (guardarnuevo y cancelarnuevo)
$(document).on("click","#nuevo",function(){	
	$.post("noticia_formulario_nuevo.php",function(data){
	//Añade a la tabla de datos una nueva fila
	$("#tabladatos").append(data);
			//Ocultamos boton de nuevo inmueble
			//Para evitar añadir mas de uno 
			//a la vez
			$("#nuevo").hide();			
	})//get	
});

//Boton de cancelar nuevo
$(document).on("click","#cancelarnuevo",function(){	
		//Elimina la nueva fila creada
		$("#filanueva").remove();
		//vuelve a mostrar el botón de nuevo
		$("#nuevo").show();
		
});			

//Boton de guardar nuevo
$(document).on("click","#guardarnuevo",function(){
	$.post("noticia_insertar_nuevo.php", {
				"titulonuevo":$("#titulonuevo").val(),
				"textonuevo":$("#textonuevo").val()
			},function(data){
				window.location.href = "administracion.php";
			})//post	
});


		});
	</script>
</head>
<body>
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="principal.php">Noticias</a>
	    </div>
	    <ul class="nav navbar-nav navbar-left">
	    	<li class="nav-item">
	        	<a class="nav-link" href="index.php">Mi perfil <span class="sr-only">(current)</span></a>
	      	</li>
	      	<?php 
	      		if($_SESSION["admin"]) {
	      			echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"administracion.php\">Admin<span class=\"sr-only\">(current)</span></a></li>";
	      		}
	      	 ?>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
	    	
	    	<li><a href="profile.php?destroy"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
	    </ul>
	  </div>
	</nav>
	<input id="nuevo" type="button" value="Nuevo" class="btn-info btn-sm" />

	<?php include "noticia_tabla.php" ?>
	<div class="text-center">
		<input id="nuevo" type="button" value="Nuevo" class="btn-info btn-lg" />
	</div>

	<!-- CAPA DE DIALOGO ELIMINAR noticia -->
	<div id="dialogoborrar" title="Eliminar noticia">
	  <p>¿Esta seguro que desea eliminar la noticia?</p>
	</div>

	<!-- CAPA DE DIALOGO MODIFICAR vehiculo -->
	<div id="dialogomodificar" title="Modificar vehiculo">
		<?php include "formulario_modificar_noticia.php" ?>
	</div>
</body>
</html>