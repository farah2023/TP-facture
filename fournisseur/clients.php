<?php
include '../client/config.php';
// Vérification de la soumission du formulaire
if(isset($_POST['submit'])){
  // Récupération des données du formulaire et échappement des caractères spéciaux pour éviter les failles de sécurité
   $nom = mysqli_real_escape_string($connection, $_POST['nom']);
   $prenom = mysqli_real_escape_string($connection, $_POST['prenom']);
   $adresse = mysqli_real_escape_string($connection, $_POST['adresse']); 
   $email = mysqli_real_escape_string($connection, $_POST['email']); 
   $update_id = mysqli_real_escape_string($connection, $_POST['update_id']);




   $select = mysqli_query($connection, "SELECT * FROM `client` WHERE email = '$email' ") or die('query failed');
   $update = mysqli_query($connection, "UPDATE `client` SET Nom = '$nom', Prenom = '$prenom', Adresse = '$adresse', email = '$email' WHERE ID_Client = '$update_id'") or die('query failed');
      if($update){
         $message[] = 'client modifier !'; // Message de réussite
         header('location:clients.php'); // Redirige vers la page de connexion
      }else{
         $message[] = ' échouee de modification!'; // Message d'erreur
      } }?>
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
            display: none;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        #editForm input[type="text"],
        #editForm input[type="email"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box; /* Pour inclure le padding dans la largeur */
        }

        #editForm button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #1b6953;
            color: white;
            cursor: pointer;
        }

        #editForm button:hover {
            background-color: #1b6953;
        }
#editForm input[type="submit"] {
  background-color: #4CAF50;
  color: white;
  padding: 8px 16px;
  border: none;
  cursor: pointer;
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
				<a href="#">
					<span class="text">Clients</span>
				</a>
			</li>
			<li>
				<a href="factures.php">
					<span class="text">Factures</span>
				</a>
			</li>
			<li>
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
					<h1>All clients</h1>
				</div>
				<a href="add_client.php" class="btn-download ">
					<span class="text" >Add client</span>
				</a>
			</div>
			<?php
                  $conn = mysqli_connect('localhost','root','','e-facture') or die('connection failed'); 
                  $requete = " SELECT * FROM client ORDER BY ID_Client ASC";
                  $result = mysqli_query($conn,$requete);

                  if(!$result){
                  echo 'erreur'. mysqli_error();
                  }else{ }?>
			<div class="table-data">
				<div class="order">
					<table>
						<thead>
							<tr>
								<th>last name </th>
								<th>First name</th>
								<th>Adresse</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>
						<?php while($ligne = mysqli_fetch_array($result) ){?>
						<tbody>
							
							<tr>
								<td><p><?php echo $ligne['Nom'] ?> </p></td>
								<td><?php echo $ligne['Prenom'] ?></td>
								<td><?php echo $ligne['Adresse'] ?></td>
								<td><?php echo $ligne['email'] ?></td>
								<!-- <td><span class="action modify modify-btn" onclick="showEditForm(this)">Modify</span></td> -->
								<td><span class="action modify modify-btn" onclick="showEditForm(this, <?php echo $ligne['ID_Client']; ?>)">Modifier</span></td>

							</tr>
					
						</tbody>
						<?php }?>
					</table>
				</div>

			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

</div>
	<form id="editForm" style="display: none;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" placeholder="First Name" name="prenom">
        <input type="text" placeholder="Last Name" name="nom">
		<input type="text" placeholder="adresse" name="adresse">
        <input type="email" placeholder="Email" name="email">
        <input type="submit" onclick="saveChanges()" name="submit" value="save" >
    </form>
	
	<script src="./scripts/script.js"></script>
</body>
<script>
function showEditForm(element, clientId) {
    var form = document.getElementById('editForm');
    var row = element.closest('tr');
    var cells = row.cells;
    var inputs = form.getElementsByTagName('input');

    for (var i = 0; i < cells.length - 1; i++) {
        inputs[i].value = cells[i].innerText;
    }

    // Set the value of the hidden input field to the client ID
    var idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'update_id';
    idInput.value = clientId;
    form.appendChild(idInput);

    form.style.display = 'block';
    row.style.display = 'none';
}

</script>
   
</html>