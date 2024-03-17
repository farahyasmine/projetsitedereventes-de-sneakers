<?php
session_start();
include "connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
    <link rel="stylesheet" href="page2.css">
</head>
<body>
 <form action="" method="post">

 <nav class="navMenu">
  <a href="page2.php">Accueil</a>
  <a href="#">Blog</a>
  <a href="#">Work</a>
  <a href="inscription.php">compte</a>
  <a href="page1.html">&larr; Retour</a>
  <div class="dot"></div>
</nav>

    <div class="searchBox">
    <input id="searchInput" class="searchInput" type="text" name="search" placeholder="Search">
    <button class="searchButton" onclick="executeSearch()">
        <i class="material-icons">
            search
        </i>
    </button>
</div>

<script>
    function executeSearch() {
        var searchTerm = document.getElementById("searchInput").value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                // Mettez à jour la page avec les résultats de la recherche si nécessaire
            }
        };
        xhr.open("GET", "search.php?search=" + searchTerm, true);
        xhr.send();
    }
    
    function filterResults() {
        document.getElementById("filterForm").submit();
    }
    
    // Ajoutez cette fonction pour intercepter l'événement de soumission du formulaire de recherche
    document.getElementById("searchForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement
        executeSearch(); // Appelle la fonction d'exécution de la recherche
    });
</script>

<div class="filterBox">
    <form id="filterForm" method="POST" action="">
        <label for="sort">Trier par prix:</label>
        <select id="sort" name="sort">
            <option value="asc">Trier par ordre croissant</option>
            <option value="desc">Trier par ordre décroissant</option>
        </select>

     
    

        <label for="pointure">Pointure:</label>
        <select id="pointure" name="pointure">
            <option value="">-Selectionner une pointure-</option>
            <?php
            $query = "SELECT DISTINCT pointure FROM annonce";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['pointure'] . "'>" . $row['pointure'] . "</option>";
            }
            ?>
        </select>

        <label for="marque">Marque:</label>
        <select id="marque" name="marque">
            <option value="">-Selectionner une marque-</option>
            <?php
            $query = "SELECT DISTINCT marque FROM annonce";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['marque'] . "'>" . $row['marque'] . "</option>";
            }
            ?>
        </select>

        <label for="ida">ID:</label>
        <select id="ida" name="ida">
            <option value="">-Selectionner un ID-</option>
            <?php
            $query = "SELECT DISTINCT ida FROM annonce";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['ida'] . "'>" . $row['ida'] . "</option>";
            }
            ?>
        </select>

        <button type="submit" name="filtrer">Filtrer</button>
    </form>
</div>





 </form>

<?php
$req= "S"
?>

<?php


// Vérifier si le formulaire a été soumis avec le bouton "Filtrer"
$filtrer = isset($_POST['filtrer']);
$sortOption = isset($_POST['sort']) ? $_POST['sort'] : 'asc';
$sortOrder = $sortOption === 'desc' ? 'DESC' : 'ASC';
// Affichage de toutes les annonces ou annonces filtrées
if (!$filtrer) {
    // Affichage de toutes les annonces
    $query = "SELECT * FROM annonce";
} else {
    // Récupérer les valeurs des champs de filtre
    $prix = $_POST['prix'];
    $annonce = $_POST['produit'];
    $pointure = $_POST['pointure'];
    $marque = $_POST['marque'];
    $ida = $_POST['ida'];

    // Construire la requête SQL de filtre
    $query = "SELECT * FROM annonce WHERE 1=1";

    if (!empty($prix)) {
        $query .= " AND prix <= '$prix'";
    }

    if (!empty($annonce)) {
        $query .= " AND annonce = '$annonce'";
    }

    if (!empty($pointure)) {
        $query .= " AND pointure = '$pointure'";
    }

    if (!empty($marque)) {
        $query .= " AND marque = '$marque'";
    }

    if (!empty($ida)) {
        $query .= " AND ida = '$ida'";
    }
     // Ajouter l'ordre de tri à la requête
     $query .= " ORDER BY prix $sortOrder";
}

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  echo '<div class="page-content">';
  while ($row = mysqli_fetch_assoc($result)) {
      echo '<div class="card" data-id="' . $row['ida'] . '">';
      echo "<h2 class='title'>Annonce #" . $row['ida'] . "</h2>";
      // Afficher uniquement l'image avec un identifiant unique
      echo '<img id="image-' . $row['ida'] . '" src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Photo" />';
      echo 'prix ($) : ' .$row['prix'];
      echo "<div class='details' style='display:none;' id='details-" . $row['ida'] . "'>";
      // Afficher les autres détails de chaque annonce
     
      // Ajouter les boutons pour acheter ou contacter le vendeur
      echo "<button>Acheter</button>";
      echo '<button><a class="white-link" href="page3.php?ida=' . $row['ida'] . '">Voir les détails</a></button>';
      echo '<button><a class="white-link" href="favoris.php?ida=' . $row['ida'] . '">Ajouter aux favoris</a></button>';
      

      echo "</div>";
      echo "</div>";
  }
  echo '</div>';
} else {
  echo "Aucune annonce correspondante.";
}
?>

<script>
// Fonction pour afficher/masquer les détails de l'annonce
function toggleDetails(id) {
  var details = document.getElementById('details-' + id);
  var image = document.getElementById('image-' + id);
  if (details.style.display === 'none') {
      details.style.display = 'block';
      image.style.opacity = '0.8'; // Réduire l'opacité de l'image lorsque les détails sont affichés
  } else {
      details.style.display = 'none';
      image.style.opacity = '1'; // Rétablir l'opacité normale de l'image lorsque les détails sont masqués
  }
}

// Ajouter un gestionnaire d'événement pour chaque carte
var cards = document.getElementsByClassName('card');
for (var i = 0; i < cards.length; i++) {
  var card = cards[i];
  card.addEventListener('click', function() {
      var cardId = this.getAttribute('data-id');
      toggleDetails(cardId);
  });
}
</script>
</body>
</html>






