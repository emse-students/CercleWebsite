<?php
session_start();
 if (isset($_SESSION["id_cercle"])) {
     header("location: compte.php");
 }
?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8" />
        <?php 
        echo "<link rel='stylesheet' href='css/style.css?".time()."'/>";
        ?>
        <link rel="icon" type="image/png" href="images/touchicon.png" />
        <link rel="apple-touch-icon" href="images/appleicon.png" />
        <title>Cercle EMSE-Accueil</title>
    </head>

    <body>
    	<?php 
    	$page=0;
    	include("php/header.php");
    	?>
    	<div class="page">
    		<h1>Bienvenue sur le site du Cercle de l'EMSE</h1>
    		<div class="centreur"><p>"La pression on ne la subit pas, on la boit"</p></div>
    		<div class="centreur">
                <div class="C_centre">
                    <p>Pour accedez à votre compte veuillez vous identifiez via la plateforme de l'école</p>
        			<a href="compte.php"><div class="bouton">Connexion</div></a>
                    <?php
                    if (isset($_GET["erreur"])) {
                       echo "<p class=erreur>".$_GET["erreur"]."</p>";
                       echo "Si vous êtes connecté au CAS mais que vous retombez ici :<br>
                            - Vous n'avez pas de compte Cercle => contacter un membre du Cercle pour plus d'information<br>
                            - Vous avez un compte Cercle => contacter Corentin Doué pour résoudre le problème";
                    }
                    ?>
                </div>
    		</div>
    	</div>
    </body>