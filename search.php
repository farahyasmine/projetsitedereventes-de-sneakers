<?php
include "connect.php";

// Vérification de la connexion
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Traitement de la recherche
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Exécution de la requête avec la valeur de recherche
    $sql = "SELECT * FROM annonce WHERE annonce LIKE '%$searchTerm%' OR marque LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $sql);
    $queryResult = mysqli_num_rows($result);

    // Affichage des résultats de la recherche
    if ($queryResult > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='result'>
                  <h3>".$row['annonce']."</h3>
                  <p>".$row['marque']."</p>
                  <img src='".$row['photo']."' alt=''>
                  </div>";
        }
    } else {
        echo "Aucun résultat trouvé.";
    }
}

// Fermeture de la connexion
mysqli_close($conn);
?>
