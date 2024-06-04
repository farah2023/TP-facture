<?php
include '../client/config.php';

    // Your code for processing the form submission goes here
    if(isset($_POST['submit'])) {
        $consommation_compteur = mysqli_real_escape_string($connection, $_POST['consommation_compteur']);
        $statut = mysqli_real_escape_string($connection, $_POST['statut']);
		$mois = mysqli_real_escape_string($connection, $_POST['mois']);
		$annee = mysqli_real_escape_string($connection, $_POST['annee']);
        $clientid = mysqli_real_escape_string($connection, $_POST['clientid']);

        // Update the database with the provided values
        $update_query = "UPDATE `consommation` SET consommation = '$consommation_compteur', etat_cons = '$statut' WHERE ID_Client = '$clientid' AND Mois = '$mois' AND Annee = '$annee'";
        $update_result = mysqli_query($connection, $update_query);

        if($update_result) {
            // Redirect back to factures.php after successful update
            header('Location: factures.php');
            exit(); // Ensure script execution stops after redirection
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }

?>
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
				<a href="#">
					<span class="text">Reclamations</span>
				</a>
			</li>
			<li>
				<a href="#">
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
	    <input type="text" placeholder="id de client" name="clientid">
        <input type="text" placeholder="consommation du compteur" name="consommation_compteur">
        <input type="text" placeholder="statut" name="statut">
		<input type="text" placeholder="mois" name="mois">
		<input type="text" placeholder="annee" name="annee">
		<input type="submit" name="submit" class="button" value="modifier">
    </form>
	<script src="./scripts/script.js"></script>
  </body>

</html>