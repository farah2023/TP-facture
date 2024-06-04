<?php
include '../client/config.php';
// Vérification de la soumission du formulaire
if(isset($_POST['submit'])){
  // Récupération des données du formulaire et échappement des caractères spéciaux pour éviter les failles de sécurité
   $nom = mysqli_real_escape_string($connection, $_POST['nom']);
   $prenom = mysqli_real_escape_string($connection, $_POST['prenom']);
   $adresse = mysqli_real_escape_string($connection, $_POST['adresse']); 
   $email = mysqli_real_escape_string($connection, $_POST['email']); 
   $password = mysqli_real_escape_string($connection, $_POST['password']); 


   $select = mysqli_query($connection, "SELECT * FROM `client` WHERE email = '$email' ") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $message[] = 'client deja existe'; 
}else{

      // Insère les données dans la base de données et redirige vers la page de connection$connectionexion en cas de succès
      $insert = mysqli_query($connection, "INSERT INTO `client`(Nom,Prenom , Adresse, email,ID_fr,mdp) VALUES('$nom', '$prenom', '$adresse', '$email',1,$password)") or die('query failed');
      if($insert){
         $message[] = 'client ajouter !'; // Message de réussite
         header('location:clients.php'); // Redirige vers la page de connexion
      }else{
         $message[] = ' échouee!'; // Message d'erreur
      } } }?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="../styles/style2.css">

	<title>Fournisseur</title>
	<style>
        /* Style du formulaire */
        #editForm {
            max-width: 300px;
            margin:  50px auto;
            padding: 40px;
            border: 1px solid #ccc;
            border-radius: 25px;
            background-color: #f9f9f9;
			
        }

        #editForm input[type="text"],
        #editForm input[type="email"],input[type="password"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box; 
        }

        #editForm .button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #1b6953;
            color: white;
            cursor: pointer;
        }

        #editForm .button:hover {
            background-color: #1b6953;
        }

		
    </style>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<span class="text">e-Facture</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="clients.php">
					<span class="text">Clients</span>
				</a>
			</li>
			<li>
				<a href="factures.php">
					<span class="text">Factures</span>
				</a>
			</li>
			<li>
				<a href="reclamation.php">
					<span class="text">Reclamations</span>
				</a>
			</li>
			<li>
				<a href="Consommation_ann.php">
					<span class="text">Consommation Anuelle</span>
				</a>
			</li>

		</ul>
		<ul class="side-menu">

			<li>
				<a href="#" class="logout">
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->
	<section id="content">

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Add clients</h1>
				</div>
			</div>
	    </main>	
    </section>
	<form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" placeholder="First Name" name="prenom">
        <input type="text" placeholder="Last Name" name="nom">
		<input type="text" placeholder="adresse" name="adresse">
        <input type="email" placeholder="Email" name="email">
		<input type="password" placeholder="password" name="password">
		<input type="submit" name="submit" class="button" value="Ajouter">
    </form>
	<script src="./scripts/script.js"></script>
  </body>

</html>