<?php
include "connect.php";
session_start();
if (!isset($_SESSION["mail"])) {
  header("location: compte.php");
  exit();
}

// Établir une connexion à la base de données (adapté à votre configuration)
include "connect.php";

// Vérifier la connexion
if (!$conn) {
  die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Récupérer les données du formulaire

  $mail = $_SESSION["mail"];
  $message = $_POST['message'];
  $ida = $_GET['ida'];

  // Vérifier si $ida est un entier valide
  if (!is_numeric($ida)) {
    echo "La valeur de ida n'est pas valide.";
    // Autres actions à prendre en cas d'erreur
    mysqli_close($conn);
    exit(); // Arrêter l'exécution du script
  }

  // Requête pour récupérer l'idc correspondant au mail
  $sql = "SELECT idc FROM compte WHERE mail = '$mail'";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $idc = $row['idc'];

    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO messagerie (idm, message, date, idc, ida) VALUES (NULL, '$message', NOW(), '$idc', '$ida')";
    if (mysqli_query($conn, $sql)) {
      echo "Message envoyé avec succès.";
      // Vous pouvez rediriger l'utilisateur vers une page de confirmation ou effectuer d'autres actions nécessaires.
    } else {
      echo "Erreur lors de l'envoi du message: " . mysqli_error($conn);
    }
  } else {
    echo "Aucun résultat trouvé pour l'e-mail: $mail";
  }

  // Fermer le résultat de la requête
  mysqli_free_result($result);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>


</body>
</html>
