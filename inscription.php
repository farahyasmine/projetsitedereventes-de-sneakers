<?php
session_start();

include "connect.php";

if (isset($_POST["login"])) {
    $mail = $_POST["mail"];
    $mdp = $_POST["mdp"];
    $req = "SELECT * FROM compte WHERE mail = '$mail' AND mdp = '$mdp'";
    //$req = "SELECT * FROM user WHERE mail = '$mail' AND mdp = '' or '1'"; injection sql
    $res = mysqli_query($conn, $req);
    $ligne = mysqli_fetch_assoc($res);
    if (mysqli_num_rows($res) > 0) {
        $_SESSION["mail"] = $mail;
        $_SESSION["mdp"] = $ligne["mdp"];
       
        header("location: compte.php");
    } else {
        echo "Erreur de mail et/ou de mot de passe";
    }
} elseif (isset($_POST["signup"])) {
    $nom = $_POST["nom"];
    $mail = $_POST["mail"];
    $mdp = $_POST["mdp"];
    $confirm_mdp = $_POST["confirm-password"];

    if ($mdp != $confirm_mdp) {
        echo "Les mots de passe ne correspondent pas";
    } else {
        // Utiliser la fonction password_hash() pour hacher le mot de passe
        $hashed_mdp = password_hash($mdp, PASSWORD_DEFAULT);
        $hashed_mdp = password_hash($mdp, PASSWORD_BCRYPT);

        // Vérifier si l'email existe déjà dans la base de données
        $requ = "SELECT * FROM compte WHERE mail='$mail'";
        $res = mysqli_query($conn, $requ);
        if (mysqli_num_rows($res) > 0) {
            echo "L'email existe déjà";
        } else {
            // Insérer les données dans la base de données
            $requete = "INSERT INTO compte (nom, mail, mdp) VALUES ('$nom', '$mail', '$hashed_mdp')";
            $res = mysqli_query($conn, $requete);
            if ($res) {
                $_SESSION["mail"] = $mail;
                $_SESSION["mdp"] = $hashed_mdp;
                header("location: compte.php");
            } else {
                echo "Une erreur est survenue lors de l'inscription";
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
<title>Se connecter</title>
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
 <link rel="stylesheet" href="inscription.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    
        </style>
</head>
<body>
    <div class="background">
      <div class="shape"></div>
      <div class="shape"></div>
    </div>

    <?php if (!isset($_POST['signup'])) { // Si le formulaire d'inscription n'a pas encore été soumis ?>
      <form action="" method="post">
        <h3>Login Here</h3>

        <label for="Username">Identifiant</label>
        <input type="mail" placeholder="mail" id="mail" name="mail" >
    
        <label for="mdp">Mot de passe</label>
        <input type="mdp" placeholder="mdp" id="mdp" name="mdp">
 
        <button name="login">Se connecter</button>
        <div class="social">
          <div class="go"><i class="fab fa-google"></i>  Google</div>
          <div class="fb"><i class="fab fa-facebook"></i>  Facebook</div>
        </div>
        <p>Don't have an account? <button name="signup" type="submit">S'inscrire</button></p>
      </form>



      
    <?php } else { // Si le formulaire d'inscription a été soumis ?>
      <form action="" method="post">
        <h3>Sign Up Here</h3>

        <label for="fullname">Nom</label>
        <input type="text" placeholder="Full Name" id="nom" name="nom"required>

        <label for="email">Email</label>
        <input type="email" placeholder="Email" id="mail" name="mail"required>

        <label for="password">Mot de passe</label>
        <input type="password" placeholder="Password" id="mdp" name="mdp" minlength="10"required>
        <label for="confirm-password">Confirmer le mot de passe</label>
        <input type="password" placeholder="Confirm Password" id="confirm-password" name="confirm-password">


        <button name="signup">S'inscrire </button>
        <p>Vous n'avez pas de compte? <button name="login" type="submit">Se connecter</button></p>
      </form>
    <?php }  ?>


    
  </body>
  
</html>
