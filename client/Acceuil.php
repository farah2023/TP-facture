<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../styles/style1.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" rel="stylesheet" />
    <title>E-facture</title>
</head>

<body>

    <header>
        <span class="logo">E-facture</span>
        <nav class="navigation">
            <a href="#Home">Home</a>
            <a href="#About">A propos</a>
            <a href="#services">Services</a>
            <a href="#contact">Contacte</a>
        </nav>
        <button class="login"> <a href="login.php">login</a></button>
    </header>


    <section id="Home" class="main">
        <div>
            <h2>Bienvenue sur <br><span>E-facture</span></h2>
            <div class="desc">
            <p>
               Avec notre platforme vous pouvez maintenant avoir vos facture en double-click ,reclamer vos probleme ET visualiser votre paiment de facture 
            </p>
            </div>
            <a href="#projects" class="main-btn">Lire plus</a>    
        </div>
        <img class="img1" src="../img/Rectangle7.png" >
    </section>
    <img class="img3" src="../img/Rectangle6.png" >
    <section id="About" class="About">
        <div> <img class="img2" src="../img/Rectangle8.png" ></div>
        <div class="text">
            <h1> A propos de nous </h1>
             <span>Nous somme e-facture et on est la pour vous aider a faciliter l'obtention de vos facture en ligne juste 
                a l'aide de votre Consommation et votre compteur et vous pouvez chaque fin de mois avoir votre facture

             </span>
  
        </div>

    </section>
    <img class="img4" src="../img/Rectangle6.png" >
    <section class="cards" id="services">
        <h2 class="title">Services</h2>
        <div class="content ">
            <div class="card">
              
                <div class="info">
                    <h3>Facturation</h3>
                    <p>sur notre page vous pouvez telecharger vos facture et visualiser les factures non payer et leur montant </p>
                </div>
            </div>
            <div class="card">
              
                <div class="info">
                    <h3>Reclamation</h3>
                    <p>sur notre page vous pouvez deposez vos reclamation qui seront raiter par le service convenable
                    </p>
                  
                </div>
            </div>
            <div class="card">
                
                <div class="info">
                    <h3>Consommation</h3>
                    <p>sur notre page vous pouvez deposez votre Consommation mensuelle qui sera utile pour le service de generation de facture</p>
                  

                </div>
            </div>
        </div>
    </section>



    <section class=" contact" id="contact">
        <h2 class="title">Contact US</h2>
        <form>
            <div class="contain-form">

                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" id="form4Example2" class="form-control back" />
                    <label class="form-label" for="form4Example2">Email address</label>
                </div>

                <!-- Message input -->
                <div class="form-outline mb-4">
                    <textarea class="form-control back" id="form4Example3" rows="4"></textarea>
                    <label class="form-label" for="form4Example3">Message</label>
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-block mb-4 color">Send</button>
            </div>
        </form>
    </section>

    <footer class="footer">
        <p class="footer-title">Copyrights @ <span>E-facture</span></p>
        <div class="social-icons icon-foot">
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
           
        </div>
    </footer>


    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
</body>

</html>