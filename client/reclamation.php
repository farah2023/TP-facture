<?php
session_start();
include("config.php");

// Check connection


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data

    $type_rec = $_POST['type_rec'];
    $description = $_POST['description'];
    $clientId = $_SESSION['ID_Client'];

    // SQL query to insert data into the 'reclamation' table
    $sql = "INSERT INTO reclamation (ID_Client, ID_fr, Type_Reclamation, Description,etat) VALUES ('$clientId', 1,'$type_rec', '$description','non traite')";

    if ($connection->query($sql) === TRUE) {
     
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$connection->close();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <title>Reclamation</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comic+Neue%3A700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400%2C600%2C700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter%3A700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400%2C600" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto%3A400%2C700" />
  <link rel="stylesheet" href="../styles/client.css" />
</head>

<body>
  <div class="item-1-v8n">
    <div class="auto-group-6vze-f6N">
      <p class="e-facture-y74">E-facture</p>
      <div class="group-4-48W">
        <div class="rectangle-1-moc">
        </div>
        <a href="consommation.php" class="reclamation-VUi">Consomation</a>
      </div>
      <div class="group-4-48W">
        <div class="rectangle-1-moc">
        </div>
        <a href="dashbord.php" class="reclamation-VUi">Factures</a>
      </div>
    
    </div>
    <div class="auto-group-cktt-XTL">
      <div class="auto-group-d3pt-enr">
        <div class="links-yKL">
          <a href="Acceuil.php">Home</a>
          <a href="Acceuil.php">About</a>
          <a href="Acceuil.php">Services</a>
          <a href="Acceuil.php">Contact</a>
        </div>
        <div class="group-6-sp2">
          <div class="rectangle-1-EPg">
          </div>
          <a  href ="Acceuil.php" class="log-out-Zwk">log out</a>
        </div>
      </div>


      <form class="frame-1-sSe" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="input-o5Q">
          <!-- <div class="label-KpS">email</div>
              <input name="email" type ="email" class="inputcontainer-cYe">
        </div> -->
        <div class="input-o5Q">
          <div class="label-KpS"> reclamation</div>
               <select id="nbr_projet" name="type_rec"class="inputcontainer-cYe" > 
               <option value="fuite interne" selected="selected">fuite interne</option>
               <option value="fuite externe">fuite externe</option>
               <option value="3">Facture</option>
              <option value="4">autre</option>
             </select>
         </div>    
        <br><br>
        <div class="auto-group-jrml-wMx">
          <div class="input-Usg">
            <div class="label-dVg">Description:</div>
            <textarea name="description" id="" cols="30" rows="10"></textarea>
          </div>
        </div>
        <br><br><br><br>
       
        </div>
  
          <input type="submit" name = "submit" value="Envoyer">
    
</form>
    </div>
  </div>
</body>