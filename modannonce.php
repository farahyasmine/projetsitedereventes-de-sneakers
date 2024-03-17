<?php
include "connect.php";

if (isset($_GET['ida'])) {
    $ida = $_GET['ida'];
    
    if (!$conn) {
        echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
    } else {
        $req = "SELECT * FROM annonce WHERE ida = $ida";
        $res = mysqli_query($conn, $req);

        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $annonce = $row['annonce'];
            $pointure = $row['pointure'];
            $marque = $row['marque'];
            $prix = $row['prix'];
            $photo = $row['photo'];
        } else {
            echo "Aucune annonce trouvée avec cet identifiant.";
            exit();
        }
    }
} else {
    echo "Erreur : l'identifiant de l'annonce n'a pas été transmis.";
    exit();
}


if (isset($_POST['submit'])) {
    $ida = $_POST['ida'];
    $annonce = $_POST['annonce'];
    $pointure = $_POST['pointure'];
    $marque = $_POST['marque'];
    $prix = $_POST['prix'];
    $photo = $_FILES['photo'];

    if (!$conn) {
        echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
    } else {
        // Vérifier si un nouveau fichier a été téléchargé
        if ($photo['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $photo['tmp_name'];
            $photo_content = file_get_contents($tmp_name);

            // Mettre à jour la photo dans la base de données
            $req = "UPDATE annonce SET annonce = '$annonce', pointure = '$pointure', marque = '$marque', prix = '$prix', photo = ? WHERE ida = $ida";
            $stmt = mysqli_prepare($conn, $req);
            mysqli_stmt_bind_param($stmt, "b", $photo_content);
            mysqli_stmt_send_long_data($stmt, 0, $photo_content);
            $res = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($res) {
                echo "Annonce modifiée avec succès !";
                echo "<a href='compte.php'> Retourner à mon compte </a>";
            } else {
                echo "Une erreur est survenue lors de la modification de l'annonce.";
            }
        } else {
            // Si aucun nouveau fichier n'a été téléchargé, mettre à jour les autres champs sans modifier la photo
            $req = "UPDATE annonce SET annonce = '$annonce', pointure = '$pointure', marque = '$marque', prix = '$prix' WHERE ida = $ida";
            $res = mysqli_query($conn, $req);

            if ($res) {
                echo "Annonce modifiée avec succès !";
                echo "<a href='compte.php'> Retourner à mon compte </a>";
            } else {
                echo "Une erreur est survenue lors de la modification de l'annonce.";
            }
        }
    }
} else {
    echo "Erreur : le formulaire de modification n'a pas été soumis.";
}
?>

<link rel="stylesheet" href="modif.css">

<body>
<form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="ida" value="<?php echo $ida; ?>">
    <label>Annonce :</label>
    <input type="text" name="annonce" value="<?php echo $annonce; ?>"><br>
    <label>Pointure :</label>
    <input type="text" name="pointure" value="<?php echo $pointure; ?>"><br>
    <label>Marque :</label>
    <input type="text" name="marque" value="<?php echo $marque; ?>"><br>
    <label>Prix :</label>
    <input type="text" name="prix" value="<?php echo $prix; ?>"><br>
    <label>Photo :</label>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($photo); ?>" alt="Photo" style="width: 300px;"><br>
    <input type="file" name="photo"><br>
    <input type="submit" name="submit" value="Enregistrer">
</form>
</body>
