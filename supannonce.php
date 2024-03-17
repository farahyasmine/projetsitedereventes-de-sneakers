
<?php
include "connect.php";

if (isset($_GET['ida'])) {
    $ida = $_GET['ida'];
    
    if (!$conn) {
        echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
    } else {
        $req = "DELETE from annonce where ida= $ida";
        $res = mysqli_query($conn, $req);

        if ($res) {
            echo "Annonce supprimée avec succès !";
        } else {
            echo "Une erreur est survenue lors de la suppression de l'annonce.";
        }
    }
} else {
    echo "Erreur : l'identifiant de l'annonce n'a pas été transmis.";
}
?>
