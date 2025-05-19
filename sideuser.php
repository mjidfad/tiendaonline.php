<div class="mt-2 ms-1">
  <h5 id="h5" style="cursor: pointer;">Categorias 
    <img id="im1" src="../imagnes2/icon1.png" style='cursor: pointer; transition: transform 0.3s ease; gap: 15px;' alt="" height="20px">
  </h5>

  <?php
  include 'db2.php';
  $sql_padre = "SELECT * FROM categoria_padre";
  $result_padre = $pdo->query($sql_padre);

  if ($result_padre->num_rows > 0) {
      echo "<ul id='ul_principal' style='list-style: none; display: none;'>"; 

      while($row_padre = $result_padre->fetch_assoc()) {
          $padre_id = $row_padre["id"];
          $padre_name = $row_padre["name"];

          echo "<li>";

          echo "<div  class='categoria-padre' style='display: flex; align-items: center; gap: 15px;'><a href='buscarpadreindex.php?name1=$padre_name'>$padre_name   </a>
       <img  onclick='toggleHijos($padre_id);' id='imgpadre_$padre_id' height='15px' src='../imagnes2/icon1.png'   style='cursor: pointer; transition: transform 0.3s ease;'></div>";

          $sql_hijas = "SELECT * FROM categoria_hijos WHERE category_id = $padre_id";
          $result_hijas = $pdo->query($sql_hijas);

          if ($result_hijas->num_rows > 0) {
              echo "<ul id='hijos_$padre_id' style='display: none; margin-left: 20px;'>";

              while($row_hija = $result_hijas->fetch_assoc()) {
                  $hija_name = $row_hija["name"];
                  echo "<li><a href='buscarhijoindex.php?name2=$hija_name&name1=$padre_name'>$hija_name</a></li>";
              }

              echo "</ul>";
          }

          echo "</li>";
      }

      echo "</ul>";
  } else {
      echo "No se encontraron categorías padre.";
  }
  ?>
</div>

<!-- Mostrar u ocultar categorías hijas -->
<script>

function toggleHijos(padreId) {
    var ul = document.getElementById("hijos_" + padreId);
    var im = document.getElementById("imgpadre_" + padreId);

    if (ul.style.display === "none") {
        ul.style.display = "block";
        im.classList.add("down");
    } else {
        ul.style.display = "none";
        im.classList.remove("down");
    }
}
</script>

<!-- Mostrar u ocultar la lista principal cuando se pulsa h5 -->
<script>
document.getElementById("h5").addEventListener("click", function() {
    var ulPrincipal = document.getElementById("ul_principal");
    var im = document.getElementById("im1");

    if (ulPrincipal.style.display === "none") {
        ulPrincipal.style.display = "block";
        im.classList.add("down");
    } else {
        ulPrincipal.style.display = "none";
        im.classList.remove("down");
    }
});
</script>


<style>
  #h5,
  #bmujeres,
  #bhombres {
    cursor: pointer;
  }
.categoria-padre:hover img {
 box-shadow: 0px 0px 10px red;
  
}
  .down {

    transform: rotate(90deg) scale(1.3);
    transition: 0.5s ease-in-out;
    
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
 