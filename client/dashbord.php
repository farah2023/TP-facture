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
</head>

<body>	
	
    <div>
        <a href="#" class="logo">
			<span class="text">e-Facture</span>
		</a>
	</div>
	<!-- CONTENT -->
	<div id="dashbord">
	<section id="content">
		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Tableau de bord</h1>
				</div>
				<a href="consommation.php" class="btn-download">
					<span class="text">log out</span>
				</a>
			</div>
			<?php
                  session_start();
			      include("config.php");

				  $clientId = $_SESSION['ID_Client'];
				 
				  $requete = "SELECT c.mois, c.annee, f.consommation, f.etat, f.prix_TTC ,c.id_consommation
				  FROM factures f 
				  INNER JOIN consommation c ON f.id_consommation = c.id_consommation 
				  WHERE f.ID_Client = '$clientId' AND c.ID_Client = '$clientId'";
	  
                  $result = mysqli_query($connection,$requete);

                  if(!$result){
                  echo 'erreur'. mysqli_error();
                  }else{ }?>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>Date</th>
								<th>consommation</th>
								<th>status</th>
								<th>montant</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<?php while($ligne = mysqli_fetch_array($result) ){?>
						<tbody>
							<tr>
								<td>
									<p><?php echo $ligne['mois']."/".$ligne['annee'] ?></p>
								</td>
								<td><?php echo $ligne['consommation'] ?></td>
								<td><?php echo $ligne['etat'] ?></td>
								<td><?php echo $ligne['prix_TTC'] ?></td>
								<td><a href="reclamation.php" class="action modify modify-btn">Reclamer</a>
								<!-- <a href="anomalie.php?mois=<?php //echo $ligne['mois'] ?>&annee=<?php //echo $ligne['annee']?>&consommation=<?php //echo $ligne['consommation'] ?>&statut=<?php //echo $ligne['etat'] ?>&montant=<?php //echo $ligne['prix_TTC'] ?>" class="action modify modify-btn">telecharger</a> -->
							   </td>
							</tr>
							
						</tbody>
						<?php }?>
					</table>
				</div>

			</div>
		</main>
		<!-- MAIN -->
	</section>
	</div>
	<!-- CONTENT -->


	<script src="./scripts/script.js"></script>
</body>

</html>