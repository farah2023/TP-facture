<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])){
  $mail=new PHPMailer();
  $mail->isSMTP();
  $mail->Host='smtp.gmail.com';
  $mail->SMTPAuth =true;
  $mail->Username='farah.boughait@etu.uae.ac.ma';
  $mail->Password='vmxenhehlxlrnhsl';
  $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port=587;
  $mail->setFrom('farah.boughait@etu.uae.ac.ma');

  $mail->addAddress($_POST["email"]);
  $mail->isHTML(true);

  $mail->Subject=$_POST["subject"];
  $mail->Body=$_POST["message"];
  $mail->AltBody=$_POST["message"];
  $mail->send();

  echo"
  <script> alert('reponse envoyer') </script>
  ";


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
	<section id="content">

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Repondre a la Reclamation</h1>
				</div>
			</div>
	    </main>	
    </section>
	<form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="email" placeholder="Email" name="email">
        <input type="text" placeholder="subject" name="subject">
        <input type="text" placeholder="message" name="message">
		<input type="submit" name="send" class="button" value="envyer">
    </form>
	<script src="./scripts/script.js"></script>
  </body>

</html>