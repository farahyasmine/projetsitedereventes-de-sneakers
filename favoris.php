<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
session_start();
include "connect.php";

if (!isset($_SESSION["mail"])) {
  header("location: compte.php");
  exit();
}

// Vérifier la connexion
if (!$conn) {
  die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

if (isset($_GET['ida'])) {
    $ida = $_GET['ida'];
    $mail = $_SESSION['mail'];

    // Vérifier si l'annonce existe déjà dans les favoris de l'utilisateur
    $checkQuery = "SELECT * FROM favoris WHERE mail = '$mail' AND ida = '$ida'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        // L'annonce est déjà dans les favoris de l'utilisateur
        echo "Cette annonce est déjà dans vos favoris.";
    } else {
        // Ajouter l'annonce aux favoris de l'utilisateur
        $insertQuery = "INSERT INTO favoris (mail, ida) VALUES ('$mail', '$ida')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo "Annonce ajoutée aux favoris avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'annonce aux favoris : " . mysqli_error($conn);
        }
    }
}
