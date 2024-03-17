<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>

<link rel="stylesheet" href="page3.css">
<body>
    




<nav class="navMenu">
      <a href="page2.php">Home</a>
      <a href="#">Blog</a>
      <a href="#">Work</a>
      <a href="inscription.php"> Compte</a>
      <div class="dot"></div>
    </nav>


<div class="page-content">
    <div class="image-container">
        <?php
        // Récupérer l'identifiant de l'annonce depuis l'URL
        $ida = isset($_GET['ida']) ? $_GET['ida'] : '';

        // Vérifier si l'identifiant de l'annonce est valide
        if (!empty($ida)) {
            // Inclure le fichier de connexion à la base de données
            include "connect.php";

            // Construire la requête SQL pour récupérer l'image de l'annonce
            $query = "SELECT photo FROM annonce WHERE ida = '$ida'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Afficher l'image de l'annonce
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Photo" />';
            } else {
                echo "Aucune image d'annonce trouvée.";
            }

            // Fermer la connexion à la base de données
            mysqli_close($conn);
        } else {
            echo "Identifiant d'annonce invalide.";
        }
        ?>
    </div>

    <div class="details-container">
        <?php
        // Récupérer l'identifiant de l'annonce depuis l'URL
        $ida = isset($_GET['ida']) ? $_GET['ida'] : '';

        // Vérifier si l'identifiant de l'annonce est valide
        if (!empty($ida)) {
            // Inclure le fichier de connexion à la base de données
            include "connect.php";

            // Construire la requête SQL pour récupérer les détails de l'annonce
            $query = "SELECT * FROM annonce WHERE ida = '$ida'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                // Afficher les détails de l'annonce
                $row = mysqli_fetch_assoc($result);
                echo "<h2>Details de l'annonce #" . $row['ida'] . "</h2>";
                echo "<p>Annonce : " . $row['annonce'] . "</p>";
                echo "<p>Pointure : " . $row['pointure'] . "</p>";
                echo "<p>Marque : " . $row['marque'] . "</p>";
                echo "<p>Prix : " . $row['prix'] . "</p>";

                // Ajouter les boutons pour acheter ou contacter le vendeur
                echo '<div class="button-container">';
                echo '<button>Acheter</button>';
                echo '<button><a class="white-link" href="messagerie.php?ida=' . $row['ida'] . '">Contacter le vendeur</a></button>';
                echo '<button><a class="white-link" href="favoris.php?ida=' . $row['ida'] . '"><span class="heart">&#10084;</span> Ajouter aux favoris</a></button>';

                echo '</div>';
            } else {
                echo "Aucun détail d'annonce trouvé.";
            }

            // Fermer la connexion à la base de données
            mysqli_close($conn);
        } else {
            echo "Identifiant d'annonce invalide.";
        }
        ?>
    </div>
</div>

<script>
  // Sélectionner tous les liens avec la classe 'white-link' contenant un cœur
var links = document.querySelectorAll('.white-link .heart');

// Parcourir tous les liens et ajouter un gestionnaire d'événement pour chaque clic
links.forEach(function(heart) {
  heart.addEventListener('click', function(e) {
    e.preventDefault();
    heart.classList.toggle('clicked');
  });
});


</script>
