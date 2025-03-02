<div class="mt-2 ms-1">
  <h5 id="h5" class="">Categorias <img id="im" src="../imagnes2/icon1.png" alt="" height="20px"></h5>
  

<?php

$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Consulta para obtener las categorías padre
$sql_padre = "SELECT * FROM categoria_padre";
$result_padre = $conn->query($sql_padre);

if ($result_padre->num_rows > 0) {
    // Iniciar la lista desordenada
    echo "<ul style='display:none;' id='ul' >";

    // Recorrer cada categoría padre
    while($row_padre = $result_padre->fetch_assoc()) {
      echo "<li> <a href='buscarpadreindex.php?name=". $row_padre["name"] ."'>". $row_padre["name"] ."</a></li>";

        // Consulta para obtener las categorías hijas de esta categoría padre
        $sql_hijas = "SELECT * FROM categoria_hijos WHERE category_id = " . $row_padre["id"];
        $result_hijas = $conn->query($sql_hijas);

        if ($result_hijas->num_rows > 0) {
            // Iniciar una sublista para las categorías hijas
            echo "<ul>";

            // Recorrer cada categoría hija
            while($row_hija = $result_hijas->fetch_assoc()) {
              
                 echo "<li> <a href='buscarhijoindex.php?name=". $row_hija["name"] ."'>". $row_hija["name"] ."</a></li>";
            }

            // Cerrar la sublista
            echo "</ul>";
        }
    }

    // Cerrar la lista desordenada
    echo "</ul>";
} else {
    echo "No se encontraron categorías padre.";
}
?>
</div>

<script>
  let h5 = document.getElementById("h5");
  let h6 = document.getElementById("h6");
  let bmujeres = document.getElementById("bmujeres");
  let bhombres = document.getElementById("bhombres");
  let im = document.getElementById("im");
  let im2 = document.getElementById("im2");
  let im3 = document.getElementById("im3");
  let ul = document.getElementById("ul");
  let ulmujeres = document.getElementById("ulmujeres");
  let ulhombres = document.getElementById("ulhombres");


  // Agrega el evento de clic al botón
  h5.addEventListener('click', ok);
  bmujeres.addEventListener("click", ok2);
  bhombres.addEventListener("click", ok3);


  function ok() {
    if (ul.style.display === "block") {
      ul.style = "display:none;";
      im.classList.remove("down");


    } else {
      ul.style = "display:block;";
      im.classList.add("down");
    }
  }
</script>



<style>
  #h5,
  #bmujeres,
  #bhombres {
    cursor: pointer;
  }

  .down {

    transform: rotate(90deg);
    /*transition: 0.5s ease-in-out;*/
  }

  li {
    list-style: none;
    margin: 3px;
  }

  a {
    text-decoration: none;
    color: black;
    display: block;
  }

  a:hover {
    color: black;


  }
</style>