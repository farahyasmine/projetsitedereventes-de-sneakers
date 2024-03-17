<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
  <title>Messagerie</title>
</head>
<body>
<link rel="stylesheet" href="messagerie.css">
  <h1>Messagerie</h1>
  
  <?php
  session_start();
  if(!isset($_SESSION["mail"])){
    echo '<p>Connectez-vous pour contacter le vendeur.</p>';
    header("location: compte.php");
    exit();
  }
  ?>
   <?php
   $ida = $_GET['ida'];
   ?>

  <div class="form-container">
    <h3>Contacter le vendeur</h3>
    <form id="contactForm" action="traitement_message.php?ida=<?php echo $ida; ?>" method="POST">
  
      <textarea name="message" placeholder="Votre message" required></textarea>
      <input type="submit" value="Envoyer">
    </form>
  </div>

</body>
</html>
