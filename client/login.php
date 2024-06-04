<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['email'], $_POST['pass'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['pass']; // No hashing for now

        if (empty($email) || empty($password)) {
            $message[] = 'Email and password are required.';
        } else {
            try {
                // Check if email exists in the client table
                $stmtClient = $connection->prepare("SELECT * FROM client WHERE email = ?");
                $stmtClient->bind_param('s', $email);
                $stmtClient->execute();
                $resultClient = $stmtClient->get_result();
                $rowClient = $resultClient->fetch_assoc();

                // Check if email exists in the fournisseur table
                $stmtFournisseur = $connection->prepare("SELECT * FROM fournisseur WHERE email = ?");
                $stmtFournisseur->bind_param('s', $email);
                $stmtFournisseur->execute();
                $resultFournisseur = $stmtFournisseur->get_result();
                $rowFournisseur = $resultFournisseur->fetch_assoc();

                if ($rowClient) {
                    // Here, you should hash the password stored in the database and compare it
                    // with the provided password. For now, we'll directly compare the ASCII passwords.
                    if ($password === $rowClient['mdp']) {
                        $_SESSION['ID_Client'] = $rowClient['ID_Client'];
                        echo "Login successful, dear client. Redirecting..."; // You can remove this message
                        header("Location: consommation.php");
                        exit(0);
                    } else {
                        $message[] = 'Incorrect email or password.';
                    }
                } elseif ($rowFournisseur) {
                    // Here, you should hash the password stored in the database and compare it
                    // with the provided password. For now, we'll directly compare the ASCII passwords.
                    if ($password === $rowFournisseur['mdp']) {
                        $_SESSION['Id_fr'] = $rowFournisseur['ID_fr'];
                        echo "Login successful, dear fournisseur. Redirecting...";
                        header("Location: ../fournisseur/clients.php");
                        exit(0);
                    } else {
                        $message[] = 'Incorrect email or password.';
                    }
                } else {
                    $message[] = 'Incorrect email or password.';
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        $message[] = 'Email and password are required.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <title>login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comic+Neue%3A700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400%2C500%2C600%2C700" />
  <link rel="stylesheet" href="../styles/login.css" />
  <style>
    
  </style>
</head>

<body>
  <div class="header">
    <div class="nav">
      <p class="logo">E-facture</p>
      <div class="links">
        <p>Home</p>
        <p>About</p>
        <p>Services</p>
        <p>Contact</p>
      </div>
    </div>
    <div class="frame1">
      <div class="frame2">
        <div class="login">login</div>
        <form class="frame3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="text-field-wqx">
            <div class="frame-243-5hG">Email address</div>
            <input type="text" class="text-field-ma6" name="email">
          </div>
          <div class="text-field-tPp">
            <p class="label-cai">Password</p>
            <input type="password" class="text-field-kgv" name="pass">
          </div>
          <button type="submit" name="login_btn">login</button>
          <?php if (!empty($error)) { ?>
            <div><?php echo $error; ?></div>
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
