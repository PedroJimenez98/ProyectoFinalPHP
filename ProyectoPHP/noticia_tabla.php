<?php 
include "conexion.php";

	$noticias = $connection->query("SELECT * FROM noticias;") ;
 ?>
	<table id="tabladatos">
			<tr>
				<th><h2>Título</h2></th>
				<th><h2>Texto</h2></th>
				<th><h2>Imagen</h2></th>
				<th colspan="2"><h2>Acciones</h2></th>
			</tr>
		<?php while ($noticia = $noticias->fetch_object()):  ?>
			<tr id="noticia_<?=$noticia->idnoticia?>" data-idnoticia="<?=$noticia->idnoticia?>">
				<td class="titulo"><?= $noticia->titulo ?></td>
				<td class="texto"><?= $noticia->texto ?></td>
				<td style="width: 10%;"><img alt="" class="avatar width-full rounded-2" height="75" src="avatar.jpg" width="75"></td>
				<td><input type="button" class="modificar btn btn-success" value="Modificar"/></td><td><input type="button" class="borrar btn btn-danger" value="Eliminar"/></td>
			</tr>
	    <?php endwhile; ?>

	</table>
