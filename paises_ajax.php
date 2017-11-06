<?php
/*-----------------------
Autor: Obed Alvarado
http://www.obedalvarado.pw
Fecha: 12-06-2015
Version de PHP: 5.6.3
----------------------------*/

  # conectare la base de datos
  $con=@mysqli_connect('localhost', 'root', '', 'test');
  if(!$con){
    die("imposible conectarse: ".mysqli_error($con));
  }
  if (@mysqli_connect_errno()) {
    die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
  }

  $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL) ? $_REQUEST['action'] : '';

  if($action == 'ajax'):
    include 'pagination.php'; // Incluir el archivo de paginación

    // Las variables de paginación
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 10; // La cantidad de registros que desea mostrar
    $adjacents = 4; // Brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;
    // Cuenta el número total de filas de la tabla*/
    $count_query = mysqli_query($con,"SELECT count(*) AS numrows FROM countries");
    if ($row= mysqli_fetch_array($count_query)){
      $numrows = $row['numrows'];
    }
    $total_pages = ceil($numrows/$per_page);
    // consulta principal para recuperar los datos
    $query = mysqli_query($con,"SELECT * FROM countries order by countryName LIMIT $offset,$per_page");

    if ($numrows>0):

?>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Moneda</th>
          <th>Capital</th>
          <th>Continente</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = mysqli_fetch_array($query)): ?>
        <tr>
          <td><?=$row['countryCode']?></td>
          <td><?=$row['countryName']?></td>
          <td><?=$row['currencyCode']?></td>
          <td><?=$row['capital']?></td>
          <td><?=$row['continentName']?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <div class="table-pagination pull-right">
      <?=paginate($page, $total_pages, $adjacents)?>
    </div>

      <?php else: ?>
      <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>Aviso!!!</h4> No hay datos para mostrar
      </div>
      <?php
    endif;
  endif;
