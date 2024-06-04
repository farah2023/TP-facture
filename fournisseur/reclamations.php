
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
			<li class="active">
				<a href="reclamations.php">
					<span class="text">Reclamations</span>
				</a>
			</li>
			<li>
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
					<h1>Gestion de Reclamation</h1>
				</div>
				
			</div>
			<?php
                  $conn = mysqli_connect('localhost','root','','e-facture') or die('connection failed'); 
                  $requete = "SELECT * FROM reclamation R INNER JOIN client Cl ON R.ID_Client = Cl.ID_Client ORDER BY ID_Reclamation ASC";

                  $result = mysqli_query($conn,$requete);

                  if(!$result){
                  echo 'erreur'. mysqli_error();
                  }else{ }?>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>Client </th>
								<th>Type Reclamation </th>
								<th>description </th>
								<th>Email</th>
								<th>status</th>
								<th>repondre</th>
								
							</tr>
						</thead>
						<?php while($ligne = mysqli_fetch_array($result) ){?>
						<tbody>
							<tr>
								<td>
									<p><?php echo $ligne['Nom']." ".$ligne['Prenom'] ?> </p>
								</td>
								<td><?php echo $ligne['Type_Reclamation'] ?>  </td>
								<td><?php echo $ligne['Description'] ?>  </td>
								<td><?php echo $ligne['email'] ?> </td>
								<td><?php echo $ligne['etat'] ?> </td>
								<td><a href="mail.php" class="class="action modify"">repondre</a></form> </td>
							
							</tr>
							<!-- Inside your while loop where "Repondre" is located -->

<!-- Create a container for the reply form -->


							
						</tbody>
						<?php }?>
					</table>
					<!-- Inside your while loop where "Repondre" is located -->

   </section>
    <!-- Reply form -->



				</div>

			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<script>
</script>

	<script src="./scripts/script.js"></script>
</body>

</html>