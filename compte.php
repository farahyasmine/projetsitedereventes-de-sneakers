<?php
session_start();
if(!isset($_SESSION["mail"])){
    header("location: inscription.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mon compte</title>
    <link rel="stylesheet" href="compte.css">
</head>
<body>
	          <h1>Mon compte</h1>
	<nav>
		<ul>
    <li><a href="?page=infos">Mes infos</a></li>
    <li><a href="?page=annonces">Mes annonces</a></li>
    <li><a href="?page=messages">Mes messages</a></li>
    <li><a href="?page=recherches">Mes favoris et recherches précédentes</a></li>
    <li><a class="disconnect-link" href="deconnexion.php">Se Déconnecter <img src="assets/img/disconnect.png" style="width: 20px; height: 20px;"></a></li>
    <li><a class="arrow-link" href="page1.html">&larr; Retour</a></li>
  </ul>
</nav>

	</nav>
	<div id="contenu">
		<?php
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		
        header("Cache-Control: no-cache, must-revalidate"); // Pour éviter la mise en cache du navigateur
        include "connect.php";
       

		if (isset($_GET['page'])) {
			$page = $_GET['page'];		
			if ($page == 'infos') {
				echo '<h2>Mes infos</h2>';
				echo '<p>Contenu de la rubrique "Mes infos".</p>';
				$req = "SELECT nom, mail FROM compte WHERE mail = '{$_SESSION['mail']}'";
				$result = mysqli_query($conn, $req);
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						echo 'Nom : ' . $row['nom'] . '<br>';
						echo 'Email : ' . $row['mail'];
					}
				}
			} elseif ($page == 'annonces') {
				echo '<h2>Mes annonces</h2>';
				echo '<p>Contenu de la rubrique "Mes annonces".</p>';
				
				// Récupérer les annonces publiées par l'utilisateur connecté
				$requete = "SELECT * FROM  `annonce` WHERE  `vendeur` = '".$_SESSION["mail"]."'";
				$res = mysqli_query($conn, $requete);
				
				if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        // Afficher les détails de chaque annonce
        echo "<h2>Annonce #" . $row['ida'] . "</h2>";
        echo "<p> <b> Annonce :</b> " . $row['annonce'] . "</p>";
        echo "<p>Pointure : " . $row['pointure'] . "</p>";
        echo "<p>Marque : " . $row['marque'] . "</p>";
        echo "<p>Prix : " . $row['prix'] . "</p>";
        
        // Afficher la photo
        echo "<p>Photo :</p>";
     // Afficher la photo
echo "<p>Photo :</p>";
echo '<img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Photo" style="width: 300px;" />';

echo "<br>";
echo "<br>";
        echo "<a href='supannonce.php?ida=" . $row['ida'] . "' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')\">Supprimer l'annonce</a>";
		echo "<br>";
        echo "<a href='modannonce.php?ida=" . $row['ida'] . "'>Modifier l'annonce</a>";

        echo "<hr>";
    }
} else {
    echo "Aucune annonce publiée sur votre compte.";
}
				
				echo '<h1>Ajouter une annonce</h1>'; ?>
			<form action="traitement_annonce.php" method="post" enctype="multipart/form-data" > 
					<?php
				echo '<label for="annonce">Annonce :</label>';
				echo '<textarea id="annonce" name="annonce" required></textarea>';
				echo '<label for="pointure">Pointure :</label>';
				echo '<input type="number" id="pointure" name="pointure" required>';
				echo '<label for="marque">Marque :</label>';
				echo '<input type="text" id="marque" name="marque" required>';
				echo '<label for="prix">Prix :</label>';
				echo '<input type="number" id="prix" name="prix" required>';
				echo '<label for="photo">Photo :</label>';
                echo '<input type="file" id="photo" name="photo" required>';
				echo '<input type="submit" value="Ajouter">'; ?>
				
			</form>
			<?php
			
		} elseif ($page == 'messages') {
			echo '<h2>Mes messages</h2>';
		
			// Récupérer l'identifiant de l'utilisateur connecté
			$idc = $_SESSION['mail'];
		
			// Requête pour récupérer les annonces et les messages correspondants
			$sql = "SELECT m.idm, m.message, m.date, a.vendeur, c.mail, a.ida, c.idc
					FROM messagerie m
					INNER JOIN annonce a ON m.ida = a.ida
					INNER JOIN compte c ON m.idc = c.idc
					WHERE (a.vendeur = '{$_SESSION['mail']}' OR c.mail = '{$_SESSION['mail']}')
					ORDER BY m.date DESC";
		
			// Exécution de la requête
			$result = mysqli_query($conn, $sql);
		
			if ($result && mysqli_num_rows($result) > 0) {
				$previous_ida = null; // Identifiant de l'annonce précédente
				$previous_idc = null; // Identifiant du client précédent
		
				while ($row = mysqli_fetch_assoc($result)) {
					$ida = $row['ida'];
					$idc = $row['idc'];
		
					// Affichage de l'annonce si différente de l'annonce précédente
					if ($ida !== $previous_ida || $idc !== $previous_idc) {
						echo '<div class="message">';
						echo '<h3>Annonce : ' . $ida . '</h3>';
		
						// Nommer le message en fonction du compte connecté
						if ($row['vendeur'] === $_SESSION['mail']) {
							echo '<h4>Message client : ' . $row['mail'] . ' ' . $idc . '</h4>';
						} else {
							echo '<h4>Réponse vendeur (' . $row['vendeur'] . ') : ' . $row['mail'] . ' ' . $idc . '</h4>';
						}
		
						// Afficher le message d'origine uniquement une fois
						if (!empty($row['message'])) {
							// Appliquer une classe CSS différente en fonction de l'utilisateur
							$messageClass = ($idc === $_SESSION['mail']) ? 'user-message' : 'other-message';
							echo '<p class="' . $messageClass . '">Message : ' . $row['message'] . ' - Date : ' . $row['date'] . '</p>';
						}
		
						// Affichage des réponses existantes pour cette annonce
						$replySql = "SELECT message, date, idc FROM messagerie WHERE ida = '$ida' AND idc = '$idc'";
						$replyResult = mysqli_query($conn, $replySql);
		
						if ($replyResult && mysqli_num_rows($replyResult) > 1) {
							while ($replyRow = mysqli_fetch_assoc($replyResult)) {
								// Appliquer une classe CSS différente en fonction de l'utilisateur
								$replyClass = ($replyRow['idc'] === $_SESSION['mail']) ? 'user-reply' : 'other-reply';
								echo '<p class="' . $replyClass . '">Réponse : ' . $replyRow['message'] . ' -  ' . $replyRow['date'] . '</p>';
							}
						}
		
						// Formulaire de réponse
						echo '<form method="post" action="" class="reply-form">';
						echo '<input type="hidden" name="reply_ida" value="' . $ida . '">';
						echo '<input type="hidden" name="reply_idc" value="' . $idc . '">';
						echo '<textarea name="reply_message" placeholder="Répondre au message"></textarea>';
						echo '<input type="submit" name="reply" value="Reply">';
						echo '</form>';
		
						echo '</div>';
		
						// Mise à jour des identifiants précédents
						$previous_ida = $ida;
						$previous_idc = $idc;
					}
				}
			} else {
				echo '<p>Aucun message trouvé.</p>';
			}
		
		
		if (isset($_POST['reply'])) {
			// Récupérer les valeurs du formulaire
			$message = $_POST['reply_message'];
			$ida = $_POST['reply_ida'];
			$idc = $_POST['reply_idc'];
		  
			// Vérifier si l'identifiant de l'annonce est défini
			if (!empty($ida)) {
				// Insérer la réponse dans la base de données
				$insertSql = "INSERT INTO messagerie (idm, message, date, ida, idc) VALUES (NULL, '$message', NOW(), '$ida', '$idc')";
				$insertResult = mysqli_query($conn, $insertSql);
		
				if ($insertResult) {
					// Rediriger vers la page des messages pour éviter la soumission du formulaire lors du rafraîchissement de la page
					header('Location: compte.php?page=messages');
					exit();
				} else {
					echo '<p>Erreur lors de l\'envoi du message : ' . mysqli_error($conn) . '</p>';
				}
			} else {
				echo '<p>Erreur lors de l\'envoi du message : identifiant de l\'annonce manquant.</p>';
			}
		}
		
		
		
	} elseif ($page == 'recherches') {
		echo '<h2 class="section-title">Mes favoris et recherches précédentes</h2>';
		echo '<p class="section-description">Contenu de la rubrique "Mes favoris et recherches précédentes".</p>';
	
		// Récupérer les favoris de l'utilisateur connecté
		$reqFavoris = "SELECT a.ida, a.annonce, a.prix, a.pointure FROM favoris f INNER JOIN annonce a ON f.ida = a.ida WHERE f.mail = '{$_SESSION['mail']}'";
		$resFavoris = mysqli_query($conn, $reqFavoris);
	
		if (mysqli_num_rows($resFavoris) > 0) {
			echo '<ul class="favoris-list">';
			while ($row = mysqli_fetch_assoc($resFavoris)) {
				echo '<li class="favori-item">';
				echo '<span class="favori-id">ID: ' . $row['ida'] . '</span>';
				echo '<span class="favori-name">' . $row['annonce'] . '</span>';
				echo '<span class="favori-price">Prix: ' . $row['prix'] . '</span>';
				echo '<span class="favori-size">Pointure: ' . $row['pointure'] . '</span>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<p class="no-favoris">Aucun favori trouvé.</p>';
		}
	}
}
	?>
	
	



			
	
	</div>
   


</body>

</html>