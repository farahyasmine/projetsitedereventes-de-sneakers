<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $annonce = $_POST["annonce"];
    $pointure = $_POST["pointure"];
    $marque = $_POST["marque"];
    $prix = $_POST["prix"];

    // Vérifie si un fichier a été uploadé
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $nomFichier = $_FILES['photo']['name'];
        $cheminFichierTemporaire = $_FILES['photo']['tmp_name'];

        // Lecture du contenu de l'image
        $contenuImage = file_get_contents($cheminFichierTemporaire);
        
        // Échapper les caractères spéciaux dans le contenu de l'image
        $contenuImage = mysqli_real_escape_string($conn, $contenuImage);

        // Enregistrement du nom du fichier et le contenu de l'image dans la base de données
        $requet = "INSERT INTO `annonce` (`ida`, `vendeur`, `annonce`, `pointure`, `marque`, `prix`, `photo_name`, `photo`) VALUES (NULL, '".$_SESSION["mail"]."','$annonce','$pointure', '$marque', '$prix', '$nomFichier', '$contenuImage');";
        $res = mysqli_query($conn, $requet);

        if($res){
            echo "Annonce ajoutée avec succès !";
            echo "Nom du fichier de la photo : " . $nomFichier;
        } else {
            echo "Une erreur est survenue lors de l'ajout de l'annonce : " . mysqli_error($conn);
        }
    } else {
        echo "Aucun fichier photo n'a été téléchargé.";
    }
}
?>
