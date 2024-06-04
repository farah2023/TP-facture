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
			<li>
				<a href="factures.php">
					<span class="text">Factures</span>
				</a>
			</li>
			<li >
				<a href="reclamations.php">
					<span class="text">Reclamations</span>
				</a>
			</li>
			<li class="active">
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



	<!-- CONTENT -->
	<section id="content">

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Consommation Annuelle</h1>
				</div>	
			</div>
			<?php
                 
			      include("../client/config.php");

				  $requete = "SELECT c.ID_Client, co.mois,
				  co.annee,ca.annee, 
				  co.consommation, 
				  ca.Consommation_ann
		          FROM consommation co
		          JOIN consommation_annuelle ca ON co.ID_client = ca.ID_client
		          JOIN client c ON co.ID_client = c.ID_client where co.mois=12 and co.annee=ca.annee" ;
	  
                  $result = mysqli_query($connection,$requete);

                  if(!$result){
                  echo 'erreur'. mysqli_error();
                  }else{ }?>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>id Client </th>
								<th>Annee </th>
								<th>consommation par client</th>
								<th>consommation par Agent</th>
								<th>Action</th>
							</tr>
						</thead>
						<?php while($ligne = mysqli_fetch_array($result) ){?>
						<tbody>
							<tr>
								<td>
									<p><?php echo $ligne['ID_Client'] ?></p>
								</td>
								<td><?php echo $ligne['annee'] ?> </td>
								<td><?php echo $ligne['consommation'] ?></td>
								<td><?php echo $ligne['Consommation_ann'] ?></td>
								<td><a href="notify.php" class="action modify">Notify</a> </td>								
							</tr>
						</tbody>
						<?php } ?>
					</table>
				</div>

			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->


	<script src="./scripts/script.js"></script>
</body>

</html>