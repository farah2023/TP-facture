<?php

// Établit une connexion à la base de données en utilisant l'hôte, le nom d'utilisateur, le mot de passe et le nom de la base de données appropriés
  $connection = mysqli_connect('localhost','root','','e-facture') or die('connection failed');
?>