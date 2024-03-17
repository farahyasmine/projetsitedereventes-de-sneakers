<?php

$conn= mysqli_connect("localhost:8889","root","root","sneakers");
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

?>