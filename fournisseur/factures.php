
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<!-- My CSS -->
	<link rel="stylesheet" href="../styles/style2.css">

	<title>Fournisseur</title>
	<style>
		.compteur-image {
         width: 50px;
         height: 50px;
         border-radius: 50%;
        }
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
			<li>
				<a href="clients.php">
					<span class="text">Clients</span>
				</a>
			</li>
			<li class="active">
				<a href="factures.php">
					<span class="text">Factures</span>
				</a>
			</li>
			<li >
				<a href="reclamations.php">
					<span class="text">Reclamations</span>
				</a>
			</li>
			<li >
				<a href="consommation_ann.php">
					<span class="text">Consommation Anuelle</span>
				</a>
			</li>

		</ul>
		<ul class="side-menu">

			<li>
				<a href="../client/Acceuil.php" class="logout">
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
					<h1>Factures</h1>
				</div>	
				<br><br>
				<div class="row g-3 align-items-center">
					 <div class="col-auto">
						<label for="inputPassword6" class="col-form-label">client:</label>
					</div>
					<div class="col-auto">
						<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<input type="text" id="inputPassword6" name="client" class="form-control" aria-describedby="passwordHelpInline">
					</form>
				</div>
				</div>
			</div>
			<?php
			 
                  $conn = mysqli_connect('localhost','root','','e-facture') or die('connection failed'); 
				  if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
					$client = $_POST["client"];
				

					$requete = "SELECT c.mois, c.annee, c.consommation, f.etat, f.prix_TTC, f.prix_HT , c.image,c.etat_cons
					FROM consommation c
					LEFT JOIN factures f ON f.id_consommation = c.id_consommation 
					WHERE c.ID_Client = '$client'";
		

                  $result = mysqli_query($conn,$requete);

                  if(!$result){
                  echo 'erreur'. mysqli_error();
                  }else{ }?>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>Date</th>
								<th>consommation </th>
								<th>compteur </th>
								<th>prix HT </th>
								<th>prix TTC </th>
								<th>etat de paiment </th>
								<th>statut </th>
								<th>Action</th>
							</tr>
						</thead>
						<?php while($ligne = mysqli_fetch_array($result) ){?>
						<tbody>
							<tr>
								<td><?php echo $ligne['mois']."/".$ligne['annee'] ?> </td>
								<td><?php echo $ligne['consommation'] ?></td>
								<td><?php echo "<img class='compteur-image' src='../client/uploads/" . $ligne['image']  . "' alt='compteur image'>";?></td>
								<td><?php echo $ligne['prix_HT'] ?></td>
								<td><?php echo $ligne['prix_TTC'] ?></td>
								<td><?php echo $ligne['etat'] ?></td>
								<td><?php echo $ligne['etat_cons'] ?></td>
								<td>
                                <a href="anomalie_modif.php" class="action modify">modifier</a> </td>
    </td>

							</tr>
						</tbody>
						<?php }}?>
					</table>
				</div>

			</div>
		</main>
		<!-- MAIN -->
	</section>

    
	


<script src="./scripts/script.js">
</body>

</html>